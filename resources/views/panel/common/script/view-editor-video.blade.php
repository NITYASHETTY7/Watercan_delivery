<script async charset="utf-8" src="{{ asset($master_root_directory . 'plugins/widgets/platform.js') }}"></script>
<script>
    document.querySelectorAll('oembed[url]').forEach(element => {
        // Create the <a href="..." class="embedly-card"></a> element that Embedly uses
        // to discover the media.
        const anchor = document.createElement('a');

        anchor.setAttribute('href', element.getAttribute('url'));
        anchor.className = 'embedly-card';

        element.appendChild(anchor);
    });
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('p a').forEach((element) => {
            // Apply a blue color style to the link
            element.style.color = 'blue';
            element.style.textDecoration = 'underline'; // Optional: underline the link
        });
    });
</script>
