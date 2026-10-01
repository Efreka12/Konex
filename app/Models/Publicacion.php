<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Publicacion extends Model
{
    protected $table = 'publicaciones';

    protected $primaryKey = 'id_publicacion';

    public $timestamps = false;

    protected $fillable = ['id_usuario', 'tipo', 'titulo', 'contenido', 'fecha'];

    protected function casts(): array
    {
        return [
            'fecha' => 'datetime',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class, 'id_publicacion', 'id_publicacion');
    }

    public function reacciones(): HasMany
    {
        return $this->hasMany(Reaccion::class, 'id_publicacion', 'id_publicacion');
    }
}
