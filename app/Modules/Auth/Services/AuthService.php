<?php

namespace Modules\Auth\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function register(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'nombreCompleto' => $data['nombreCompleto'],
            'email' => $data['email'],
            'telefono' => $data['telefono'] ?? null,
            'rol' => $data['rol'],
            'estado' => true,
            'password' => bcrypt($data['password']),
        ]);

        return $user;
    }

    public function login(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);
        }

        return [
            'token' => $user->createToken('api-token')->plainTextToken,
            'user' => $user,
            'success' => true,
        ];
    }
}
