<?php

namespace App\Http\Controllers;

use App\Models\ListaReproduccion;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function index()
    {
        $listas = auth()->user()->listas;
        return view('playlists', compact('listas'));
    }
   
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        auth()->user()->listas()->create([
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('musica.index')->with('success', 'Lista creada correctamente.');
    }
    public function addCancion(Request $request, $listaId)
    {
        $request->validate([
            'cancion_id' => 'required|exists:canciones,id'
        ]);

        $lista = ListaReproduccion::findOrFail($listaId);
        $lista->canciones()->attach($request->cancion_id);

        return back()->with('success', 'Canción agregada a la lista!');
    }

    public function removeCancion($listaId, $cancionId)
    {
        $lista = ListaReproduccion::findOrFail($listaId);
        $lista->canciones()->detach($cancionId);

        return back()->with('success', 'Canción removida de la lista!');
    }
}
