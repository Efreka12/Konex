<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reaccion extends Model
{
    protected $table = 'reacciones';

    protected $primaryKey = 'id_reaccion';

    public $timestamps = false;

    protected $fillable = ['id_publicacion', 'id_usuario', 'tipo'];

    public function publicacion(): BelongsTo
    {
        return $this->belongsTo(Publicacion::class, 'id_publicacion', 'id_publicacion');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
