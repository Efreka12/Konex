<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HoraSocial extends Model
{
    protected $table = 'horas_sociales';

    protected $primaryKey = 'id_hora';

    public $timestamps = false;

    protected $fillable = [
        'id_estudiante',
        'id_directivo',
        'actividad',
        'horas',
        'estado',
        'evidencia',
    ];

    protected function casts(): array
    {
        return [
            'horas' => 'decimal:1',
        ];
    }

    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_estudiante', 'id_usuario');
    }

    public function directivo(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_directivo', 'id_usuario');
    }
}
