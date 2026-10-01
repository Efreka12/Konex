<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GrupoEstudio extends Model
{
    protected $table = 'grupos_estudio';

    protected $primaryKey = 'id_grupo';

    public $timestamps = false;

    protected $fillable = [
        'id_asignatura',
        'id_creador',
        'nombre',
        'descripcion',
        'codigo_union',
        'cupo',
        'horario',
    ];

    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class, 'id_asignatura', 'id_asignatura');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_creador', 'id_usuario');
    }

    public function miembros(): BelongsToMany
    {
        return $this->belongsToMany(
            Usuario::class,
            'miembro_grupo',
            'id_grupo',
            'id_usuario'
        )->withPivot('rol_grupo', 'fecha_union');
    }

    public function mensajes(): HasMany
    {
        return $this->hasMany(Mensaje::class, 'id_grupo', 'id_grupo');
    }

    public function recursos(): HasMany
    {
        return $this->hasMany(Recurso::class, 'id_grupo', 'id_grupo');
    }
}
