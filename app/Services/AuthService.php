<?php

namespace App\Services;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(array $data): array
    {
        $user = $this->createUser($data);
        $this->assignDefaultRole($user);
        $this->logRegistration($user);

        return $this->createTokenResponse($user);
    }

    public function login(array $credentials): array
    {
        $user = $this->getUserByEmail($credentials['email']);

        if (! $user || ! $this->checkPassword($credentials['password'], $user->password)) {
            $this->logFailedLogin($credentials['email']);
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $this->createTokenResponse($user);
    }

    private function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    private function assignDefaultRole(User $user): void
    {
        $user->assignRole(roles: UserRoleEnum::CUSTOMER->value);
    }

    private function logRegistration(User $user): void
    {
        Log::info('User registered', ['id' => $user->id]);
    }

    private function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    private function checkPassword(string $input, string $hashed): bool
    {
        return Hash::check($input, $hashed);
    }

    private function logFailedLogin(string $email): void
    {
        Log::warning('Failed login attempt', ['email' => $email]);
    }

    private function createTokenResponse(User $user): array
    {
        return [
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'token_type' => 'Bearer',
        ];
    }
}
