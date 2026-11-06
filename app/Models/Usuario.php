<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens;

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
        'activo',
        'created_at',
    ];

    protected $hidden = ['contrasena'];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'activo' => 'boolean',
        ];
    }

    public function getAuthPassword(): string
    {
        return $this->contrasena;
    }

    public function iniciales(): string
    {
        $partes = preg_split('/\s+/', trim((string) $this->nombre_completo)) ?: [];
        $primera = mb_substr($partes[0] ?? 'K', 0, 1);
        $segunda = mb_substr($partes[1] ?? '', 0, 1);

        return mb_strtoupper($primera.$segunda);
    }

    public function tieneRol(string ...$roles): bool
    {
        $nombre = mb_strtolower((string) $this->rol?->nombre);

        return collect($roles)->contains(
            fn (string $rol) => $nombre === mb_strtolower($rol)
        );
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

    public function recursos(): HasMany
    {
        return $this->hasMany(Recurso::class, 'id_usuario', 'id_usuario');
    }

    public function toAuthArray(): array
    {
        $this->loadMissing(['rol', 'programa']);

        return [
            'id' => $this->id_usuario,
            'name' => $this->nombre_completo,
            'email' => $this->correo_institucional,
            'id_usuario' => $this->id_usuario,
            'nombre_completo' => $this->nombre_completo,
            'correo_institucional' => $this->correo_institucional,
            'rol' => $this->rol?->nombre,
            'programa' => $this->programa?->nombre,
            'bio' => $this->bio,
        ];
    }
}
