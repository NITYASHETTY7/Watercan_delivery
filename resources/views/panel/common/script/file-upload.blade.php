<script>
    const fileInputs = document.getElementsByClassName('file-uploader');
    if (fileInputs && fileInputs.length > 0) {
        Array.from(fileInputs).forEach(function(fileInput) {
            // Get allowed extensions from the `accept` attribute
            const allowedExtensions = fileInput.getAttribute('accept')
                .replace(/\s/g, '') // Remove any spaces
                .split(',') // Split by comma
                .map(ext => ext.replace('.', '').toLowerCase()); // Remove the dot and convert to lowercase
            fileInput.addEventListener('change', function() {
                const file = this.files[0];

                if (file) {
                    const fileExtension = file.name.split('.').pop()
                        .toLowerCase(); // Get the file extension

                    if (!allowedExtensions.includes(fileExtension)) {
                        alert(
                            `Only files with these extensions are allowed: ${allowedExtensions.join(', ')}`
                            );
                        this.value = ''; // Reset the input
                    }
                }
            });
        });
    }
</script>
