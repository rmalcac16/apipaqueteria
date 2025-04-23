<?php

namespace Modules\Agencia\Services;

use App\Events\AgenciasUpdated;
use App\Models\Agencia;

class AgenciaService
{
    public function all()
    {
        return Agencia::all();
    }

    public function find(int $id)
    {
        return Agencia::findOrFail($id);
    }

    public function create(array $data)
    {
        $agencia = Agencia::create($data);
        return $agencia;
    }

    public function update(Agencia $agencia, array $data)
    {
        $agencia->update($data);
        return $agencia;
    }

    public function delete(Agencia $agencia)
    {
        $agencia->delete();
        return true;
    }
}
