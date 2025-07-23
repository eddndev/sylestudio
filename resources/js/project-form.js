// Importa las dependencias necesarias. SortableJS ha sido eliminado.
import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';

// Importa los estilos de FilePond.
import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';

export default function projectForm(config) {
    return {
        form: config.project,
        galleryItems: [],
        filepond: null,
        _newItemCounter: 0,
        processingCount: config.processingCount || 0,

        get isProcessing() {
            return this.processingCount > 0;
        },

        init() {
            this.galleryItems = config.existingImages.map(img => ({ ...img, source: 'existing', key: img.id }));
            
            this.$nextTick(() => {
                this.initFilePond();
            });
            
            if (config.isEdit && config.projectId) {
                if (window.Echo) {
                    window.Echo.private('project.' + config.projectId)
                        .listen('.GalleryImageProcessed', (e) => {    
                            this.$dispatch('new-image-processed', e.mediaData);
                        });
                } else {
                    console.warn('[DEBUG] Laravel Echo (window.Echo) no está disponible.');
                }
            }
            
            this.$watch('form.title', (newTitle) => {
                if (!config.isEdit || !this.form.slug) {
                    this.form.slug = window.slugify(newTitle);
                }
            });
        },

        handleNewImage(mediaData) {
            if(this.processingCount > 0) {
                this.processingCount--;
            }
            
            if (mediaData && !this.galleryItems.some(item => item.id === mediaData.id)) {
                const newItem = { ...mediaData, source: 'existing', key: mediaData.id };
                
                this.galleryItems = [...this.galleryItems, newItem];
            }
        },
        // --- FIN DE LA CORRECCIÓN ---

        initFilePond() {
            FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFileValidateType);
            const initialFiles = (config.oldGallery || []).map(serverId => ({ source: serverId, options: { type: 'local' } }));
            this.filepond = FilePond.create(this.$refs.filepond, {
                allowMultiple: true,
                acceptedFileTypes: ['image/*'],
                files: initialFiles,
                server: {
                    process: { url: config.uploadUrl, headers: { 'X-CSRF-TOKEN': window.csrf } },
                    revert: (uniqueFileId, load, error) => {
                        fetch(config.revertUrl, {
                            method: 'DELETE',
                            headers: { 'Content-Type': 'text/plain', 'X-CSRF-TOKEN': window.csrf },
                            body: uniqueFileId
                        }).then(res => res.ok ? load() : error('Error'));
                    },
                    load: { url: config.loadUrl, headers: { 'X-CSRF-TOKEN': window.csrf } }
                },
                onprocessfile: (error, file) => {
                    if (error) { this.handleFileError(file); return; }
                    this.addFileToGallery(file);
                },
                onload: (file) => { this.addFileToGallery(file); },
                onremovefile: (error, file) => {
                    if (file.serverId) {
                        this.galleryItems = this.galleryItems.filter(item => item.id !== file.serverId);
                    }
                }
            });
        },

        moveItem(index, direction) {
            if ((direction === -1 && index === 0) || (direction === 1 && index === this.galleryItems.length - 1)) return;
            const items = [...this.galleryItems];
            const item = items[index];
            items.splice(index, 1);
            items.splice(index + direction, 0, item);
            this.galleryItems = items;
            if (config.isEdit) this.updateServerOrder();
        },

        updateServerOrder() {
            const orderedIds = this.galleryItems.filter(item => item.source === 'existing').map(item => item.id);
            if (orderedIds.length === 0) return;
            fetch(config.reorderUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': window.csrf },
                body: JSON.stringify({ order: orderedIds })
            });
        },

        removeItem(id) {
            const item = this.galleryItems.find(item => item.id === id);
            if (!item) return;
            if (item.source === 'new') {
                const filepondFile = this.filepond.getFiles().find(f => f.serverId === id);
                if (filepondFile) this.filepond.removeFile(filepondFile.id);
            } else {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'deleted_media[]';
                input.value = id;
                document.getElementById('project-form').appendChild(input);
                this.galleryItems = this.galleryItems.filter(item => item.id !== id);
            }
        },

        addFileToGallery(file) {
            if (!file.serverId || this.galleryItems.some(item => item.id === file.serverId)) return;
            const newItem = {
                id: file.serverId,
                url: window.URL.createObjectURL(file.file),
                source: 'new',
                key: `new-item-${this._newItemCounter++}`
            };
            this.galleryItems = [...this.galleryItems, newItem];
        },
        
        handleFileError(file) {
            console.error('FilePond: Error al procesar el archivo:', file.file.name);
            let errorMessage = 'La subida del archivo ha fallado.';
            try {
                const serverResponse = JSON.parse(file.getServerResponse());
                if (serverResponse?.errors) {
                    errorMessage = Object.values(serverResponse.errors)[0][0];
                }
            } catch (e) { /* No hacer nada si no se puede parsear */ }
            alert(`Error con el archivo "${file.file.name}":\n${errorMessage}`);
            this.filepond.removeFile(file.id);
        }
    };
}
