<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeguimientoEnvio extends Model
{
    protected $table = 'seguimiento_envios';

    protected $fillable = [
        'envio_id',
        'estado',
        'descripcion',
        'usuario_id',
    ];

    protected $appends = ['estado_legible'];

    public function envio()
    {
        return $this->belongsTo(Envio::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function getEstadoLegibleAttribute(): string
    {
        return match ($this->estado) {
            'registrado' => 'Registrado',
            'en_recojo' => 'En recojo',
            'recibido_origen' => 'Recibido en origen',
            'en_ruta' => 'En ruta',
            'recibido_destino' => 'Recibido en destino',
            'entregado' => 'Entregado',
            'cancelado' => 'Cancelado',
            default => ucfirst($this->estado),
        };
    }
}
