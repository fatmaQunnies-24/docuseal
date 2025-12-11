<x-filament::page>

    <script src="https://cdn.docuseal.com/js/form.js"></script>

    <docuseal-form
        id="docusealForm"
        data-src="https://docuseal.com/d/{{ $templateSlug }}"
        data-email="{{ $email }}"
        data-name="{{ $name }}">
    </docuseal-form>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('docusealForm');

            form.addEventListener('completed', (e) => {
                console.log('DocuSeal Completed:', e.detail);
            });
        });
    </script>

</x-filament::page>
