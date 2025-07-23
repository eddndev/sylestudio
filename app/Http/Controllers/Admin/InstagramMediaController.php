<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstagramMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class InstagramMediaController extends Controller
{
    /**
     * Muestra una lista de todas las publicaciones de Instagram.
     */
    public function index()
    {
        // Obtenemos todas las publicaciones, ordenadas como se especifica.
        $posts = InstagramMedia::orderBy('order_column', 'asc')->get();
        return view('admin.instagram.index', compact('posts'));
    }
    /**
     * Muestra el formulario para crear una nueva publicación.
     */
    public function create()
    {
        $post = new InstagramMedia();
        return view('admin.instagram.create-edit', compact('post'));
    }

    /**
     * Almacena una nueva publicación en la base de datos.
     */
    public function store(Request $request)
    {

        // ✅ CORRECCIÓN: La validación ahora espera el ID del archivo temporal (un string),
        // que es lo que FilePond envía en el campo 'image_upload'.
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'is_visible' => 'sometimes|boolean',
            'image_upload' => 'required|string',
        ]);
        Log::info('[InstagramMedia] Datos del formulario validados.', $validatedData);

        $post = InstagramMedia::create([
            'title' => $validatedData['title'],
            'url' => $validatedData['url'],
            'is_visible' => $request->has('is_visible'),
        ]);
        Log::info('[InstagramMedia] Registro creado en la base de datos.', ['id' => $post->id]);

        $tempFilename = $validatedData['image_upload'];
        $tempPath = 'tmp_uploads/instagram/' . $tempFilename;
        Log::info('[InstagramMedia] Buscando archivo temporal.', ['path' => $tempPath]);

        if (Storage::disk('local')->exists($tempPath)) {
            Log::info('[InstagramMedia] Archivo temporal encontrado. Añadiendo a la colección de medios.');
            // Se obtiene la ruta absoluta y se añade a la colección.
            // Spatie se encargará de mover el archivo a su ubicación final.
            $post->addMedia(Storage::disk('local')->path($tempPath))
                 ->toMediaCollection();
            Log::info('[InstagramMedia] Archivo añadido a la colección de medios exitosamente.');
        } else {
            Log::error('[InstagramMedia] El archivo temporal no fue encontrado. La publicación se creó sin imagen.', ['path' => $tempPath]);
            // Opcional: podrías eliminar el post si la imagen es absolutamente requerida.
            // $post->delete();
            // return back()->with('error', 'La imagen subida no pudo ser encontrada. Inténtalo de nuevo.');
        }

        return redirect()->route('admin.instagram.index')->with('success', 'Publicación creada con éxito.');
    }

    /**
     * Muestra el formulario para editar una publicación existente.
     */
    public function edit(InstagramMedia $instagram)
    {
        // Renombramos la variable a $post para consistencia en la vista.
        $post = $instagram;
        return view('admin.instagram.create-edit', compact('post'));
    }

    /**
     * Actualiza una publicación existente en la base de datos.
     */
    public function update(Request $request, InstagramMedia $instagram)
    {
        Log::info('[InstagramMedia] Iniciando proceso de actualización.', ['id' => $instagram->id]);
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'is_visible' => 'sometimes|boolean',
            'image_upload' => 'nullable|string', // Es nulo si no se subió una nueva imagen
        ]);
        Log::info('[InstagramMedia] Datos del formulario validados para actualización.', $validatedData);

        $instagram->update([
            'title' => $validatedData['title'],
            'url' => $validatedData['url'],
            'is_visible' => $request->has('is_visible'),
        ]);
        Log::info('[InstagramMedia] Registro actualizado en la base de datos.');

        // Si se subió una nueva imagen (FilePond envía el serverId en 'image_upload').
        if ($request->filled('image_upload')) {
            $tempFilename = $validatedData['image_upload'];
            $tempPath = 'tmp_uploads/instagram/' . $tempFilename;
            Log::info('[InstagramMedia] Se ha subido una nueva imagen. Buscando archivo temporal.', ['path' => $tempPath]);

            if (Storage::disk('local')->exists($tempPath)) {
                Log::info('[InstagramMedia] Archivo temporal encontrado. Reemplazando imagen existente.');
                $instagram->clearMediaCollection(); // Elimina la imagen anterior
                $instagram->addMedia(Storage::disk('local')->path($tempPath))
                          ->toMediaCollection();
                Log::info('[InstagramMedia] Imagen reemplazada exitosamente.');
            } else {
                Log::error('[InstagramMedia] El nuevo archivo temporal no fue encontrado. No se actualizó la imagen.', ['path' => $tempPath]);
            }
        } else {
            Log::info('[InstagramMedia] No se subió una nueva imagen. Se mantienen los datos del formulario.');
        }

        return redirect()->route('admin.instagram.index')->with('success', 'Publicación actualizada con éxito.');
    }

    /**
     * Elimina una publicación de la base de datos.
     */
    public function destroy(InstagramMedia $instagram)
    {
        // Spatie Media Library se encargará de eliminar los archivos asociados.
        $instagram->delete();
        return redirect()->route('admin.instagram.index')->with('success', 'Publicación eliminada con éxito.');
    }
}
