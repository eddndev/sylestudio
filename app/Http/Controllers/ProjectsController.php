<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     * Carga la vista principal SIN las imágenes de la galería.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Cambiamos Event por Project y usamos eager loading para la media.
        $projects = Project::with('media') // ¡Crucial para el rendimiento!
                        ->where('status', 'published') // Asumiendo que tienes un campo de estado
                        ->latest('published_at')
                        ->get();

        return view('pages.projects.index', [
            'projects' => $projects // Pasamos la colección de proyectos
        ]);
    }

    /**
     * Muestra un proyecto específico.
     * Carga el proyecto con sus imágenes de la galería.
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\View\View
     */
    public function show(Project $project)
    {
        // Cargamos el proyecto para la vista inicial. Es importante para el hero.
        $project->load('media');
        return view('pages.projects.show', [
            'project' => $project
        ]);
    }
    /**
     * Obtiene las imágenes de la galería para el lazy loading.
     *
     * @param \App\Models\Project $project
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGalleryImages(Project $project)
    {
        // ✅ CAMBIO: Quitamos la paginación y obtenemos TODOS los medios de la galería.
        $images = $project->getMedia('gallery');

        // ✅ CAMBIO: Transformamos la colección directamente.
        $transformedImages = $images->map(function ($media) {
            $widthMap = [
                'sm' => 400, 'md' => 800, 'lg' => 1200, 'xl' => 1920,
            ];
            
            $buildSrcset = function ($format) use ($media, $widthMap) {
                return collect($widthMap)
                    ->map(fn($width, $size) => $media->getUrl('gallery-' . $size . '-' . $format) . ' ' . $width . 'w')
                    ->implode(', ');
            };

            return [
                'id' => $media->id,
                'alt' => $media->name,
                'srcset_avif' => $buildSrcset('avif'),
                'srcset_webp' => $buildSrcset('webp'),
                'src_fallback' => $media->getUrl('gallery-md-webp'),
                'src_full' => $media->getUrl('gallery-xl-webp'),
                'width' => $media->getCustomProperty('width'),
                'height' => $media->getCustomProperty('height'),
            ];
        });

        return response()->json($transformedImages);
    }
}