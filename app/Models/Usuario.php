<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Usuario extends Model
{
    protected $table = 'usuarios';

    protected $primaryKey = 'id_usuario';

    public $timestamps = false;

    protected $fillable = [
        'id_rol',
        'id_programa',
        'nombre_completo',
        'correo_institucional',
        'contrasena',
        'foto_perfil',
        'semestre',
        'bio',
        'created_at',
    ];

    protected $hidden = ['contrasena'];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    public function programa(): BelongsTo
    {
        return $this->belongsTo(Programa::class, 'id_programa', 'id_programa');
    }

    public function asignaturas(): BelongsToMany
    {
        return $this->belongsToMany(
            Asignatura::class,
            'usuario_asignatura',
            'id_usuario',
            'id_asignatura'
        )->withPivot('tipo');
    }

    public function gruposCreados(): HasMany
    {
        return $this->hasMany(GrupoEstudio::class, 'id_creador', 'id_usuario');
    }

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(
            GrupoEstudio::class,
            'miembro_grupo',
            'id_usuario',
            'id_grupo'
        )->withPivot('rol_grupo', 'fecha_union');
    }

    public function publicaciones(): HasMany
    {
        return $this->hasMany(Publicacion::class, 'id_usuario', 'id_usuario');
    }
}
