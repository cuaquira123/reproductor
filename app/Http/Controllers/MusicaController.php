<?php

namespace App\Http\Controllers;

use App\Models\Cancion;
use App\Models\ListaReproduccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MusicaController extends Controller
{
    public function index()
    {
        // todas las canciones con la relacion de usuario
        $canciones = Cancion::with('user')->get();
        return view('music_player', compact('canciones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'url_externa' => 'required|url',
        ]);

        Cancion::create([
            'titulo' => $request->titulo,
            'artista' => 'Desconocido',
            'url_externa' => $request->url_externa,
        ]);

        return redirect()->route('musica.index')->with('success', 'Canción añadida correctamente.');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'artista' => 'required|string|max:255',
            'archivo' => 'required|file|mimes:mp3|max:10240', // Max 10MB
            'lista_id' => 'nullable|exists:listas_reproduccion,id',
        ]);

        $path = $request->file('archivo')->store('musica', 'public');

        $cancion =  Cancion::create([
            'titulo' => $request->titulo,
            'artista' => $request->artista,
            'archivo' => $path,
            'user_id' => auth()->id(),
        ]);
        if ($request->has('lista_id')) {
            $lista = ListaReproduccion::find($request->lista_id);
            if ($lista) {
                $lista->canciones()->attach($cancion->id);
            }
        }
        return redirect()->route('musica.index')->with('success', 'Canción subida correctamente.');
    }

    public function search(Request $request)
    {
        $search = $request->query('search');
        $canciones = Cancion::where('titulo', 'like', "%{$search}%")->get();
        return view('music_player', compact('canciones'));
    }

    public function destroy($id)
{
    $cancion = Cancion::findOrFail($id);
    if ($cancion->user_id === auth()->id()) {
        $cancion->delete();
        return redirect()->route('musica.index')->with('success', 'Canción eliminada con éxito');
    }
    return redirect()->route('musica.index')->with('error', 'No tienes permiso para eliminar esta canción');
}
}
