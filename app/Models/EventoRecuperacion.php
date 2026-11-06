<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventoRecuperacion extends Model
{
    protected $table = 'eventos_recuperacion';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'evento',
        'ip',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }
}
