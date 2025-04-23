<?php

namespace Modules\Usuario\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsuarioService
{
    public function all(array $filtros)
    {
        $query = User::query()->with('agencia');

        $query->when(isset($filtros['agencia_id']), function ($query) use ($filtros) {
            $query->where('agencia_id', $filtros['agencia_id']);
        });

        $query->when(isset($filtros['nombre']), function ($query) use ($filtros) {
            $query->where('nombre', 'like', '%' . $filtros['nombre'] . '%');
        });

        $query->when(isset($filtros['email']), function ($query) use ($filtros) {
            $query->where('email', 'like', '%' . $filtros['email'] . '%');
        });


        $query->when(isset($filtros['rol']), function ($query) use ($filtros) {
            $query->where('rol', $filtros['rol']);
        });

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
