<?php


namespace App\Services;


interface SocialiteServiceInterface
{
    public function getRedirectUrlByProvider($provider): array;

    public function loginWithSocialite($provider): array;
}