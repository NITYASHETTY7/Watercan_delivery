<script>
    function copyToClipboard(button) {
        // Get the URL from the data-url attribute
        var value = button.dataset.value;

        // Create a temporary input field to copy the value
        var tempInput = document.createElement('input');
        document.body.appendChild(tempInput);
        tempInput.value = value;
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);

        // Get the icon element inside the button
        var icon = button.querySelector('i');

        // Save the original icon class
        var originalIconClass = icon.className;

        // Determine the "copied" icon based on the original icon class
        let copiedIconClass;
        if (originalIconClass.includes('uil uil-copy')) {
            copiedIconClass = 'uil uil-check';
        } else if (originalIconClass.includes('ik ik-copy')) {
            copiedIconClass = 'ik ik-check';
        } else if (originalIconClass.includes('fa fa-copy')) {
            copiedIconClass = 'fa fa-check';
        } else {
            copiedIconClass = originalIconClass; // Default to the original class if not recognized
        }

        // Change the icon to show "Copied"
        icon.className = copiedIconClass;

        // Revert back to the original icon after 2 seconds
        setTimeout(() => {
            icon.className = originalIconClass;
        }, 2000);
    }
</script>
