<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Contracts\AuthRepositoryInterface;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Login and return an access token.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        /** Validations 
         * NOTE : If we have large sets for request validation then we will prefer to create Validation Request file
        */
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email|exists:users,email',
            'password'  => 'required|string|min:8',
        ], 
        [
            'email.required'    => 'The email address is required.',
            'email.email'       => 'Please provide a valid email address.',
            'email.exists'      => 'This email does not exist in our records.',
            'password.required' => 'The Password is required.',
            'password.min'      => 'Password must be at least 6 characters long.',
        ]);

        if ($validator->fails()) {
            return error(__('messages.validation_failed'), $validator->errors(), 'validation');
        }

        $credentials = $request->only(['email' , 'password']);

        $response = $this->authRepository->login($credentials);

        if (isset($response['error'])) {
            return error($response['error'], [], 'loginCase');
        }
        $user = $response['user'] ?? null;
        $token = $response['token'] ?? null;

        //Return success response
        return ok($response['message'], compact('user', 'token'));
    }

    /**
     * Logout and revoke tokens.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        // Revoke the current user token
        $user = Auth::user();

        // Revoke all of the user's previous tokens
        $user->tokens->each(function ($token) {
            $token->delete();
        });

        //Return success response
        return ok(__('messages.success', ['name' => 'Logged out']));
    }
}
