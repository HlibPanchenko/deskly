<?php

namespace App\Services;

use App\DTOs\Auth\LoginData;
use App\DTOs\Auth\RegisterData;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(RegisterData $data): array
    {
        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => $data->password,
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    public function login(LoginData $data): array
    {
        if (! Auth::attempt(['email' => $data->email, 'password' => $data->password])) {
            throw ValidationException::withMessages([
                'email' => ['Неверный email или пароль.'],
            ]);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }
}
