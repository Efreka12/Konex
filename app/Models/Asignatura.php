<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asignatura extends Model
{
    protected $table = 'asignaturas';

    protected $primaryKey = 'id_asignatura';

    public $timestamps = false;

    protected $fillable = ['id_programa', 'codigo', 'nombre'];

    public function programa(): BelongsTo
    {
        return $this->belongsTo(Programa::class, 'id_programa', 'id_programa');
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(
            Usuario::class,
            'usuario_asignatura',
            'id_asignatura',
            'id_usuario'
        )->withPivot('tipo');
    }

    public function grupos(): HasMany
    {
        return $this->hasMany(GrupoEstudio::class, 'id_asignatura', 'id_asignatura');
    }

    public function recursos(): HasMany
    {
        return $this->hasMany(Recurso::class, 'id_asignatura', 'id_asignatura');
    }
}
