<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnvioItem extends Model
{
    protected $table = 'envio_items';

    protected $fillable = [
        'envio_id',
        'cantidad',
        'unidad_medida',
        'codigo',
        'descripcion',
    ];

    public function envio(): BelongsTo
    {
        return $this->belongsTo(Envio::class);
    }
}
