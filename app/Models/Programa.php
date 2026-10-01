<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Programa extends Model
{
    protected $table = 'programas';

    protected $primaryKey = 'id_programa';

    public $timestamps = false;

    protected $fillable = ['nombre', 'facultad'];

    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'id_programa', 'id_programa');
    }

    public function asignaturas(): HasMany
    {
        return $this->hasMany(Asignatura::class, 'id_programa', 'id_programa');
    }
}
