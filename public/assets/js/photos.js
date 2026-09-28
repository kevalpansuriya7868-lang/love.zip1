// Photo Gallery & Lightbox Handler
document.addEventListener('DOMContentLoaded', () => {
    // Lightbox modal setup
    const lightboxModal = document.getElementById('lightboxModal');
    const lightboxImage = document.getElementById('lightboxImage');
    const lightboxCaption = document.getElementById('lightboxCaption');

    window.openLightbox = (src, caption = '') => {
        if (!lightboxImage) return;
        lightboxImage.src = src;
        if (lightboxCaption) lightboxCaption.innerText = caption;
        const bsModal = new bootstrap.Modal(lightboxModal);
        bsModal.show();
    };

    // AJAX Photo Upload
    const photoUploadForm = document.getElementById('photoUploadForm');
    if (photoUploadForm) {
        photoUploadForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(photoUploadForm);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            formData.append('csrf_token', csrfToken);

            const submitBtn = photoUploadForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Uploading...';
            submitBtn.disabled = true;

            try {
                const res = await fetch(window.APP_URL + '/api/photos/upload', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                });
                const json = await res.json();
                if (json.success) {
                    location.reload();
                } else {
                    alert(json.message || 'Upload failed.');
                }
            } catch (err) {
                alert('Upload failed due to network error.');
            } finally {
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            }
        });
    }

    // Delete Photo Handler
    window.deletePhoto = async (id) => {
        if (!confirm('Are you sure you want to delete this photo?')) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const formData = new FormData();
        formData.append('id', id);
        formData.append('csrf_token', csrfToken);

        try {
            const res = await fetch(window.APP_URL + '/api/photos/delete', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });

            const json = await res.json();
            if (json.success) {
                const card = document.getElementById(`photo-card-${id}`);
                if (card) card.remove();
            } else {
                alert(json.message || 'Failed to delete photo.');
            }
        } catch (e) {
            alert('Failed to delete photo.');
        }
    };
    // Create Folder Handler
    const folderCreateForm = document.getElementById('folderCreateForm');
    if (folderCreateForm) {
        folderCreateForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(folderCreateForm);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            formData.append('csrf_token', csrfToken);

            const submitBtn = folderCreateForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Creating...';
            submitBtn.disabled = true;

            try {
                const res = await fetch(window.APP_URL + '/api/photos/folders/create', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                });
                const json = await res.json();
                if (json.success) {
                    location.reload();
                } else {
                    alert(json.message || 'Folder creation failed.');
                }
            } catch (err) {
                alert('Folder creation failed due to network error.');
            } finally {
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            }
        });
    }

    // Delete Folder Handler
    window.deleteFolder = async (id) => {
        if (!confirm('Are you sure you want to delete this folder? Photos inside it will be moved to Unfiled Photos.')) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const formData = new FormData();
        formData.append('id', id);
        formData.append('csrf_token', csrfToken);

        try {
            const res = await fetch(window.APP_URL + '/api/photos/folders/delete', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });

            const json = await res.json();
            if (json.success) {
                const card = document.getElementById(`folder-card-${id}`);
                if (card) card.remove();
            } else {
                alert(json.message || 'Failed to delete folder.');
            }
        } catch (e) {
            alert('Failed to delete folder.');
        }
    };
});
