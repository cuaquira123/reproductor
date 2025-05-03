<?php

namespace App\Console\Commands;

use App\Models\Cancion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportSongs extends Command
{
    protected $signature = 'songs:import';
    protected $description = 'Import songs from public/storage/musica to the canciones table';

    public function handle()
    {
        $this->info('Iniciando la importación de canciones...');

        // Obtener todos los archivos de la carpeta public/storage/musica
        $files = Storage::disk('public')->files('musica');

        if (empty($files)) {
            $this->error('No se encontraron canciones en public/storage/musica.');
            return;
        }

        $count = 0;
        foreach ($files as $file) {
            // Verificar que el archivo sea un MP3
            if (pathinfo($file, PATHINFO_EXTENSION) !== 'mp3') {
                $this->warn("El archivo {$file} no es un MP3, se omitirá.");
                continue;
            }

            // Extraer el título y el artista del nombre del archivo
            $filename = pathinfo($file, PATHINFO_FILENAME);
            // Suponemos que el nombre del archivo tiene el formato "Título - Artista"
            $parts = explode(' - ', $filename);
            $titulo = $parts[0] ?? $filename;
            $artista = $parts[1] ?? 'Desconocido';

            // Verificar si la canción ya existe en la base de datos
            if (Cancion::where('archivo', $file)->exists()) {
                $this->warn("La canción {$file} ya está registrada, se omitirá.");
                continue;
            }

            // Crear el registro en la tabla canciones
            Cancion::create([
                'titulo' => $titulo,
                'artista' => $artista,
                'archivo' => $file,
                'lista_id' => null, // Si deseas asignar una lista, puedes modificar esto
            ]);

            $count++;
            $this->info("Canción importada: {$titulo} - {$artista}");
        }

        $this->info("Importación completada. Se importaron {$count} canciones.");
    }
}