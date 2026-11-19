document.addEventListener('DOMContentLoaded', () => {
    const avatarInput = document.getElementById('avatar');
    const previewImg = document.getElementById('avatar-live-preview');
    const previewStatus = document.getElementById('avatar-preview-status');
    const previewFilename = document.getElementById('avatar-preview-filename');
    const removeCheckbox = document.getElementById('remove_avatar');

    if (!avatarInput || !previewImg) {
        return;
    }

    let activeObjectUrl = null;
    const defaultSrc = previewImg.getAttribute('data-default-src') || previewImg.src;
    const defaultStatus = previewStatus ? previewStatus.textContent : '';
    const defaultFilename = previewFilename ? previewFilename.textContent : '';

    avatarInput.addEventListener('change', () => {
        if (activeObjectUrl) {
            URL.revokeObjectURL(activeObjectUrl);
            activeObjectUrl = null;
        }

        const file = avatarInput.files && avatarInput.files[0];
        if (!file) {
            previewImg.src = defaultSrc;
            if (previewStatus) previewStatus.textContent = defaultStatus;
            if (previewFilename) previewFilename.textContent = defaultFilename;
            return;
        }

        if (removeCheckbox) {
            removeCheckbox.checked = false;
        }

        if (file.type && file.type.startsWith('image/')) {
            activeObjectUrl = URL.createObjectURL(file);
            previewImg.src = activeObjectUrl;
        }

        if (previewStatus) {
            previewStatus.textContent = 'Selected Image Ready';
        }
        if (previewFilename) {
            const sizeKb = Math.max(1, Math.round(file.size / 1024));
            previewFilename.textContent = `${file.name} (${sizeKb} KB)`;
        }
    });
});
