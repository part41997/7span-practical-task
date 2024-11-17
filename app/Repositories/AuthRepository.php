<?php

// app/Repositories/AuthRepository.php
namespace App\Repositories;

use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class AuthRepository implements AuthRepositoryInterface
{
    /**Login method implemation */
    public function login(array $credentials)
    {
        if (Auth::attempt($credentials)) {
            $user = Auth::user()->load('role', 'hobbies');

            $userToken = clone $user;
            // Revoke all of the user's previous tokens
            $userToken->tokens->each(function ($token) {
                $token->delete();
            });

            $token = $user->createToken('API Token')->plainTextToken;

            return [
                'message'   => __('messages.success', ['name' => 'Login']),
                'user'      => $user,
                'token'     => $token
            ];
        }

        return [
            'error' => __('messages.invalid_credentials'),
        ];
    }
}
