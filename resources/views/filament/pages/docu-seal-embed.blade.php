<x-filament::page>

    <script src="https://cdn.docuseal.com/js/form.js"></script>

    <docuseal-form
        id="docusealForm"
        data-src="https://docuseal.com/d/{{ $this->templateSlug }}"
        data-token="{{ $this->token }}"
    data-email="{{ $this->email }}"
    data-name="{{ $this->name }}"
          data-values="{{ $this->fieldValues }}"
    >
    </docuseal-form>

    <script>
            alert(
        'Name: {{ $this->name }}\n' +
        'Email: {{ $this->email }}\n' +
        'Token length: {{ strlen($this->token ?? "") }}'
    );
            console.log('DocuSeal Prefill Values:', @json($this->fieldValues));

        window.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('docusealForm');

            form.addEventListener('completed', (e) => {
                console.log('DocuSeal Completed:', e.detail);
            });
        });
    </script>

</x-filament::page>
