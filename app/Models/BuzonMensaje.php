<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuzonMensaje extends Model
{
    protected $table = 'buzon_mensajes';

    public $timestamps = false;

    protected $fillable = [
        'remitente',
        'correo',
        'asunto',
        'codigo',
        'cuerpo',
        'leido',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'leido' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function codigoFormateado(): string
    {
        return substr($this->codigo, 0, 4).'-'.substr($this->codigo, 4, 4);
    }
}
