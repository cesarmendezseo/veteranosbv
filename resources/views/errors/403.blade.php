<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Error 403 - Acceso denegado</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="text-center">
        <h1 class="text-6xl font-bold text-red-600">403</h1>
        <p class="mt-4 text-xl text-gray-700">No tienes permisos para acceder a esta página.</p>

        <div class="mt-6 space-x-4">
            {{-- Volver a la ruta anterior validada en backend --}}
            <a href="{{ url()->previous() ?: route('dashboard') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                ⬅️ Volver
            </a>

        </div>
    </div>
</body>

</html>