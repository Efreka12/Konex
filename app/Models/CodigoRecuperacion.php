<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CodigoRecuperacion extends Model
{
    protected $table = 'codigos_recuperacion';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'lote',
        'codigo_hmac',
        'usado_en',
        'anulado_en',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'usado_en' => 'datetime',
            'anulado_en' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function vigente(): bool
    {
        return $this->usado_en === null && $this->anulado_en === null;
    }
}
