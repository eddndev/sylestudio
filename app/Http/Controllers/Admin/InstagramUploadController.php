<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class InstagramUploadController extends Controller
{
    /**
     * Almacena un archivo subido de forma temporal.
     */
    public function store(Request $request)
    {
        Log::info('[InstagramUpload] Petición de subida recibida.');
        try {
            $request->validate([
                'image_upload' => 'required|image|mimes:jpeg,png,jpg,webp,avif|max:20480', // 20MB
            ]);

            $file = $request->file('image_upload');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            $path = $file->storeAs('tmp_uploads/instagram', $filename, 'local');
            Log::info('[InstagramUpload] Archivo guardado temporalmente.', ['path' => $path]);

            return response($filename, 200)->header('Content-Type', 'text/plain');

        } catch (ValidationException $e) {
            Log::error('[InstagramUpload] Falló la validación.', ['errors' => $e->errors()]);
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('[InstagramUpload] Error crítico.', ['message' => $e->getMessage()]);
            return response('Error al guardar el archivo en el servidor.', 500);
        }
    }

    /**
     * Elimina un archivo temporal.
     */
    public function destroy(Request $request)
    {
        $filename = $request->getContent();
        Log::info('[InstagramUpload] Petición para eliminar archivo temporal recibida.', ['filename' => $filename]);
        $filePath = 'tmp_uploads/instagram/' . $filename;

        if ($filename && Storage::disk('local')->exists($filePath)) {
            Storage::disk('local')->delete($filePath);
            Log::info('[InstagramUpload] Archivo temporal eliminado exitosamente.', ['path' => $filePath]);
            return response()->noContent();
        }

        Log::warning('[InstagramUpload] Se intentó eliminar un archivo temporal no encontrado.', ['filename' => $filename]);
        return response('File not found.', 404);
    }
}
