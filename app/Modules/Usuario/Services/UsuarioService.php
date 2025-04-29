<?php

namespace Modules\Usuario\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;

class UsuarioService
{
    public function all(array $filters = []): Collection
    {
        $query = User::with([
            'agencia',
        ]);

        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                if (!is_null($value)) {
                    $query->where($key, $value);
                }
            }
        }

        return $query->get();
    }


    public function find(int $id)
    {
        return User::with('agencia')->findOrFail($id);
    }

    public function create(array $data): User
    {

        if (empty($data['password'])) {
            $plainPassword = Str::random(10);
        } else {
            $plainPassword = $data['password'];
        }

        $data['password'] = Hash::make($plainPassword);

        $user = User::create($data);

        $user->plain_password = $plainPassword;

        return $user;
    }


    public function update(User $usuario, array $data): User
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $usuario->update($data);
        return $usuario;
    }

    public function delete(User $usuario): bool
    {
        return $usuario->delete();
    }
}
