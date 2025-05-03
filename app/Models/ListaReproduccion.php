<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListaReproduccion extends Model
{
    protected $table = 'listas_reproduccion'; // Especificar el nombre correcto de la tabla

    protected $fillable = ['nombre', 'user_id'];

    public function canciones()
    {
        return $this->belongsToMany(Cancion::class)
            ->withTimestamps()
            ->orderBy('cancion_lista_reproduccion.created_at', 'desc');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
