<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Jobs\ProcessProjectImage;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Muestra una lista de todos los proyectos.
     */
    public function index()
    {
        $projects = Project::withCount('media')->latest()->paginate(15);
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Muestra el formulario para crear un nuevo proyecto.
     */
    public function create()
    {
        $project = new Project();
        return view('admin.projects.create-edit', compact('project'));
    }

    /**
     * Almacena un nuevo proyecto en la base de datos.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:projects,slug',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'gallery' => 'nullable|array',
            'gallery.*' => 'string',
        ]);

        try {
            $project = Project::create($validatedData);
            $redirectResponse = redirect()->route('admin.projects.edit', $project);

            if ($request->has('gallery') && !empty($request->gallery)) {
                $galleryFiles = $request->gallery;
                foreach ($galleryFiles as $filename) {
                    ProcessProjectImage::dispatch($project->id, $filename)->onQueue('media_processing');
                }
                
                // --- INICIO DE LA CORRECCIÓN ---
                // Se pasa el número exacto de imágenes a procesar.
                $redirectResponse->with('success', 'Proyecto creado. ' . count($galleryFiles) . ' imágenes se están procesando.')
                                 ->with('processing_count', count($galleryFiles));
                // --- FIN DE LA CORRECCIÓN ---
            } else {
                $redirectResponse->with('success', 'Proyecto creado con éxito.');
            }

            return $redirectResponse;

        } catch (\Exception $e) {
            Log::error('ProjectController@store: Ocurrió una excepción.', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Ocurrió un error inesperado.');
        }
    }

    /**
     * Muestra el formulario para editar un proyecto existente.
     */
    public function edit(Project $project)
    {
        $project->load('media');
        return view('admin.projects.create-edit', compact('project'));
    }

    /**
     * Actualiza un proyecto existente en la base de datos.
     */
    public function update(Request $request, Project $project)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('projects')->ignore($project->id)],
            'description' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'published_at' => 'nullable|date',
            'gallery' => 'nullable|array',
            'gallery.*' => 'string',
            'deleted_media' => 'nullable|array',
            'deleted_media.*' => 'integer',
        ]);

        try {
            DB::transaction(function () use ($validatedData, $request, $project) {
                $project->update($validatedData);
                if ($request->has('deleted_media')) {
                    $project->media()->whereIn('id', $request->deleted_media)->delete();
                }
            });

            $redirectResponse = redirect()->route('admin.projects.edit', $project);
            $successMessage = 'Proyecto actualizado con éxito.';

            if ($request->has('gallery') && !empty($request->gallery)) {
                $galleryFiles = $request->gallery;
                foreach ($galleryFiles as $filename) {
                    ProcessProjectImage::dispatch($project->id, $filename)->onQueue('media_processing');
                }
                // --- INICIO DE LA CORRECCIÓN ---
                $successMessage .= ' ' . count($galleryFiles) . ' nuevas imágenes se están procesando.';
                $redirectResponse->with('processing_count', count($galleryFiles));
                // --- FIN DE LA CORRECCIÓN ---
            }

            return $redirectResponse->with('success', $successMessage);

        } catch (\Exception $e) {
            Log::error('ProjectController@update: Ocurrió una excepción.', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Ocurrió un error inesperado.');
        }
    }

    /**
     * Elimina un proyecto de la base de datos.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Proyecto eliminado con éxito.');
    }

    /**
     * Reordena la galería de un proyecto.
     */
    public function reorderGallery(Request $request, Project $project)
    {
        $request->validate(['order' => 'required|array']);
        Media::setNewOrder($request->input('order'));
        return response()->json(['status' => 'success']);
    }
}
