// resources/js/instagram-form.js

import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';

// Importa los estilos de FilePond.
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';

/**
 * Componente de Alpine.js para el formulario de InstagramMedia.
 * Gestiona el estado del formulario y la subida asíncrona de la imagen.
 * @param {object} config - Objeto de configuración pasado desde la vista Blade.
 */
export default function instagramForm(config) {
    return {
        // --- ESTADO DEL COMPONENTE ---
        form: {
            ...config.post,
            // Almacenamos la URL de la imagen existente para la previsualización inicial.
            existingImageUrl: config.existingImage ? config.existingImage.url : null,
        },
        filepond: null,
        isEditMode: config.isEdit,
        // Nuevo estado para controlar si el usuario quiere reemplazar la imagen existente.
        isReplacing: false, 
        
        /**
         * Función de inicialización del componente Alpine.
         */
        init() {
            // Si estamos en el formulario de CREACIÓN, no hay imagen existente,
            // por lo que inicializamos FilePond inmediatamente.
            if (!this.isEditMode) {
                this.$nextTick(() => this.initFilePond());
            }
            // Si estamos EDITANDO, esperamos a que el usuario haga clic en "Reemplazar".
        },

        /**
         * Se llama al hacer clic en el botón "Reemplazar Imagen".
         * Cambia el estado para ocultar la imagen actual y mostrar FilePond.
         */
        startReplacing() {
            this.isReplacing = true;
            // Usamos $nextTick para asegurar que el <input x-ref="filepond"> sea visible
            // en el DOM antes de que intentemos inicializar FilePond en él.
            this.$nextTick(() => this.initFilePond());
        },

        /**
         * Inicializa y configura la instancia de FilePond.
         */
        initFilePond() {
            FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFileValidateType);

            // En este flujo, FilePond siempre se inicializa vacío, ya que la imagen
            // existente se muestra fuera de él.
            const initialFiles = [];

            this.filepond = FilePond.create(this.$refs.filepond, {
                // Configuración para una sola imagen
                allowMultiple: false,
                acceptedFileTypes: ['image/*'],
                name: 'image_upload', // El nombre del campo que espera el UploadController

                files: initialFiles,

                // Configuración del servidor para la subida asíncrona
                server: {
                    process: {
                        url: config.uploadUrl,
                        headers: { 'X-CSRF-TOKEN': window.csrf }
                    },
                    revert: (uniqueFileId, load, error) => {
                        // Lógica para eliminar un archivo temporal del servidor
                        fetch(config.revertUrl, {
                            method: 'DELETE',
                            headers: { 'Content-Type': 'text/plain', 'X-CSRF-TOKEN': window.csrf },
                            body: uniqueFileId
                        }).then(res => res.ok ? load() : error('Error al revertir'));
                    },
                    // No necesitamos 'load' porque la previsualización se maneja con 'poster'
                    load: null 
                },
                
                // Evento que se dispara al añadir un nuevo archivo
                onaddfile: (error, file) => {
                    // Si se añade un archivo nuevo y ya existía uno, se elimina el anterior
                    // para asegurar que siempre haya una sola imagen.
                    if (this.filepond.getFiles().length > 1) {
                        this.filepond.removeFile(this.filepond.getFiles()[0].id);
                    }
                }
            });
        }
    };
}
