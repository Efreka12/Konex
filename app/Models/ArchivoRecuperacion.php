<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArchivoRecuperacion extends Model
{
    protected $table = 'archivos_recuperacion';

    protected $fillable = [
        'id_usuario',
        'secreto_hmac',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
