<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue; // Se importa la interfaz ShouldQueue
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Project;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

// --- INICIO DE LA CORRECCIÓN 1 ---
// Se implementa la interfaz ShouldQueue para controlar el comportamiento de la cola del evento.
class GalleryImageProcessed implements ShouldBroadcast, ShouldQueue
// --- FIN DE LA CORRECCIÓN 1 ---
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Project $project;
    public array $mediaData;

    /**
     * Create a new event instance.
     */
    public function __construct(Project $project, Media $media)
    {
        $this->project = $project;
        
        $this->mediaData = [
            'id' => $media->id,
            'url' => $media->getUrl('admin-thumb'),
            'order' => $media->order_column,
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new PrivateChannel('project.' . $this->project->id);
    }

    /**
     * Define el nombre con el que se emitirá el evento.
     */
    public function broadcastAs()
    {
        return 'GalleryImageProcessed';
    }

    /**
     * --- INICIO DE LA CORRECCIÓN 2 ---
     * Define el nombre de la cola en la que se debe colocar el job de broadcasting.
     *
     * Al implementar ShouldQueue, este método le dice a Laravel que envíe
     * el job 'BroadcastEvent' a nuestra cola 'media_processing', en lugar
     * de a la cola 'default'.
     *
     * @return string
     */
    public function broadcastQueue(): string
    {
        return 'media_processing';
    }
    // --- FIN DE LA CORRECCIÓN 2 ---
}
