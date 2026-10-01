<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MiembroGrupo extends Model
{
    protected $table = 'miembro_grupo';

    public $timestamps = false;

    protected $fillable = ['id_grupo', 'id_usuario', 'rol_grupo', 'fecha_union'];

    protected function casts(): array
    {
        return [
            'fecha_union' => 'datetime',
        ];
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(GrupoEstudio::class, 'id_grupo', 'id_grupo');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
