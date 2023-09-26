<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\AccountCreated;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\CreateUserRequest;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class UserController extends Controller
{
    /**
     * Create a new User and then authenticate them.
     *
     * @param CreateUserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(CreateUserRequest $request)
    {
        $type = $request->driver;

        if (!empty($type) && !is_bool($type)) {
            return response()->json([
                'error' => [
                    'message' => trans('errors.something_went_wrong')
                ]
            ], Response::HTTP_BAD_REQUEST);
        }

        $role = $type ? User::$ROLE_DRIVER : User::$ROLE_MECHANIC;
        $newUser = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password' => Hash::make($request->input('password')),
            'address' => $request->input('address'),
            'latitude' => $request->input('lat'),
            'longitude' => $request->input('lng'),
            'role' => $role
        ]);

        if (!empty($newUser)) {

            $token = Auth::login($newUser);

            Mail::to($request->input('email'))->send(new AccountCreated());
            $url = URL::temporarySignedRoute(
                'verification.verify', Carbon::now()->addMinutes(60), ['id' => $newUser->id]
            );
            $url = str_replace("/api/v1", "", $url);
            Mail::to($request->input('email'))->send(new VerifyEmail($url));

            // return response()->json($newUser, Response::HTTP_NO_CONTENT);
            return $this->respondWithToken($token);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Test email
     *
     */
    public function test()
    {
        // return (new AccountCreated())->render();
        $url = URL::temporarySignedRoute(
            'verification.verify', Carbon::now()->addMinutes(60), ['id' => 5]
        );
        $url = str_replace("/api/v1", "", $url);
        return (new VerifyEmail($url))->render();
        // Mail::to('vladshedrin3232@gmail.com')->send(new AccountCreated());
    }
}
