<x-filament::page>

    <script src="https://cdn.docuseal.com/js/form.js"></script>

    <docuseal-form
    id="docusealForm"
    data-src="https://docuseal.com/d/{{ $this->templateSlug }}"
    data-email="{{ $this->email }}"
    data-name="{{ $this->name }}">
</docuseal-form>


    <script>


alert('fff');
        window.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('docusealForm');
      console.log('DocuSeal Completed:', form);
            form.addEventListener('completed', (e) => {
                console.log('DocuSeal Completed:', e.detail);
            });
        });
    </script>

</x-filament::page>
