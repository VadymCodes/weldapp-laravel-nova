<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadIDRequest;
use App\Mail\VerifyEmail;
use App\Models\Document;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class VerificationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Mark the user's email address as verified.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request, int $id)
    {
        $user = User::find($id);
        if (! URL::hasValidSignature($request)) {
            return response()->json([
                'status' => trans('verification.invalid'),
            ], 200);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status' => trans('verification.already_verified'),
            ], 200);
        }

        $user->markEmailAsVerified();

        // event(new Verified($user));

        return response()->json([
            'status' => trans('verification.verified'),
        ]);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (is_null($user)) {
            throw ValidationException::withMessages([
                'email' => [trans('verification.user')],
            ]);
        }

        if ($user->hasVerifiedEmail()) {
            throw ValidationException::withMessages([
                'email' => [trans('verification.already_verified')],
            ]);
        }

        // $user->sendEmailVerificationNotification();
        $url = URL::temporarySignedRoute(
            'verification.verify', Carbon::now()->addMinutes(60), ['id' => $user->id]
        );
        $url = str_replace('api/v1/', '', $url);
        Mail::to($request->email)->send(new VerifyEmail($url));

        return response()->json(['status' => trans('verification.sent')]);
    }

    /**
     * Send sms code for phone verification.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSMS(Request $request)
    {
        /* Get credentials from .env */
        $twilio = config('services.twilio');
        try {
            $client = new Client($twilio['account_sid'], $twilio['auth_token']);
            $number = '+44' . Auth::user()->phone;
            $client->verify->v2->services($twilio['verificaton_sid'])
                ->verifications
                ->create($number, "sms");
            return response()->json(['status' => trans('verification.sent')]);
        } catch (\Exception $e) {
            return response()->json(['message' => trans('verification.invalid')]);
        }
    }

    /**
     * Verify sms code for phone verification.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifySMS(Request $request)
    {
        $twilio = config('services.twilio');
        $client = new Client($twilio['account_sid'], $twilio['auth_token']);
        $number = '+44' . Auth::user()->phone;
        $verification = $client->verify->v2->services($twilio['verificaton_sid'])
            ->verificationChecks
            ->create($request->code, array('to' => $number));
        if ($verification->valid) {
            Auth::user()->markPhoneAsVerified();
            return response()->json(['status' => trans('verification.verified')]);
        }
        return response()->json([
                'status' => trans('verification.invalid'),
            ], 200);
    }

    /**
     * Upload ID document
     *
     * @param  UploadIDRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadID(UploadIDRequest $request)
    {
        $path = $request->file('image')->store('documents', 'public');
        $data = $request->all();
        $type = $data['type'];
        $document = Document::create([
            'type' => $data['type'],
            'url' => $path,
            'user_id' => auth()->id(),
            'status' => 'pending'
        ]);
        return response()->json([
                'status' => $document
            ], 200);
    }
}