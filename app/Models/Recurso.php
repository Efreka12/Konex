<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recurso extends Model
{
    protected $table = 'recursos';

    protected $primaryKey = 'id_recurso';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'id_asignatura',
        'id_grupo',
        'titulo',
        'descripcion',
        'tipo_archivo',
        'ruta_archivo',
        'verificado',
        'semestre',
    ];

    protected function casts(): array
    {
        return [
            'verificado' => 'boolean',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class, 'id_asignatura', 'id_asignatura');
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(GrupoEstudio::class, 'id_grupo', 'id_grupo');
    }
}
