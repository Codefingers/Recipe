<?php
namespace Tests\Feature\Http;

use App\User;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Trait for helping tests with interacting with a JWT
 */
trait Jwt {

    /**
     * Returns a JWT for the test user
     *
     * @return string
     */
    private function getToken(): string
    {
        $user = User::where('email', Config::get('api.test_email'))->first();
        return JWTAuth::fromUser($user);
    }

    /**
     * Returns the authorisation header containing the JWT
     *
     * @return array
     */
    public function getAuthHeader(): array
    {
        return ['authorization' => 'Bearer ' . $this->getToken()];
    }
}
