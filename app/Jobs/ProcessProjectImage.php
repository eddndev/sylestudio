<?php

namespace App\Jobs;

use App\Events\GalleryImageProcessed; // Se importa el nuevo evento
use Illuminate\Console\Concerns\InteractsWithIO;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessProjectImage implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected int $projectId;
    protected string $filename;
    
    public function __construct(int $projectId, string $filename)
    {
        $this->projectId = $projectId;
        $this->filename = $filename;
    }

    public function handle(): void
    {
        Log::info("ProcessProjectImage@handle: Iniciando Job.", [
            'projectId' => $this->projectId,
            'filename' => $this->filename,
        ]);

        $project = \App\Models\Project::find($this->projectId);
        if (!$project) {
            Log::error("ProcessProjectImage@handle: Proyecto no encontrado.", ['projectId' => $this->projectId]);
            $this->fail("El proyecto con ID {$this->projectId} no fue encontrado.");
            return;
        }

        $temporaryFilePath = 'tmp_uploads/' . $this->filename;

        if (Storage::disk('local')->exists($temporaryFilePath)) {
            $absolutePath = Storage::disk('local')->path($temporaryFilePath);

            // Spatie añade el medio y devuelve el modelo Media recién creado.
            $media = $project->addMedia($absolutePath)->toMediaCollection('gallery');

            // --- INICIO DE LA NUEVA LÓGICA ---
            // Se despacha el evento de broadcasting con el proyecto y el nuevo medio.
            GalleryImageProcessed::dispatch($project, $media);
            // --- FIN DE LA NUEVA LÓGICA ---

            Storage::disk('local')->delete($temporaryFilePath);

            Log::info("ProcessProjectImage@handle: Job completado y evento despachado.", [
                'project_id' => $this->projectId,
                'filename' => $this->filename
            ]);
        } else {
            Log::warning("ProcessProjectImage@handle: El archivo temporal no fue encontrado.", [
                'project_id' => $this->projectId,
                'filename' => $this->filename,
                'path' => $temporaryFilePath
            ]);
        }
    }
}
