<aside class="bg-white border border-gray-300 rounded-xl shadow-sm d-none d-md-flex flex-column position-fixed"
    style="width: 250px; z-index: 1030; top: 15px; bottom: 15px; left: 15px;">

    <div class="p-2">
        <a href="{{ route('home') }}" class="d-block py-3 px-2 text-center">
            <img src="{{ asset('images/logo.svg') }}" alt="Логотип" class="img-fluid max-w-100" style="max-width: 180px;">
        </a>
    </div>

    @include('layout.sidebar-mobile', ['activePage' => $activePage])
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
