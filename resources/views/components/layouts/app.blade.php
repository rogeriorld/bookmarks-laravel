<!DOCTYPE html>
<!-- Boostrap dark theme to the body -->
<html lang="en" class="h-100" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('/images/favicon.png') }}" />
    <title>{{ config('app.name') }}</title>

    @vite(['resources/js/app.js', 'resources/css/app.scss'])
    @livewireStyles
</head>

<body class="h-100 d-flex flex-column">

    <header class="container-fluid">
        @livewire('navigation')
    </header>

    <main class="container flex-grow-1">
        @livewire('notification')
        {{ $slot }}
    </main>

    <footer class="container-fluid">
        <div class="row">
            <div class="col-12 text-center">
                <p class="">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
            </div>
        </div>
    </footer>
    {{-- Adicionar um script para quando o usuário começar a digitar, ir para o campo de busca --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('keydown', function(event) {
                const searchField = document.getElementById('search');
                if (searchField && event.key.length === 1 &&
                    !searchField.contains(event.target) &&
                    event.target.tagName !== 'INPUT') {
                    searchField.value = "";
                    searchField.focus();
                }
            });
        });
    </script>
    @livewireScripts
    @stack('scripts')
</body>

</html>
