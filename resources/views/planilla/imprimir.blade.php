<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planilla - {{ $torneoNombre }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body>
    @livewire('equipo.imprimir', [
    'equipoId' => $equipoId,
    'torneoNombre' => $torneoNombre,
    'campeonatoId' => $campeonatoId,
    'fecha' => $fecha
    ])

    @livewireScripts
</body>

</html>