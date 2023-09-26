<?php


namespace App\Services;


use App\Helpers\SocialiteHelper;
use App\Services\SocialiteServiceInterface;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Laravel\Socialite\Facades\Socialite;

class SocialiteService implements SocialiteServiceInterface
{
    public function getRedirectUrlByProvider($provider): array
    {
        return [
            'redirectUrl' => Socialite::driver($provider)
                ->stateless()
                ->redirect()
                ->getTargetUrl()
        ];
    }

    public function loginWithSocialite($provider): array
    {
        $userSocial = Socialite::driver($provider)->stateless()->user();
        if (SocialiteHelper::isSocialPresent($userSocial)) {
            $user = $this->searchUserByEmail($userSocial->email);
            if ($user) {
                $isLogged = SocialiteHelper::compareUserWithSocialite($user, $userSocial);
                if ($isLogged) {
                    $token = Auth::login($user);
                    return $this->prepareSuccessResult($token);
                }
                return $this->prepareErrorResult();
            } else {
                // $user = New User([], $userSocial);
                // return $user->save()
                //     ? $this->prepareSuccessResult($user)
                //     : $this->prepareErrorResult();
                return $this->prepareSignupResult($userSocial);
            }
        } else {
            return $this->prepareErrorResult();
        }
    }

    private function makeAuthenticationCookie($result)
    {
        $result['cookie'] = cookie('authentication',
            json_encode($result),
            8000,
            null,
            null,
            false,
            false
        );
        return $result;
    }

    private function searchUserByEmail($email): ?User
    {
        return User::where('email', $email)
            ->first();
    }

    private function prepareErrorResult(): array
    {
        return $this->makeAuthenticationCookie([
            'error' => 'User is unavailable. Try another social account!',
            'redirect' => '/login',
            'redirect_url' => '/#/',
        ]);
    }

    private function prepareSuccessResult(string $token): array
    {
        return [
            'token' => $token,
            'redirect_url' => URL::to('/') . '?token=' . $token
        ];
    }

    private function prepareSignupResult($userSocial): array
    {
        $url = "/register?email={$userSocial->email}&id={$userSocial->id}&name={$userSocial->name}";
        return $this->makeAuthenticationCookie([
            'redirect_url' => $url,
            'token' => ''
        ]);
    }
}