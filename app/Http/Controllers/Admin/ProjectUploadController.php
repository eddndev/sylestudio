<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProjectUploadController extends Controller
{
    /**
     * Almacena un archivo subido de forma temporal.
     */
    public function store(Request $request)
    {
        Log::info('ProjectUploadController@store: Petición de subida recibida.');

        if (!$request->hasFile('gallery_upload')) {
            Log::warning('ProjectUploadController@store: No se encontró ningún archivo con el nombre "gallery_upload".');
            return response('No file uploaded.', 400);
        }

        $file = $request->file('gallery_upload');

        try {
            $request->validate([
                'gallery_upload' => 'required|mimes:jpeg,png,jpg,gif,svg,webp,avif|max:30720', // 30MB max
            ]);

            $uuid = Str::uuid()->toString();
            $extension = $file->getClientOriginalExtension();
            $newFilename = "{$uuid}.{$extension}";
            
            Log::info('ProjectUploadController@store: Generado nuevo nombre de archivo con extensión.', ['filename' => $newFilename]);

            // Se usa el Storage Facade con el nuevo nombre de archivo.
            Storage::disk('local')->putFileAs('tmp_uploads', $file, $newFilename);
            // --- FIN DE LA CORRECCIÓN FINAL ---
            
            Log::info('ProjectUploadController@store: Archivo guardado exitosamente.', ['filename' => $newFilename]);

            // Se devuelve el nuevo nombre de archivo (UUID con extensión) al frontend.
            return response($newFilename, 200)->header('Content-Type', 'text/plain');

        } catch (ValidationException $e) {
            Log::error('ProjectUploadController@store: Falló la validación del archivo.', [
                'original_name' => $file->getClientOriginalName(),
                'detected_mime_type' => $file->getMimeType(),
                'size_in_bytes' => $file->getSize(),
                'validation_errors' => $e->errors()
            ]);
            return response()->json(['errors' => $e->errors()], 422);

        } catch (\Exception $e) {
            Log::error('ProjectUploadController@store: Error crítico al guardar el archivo.', [
                'exception_message' => $e->getMessage()
            ]);
            return response('Error al guardar el archivo en el servidor.', 500);
        }
    }

    /**
     * Elimina un archivo temporal.
     */
    public function destroy(Request $request)
    {
        // El serverId que envía FilePond ahora es el nombre de archivo completo (uuid.ext)
        $filename = $request->getContent();
        if ($filename) {
            $filePath = 'tmp_uploads/' . $filename;
            if (Storage::disk('local')->exists($filePath)) {
                Storage::disk('local')->delete($filePath);
                return response()->noContent();
            }
        }
        return response('File not found.', 404);
    }

    /**
     * Sirve un archivo temporal para la previsualización de FilePond.
     */
    public function load($filename)
    {
        $path = 'tmp_uploads/' . $filename;

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'Archivo temporal no encontrado.');
        }

        return Storage::disk('local')->response($path);
    }
}
