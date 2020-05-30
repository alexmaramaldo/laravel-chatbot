<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountService
{
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    public function register(array $parameters)
    {
        $decrypted_password = $parameters['password'];
        $parameters['password'] = bcrypt($parameters['password']);

        $oldUser = $this->userRepository->findByEmail($parameters["email"]);

        if ($oldUser) {
            throw new Exception("Email already registered", 400);
        }

        $user = $this->userRepository->create($parameters);

        $credentials = ["email" => $parameters['email'], "password" => $decrypted_password];
        if (!$token = auth()->attempt($credentials)) {
            return false;
        }

        $data_return = [
            'name' => Auth::user()->name,
            'token' => $token
        ];

        return $data_return;
    }

    public function login(string $email, string $password)
    {
        $credentials = ["email" => $email, "password" => $password];
        if (!$token = auth()->attempt($credentials)) {
            return false;
        }

        $data_return = [
            'name' => Auth::user()->name,
            'token' => $token
        ];

        return $data_return;
    }

    public function isLogged()
    {
        try {
            $user = Auth::user();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return object
     */
    public function me(): object
    {
        return auth()->user();
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return bool
     */
    public function logout(): bool
    {
        try {
            Auth::logout();
        } catch (Exception $e) {
            //
        }

        return true;
    }

    /**
     * Refresh a token.
     *
     * @return array
     */
    public function refresh(): array
    {
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return array
     */
    protected function respondWithToken($token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ];
    }
}
