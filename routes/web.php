<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MusicaController;
use App\Http\Controllers\PlaylistController;


Route::get('/', [MusicaController::class, 'index'])->name('musica.index');
Route::get('/musica/search', [MusicaController::class, 'search'])->name('musica.search');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/musica', [MusicaController::class, 'store'])->name('musica.store');
    Route::post('/musica/upload', [MusicaController::class, 'upload'])->name('musica.upload');
    Route::get('/playlists', [PlaylistController::class, 'index'])->name('playlists.index');
    Route::post('/playlists', [PlaylistController::class, 'store'])->name('playlists.store');
    Route::delete('/musica/{id}', [MusicaController::class, 'destroy'])->name('musica.destroy');

    Route::post('/listas/{lista}/add-cancion', [PlaylistController::class, 'addCancion'])
        ->name('playlists.addCancion');
    Route::delete('/listas/{lista}/remove-cancion/{cancion}', [PlaylistController::class, 'removeCancion'])
        ->name('playlists.removeCancion');
    Route::delete('/musica/{id}', [MusicaController::class, 'destroy'])->name('musica.destroy');
});

require __DIR__ . '/auth.php';
