<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cancion extends Model
{
    protected $table = 'canciones'; // Especificar el nombre correcto de la tabla

    protected $fillable = ['titulo', 'artista', 'archivo', 'url_externa', 'user_id'];

    public function listas()
    {
        return $this->belongsToMany(ListaReproduccion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
