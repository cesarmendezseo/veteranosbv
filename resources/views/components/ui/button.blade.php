@props([
'type' => 'button', // Tipo de botón: button, submit, reset
'variant' => 'primary', // Variante: primary, secondary, danger, success, outline, ghost
'size' => 'md', // Tamaño: sm, md, lg
'loading' => false, // Estado de carga
'disabled' => false, // Estado deshabilitado
'icon' => null, // Icono opcional (nombre del componente de icono)
'iconPosition' => 'left',// Posición del icono: left, right
])

@php
// 1. Modificamos las baseClasses para que el ring y el rounded no choquen con el CSS custom
$baseClasses = 'inline-flex items-center justify-center gap-2 font-medium transition-all duration-200
focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer';

// Variantes de color (aquí defines los estilos de cada variante)
$variantClasses = match($variant) {
'sliding-green' => 'btn-sliding-green',
'primary' => 'bg-[#007BA7] hover:bg-blue-700 text-white focus:ring-blue-500',
'secondary' => 'bg-gray-600 hover:bg-gray-700 text-white focus:ring-gray-500',
'danger' => 'bg-[#E84B3D] hover:bg-red-700 text-white focus:ring-red-500',
'success' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
'warning' => 'bg-yellow-500 hover:bg-yellow-600 text-white focus:ring-yellow-500',
'outline' => 'border-2 border-blue-600 text-blue-600 bg-transparent hover:bg-blue-50 focus:ring-blue-500',
'ghost' => 'text-gray-700 hover:bg-gray-100 focus:ring-gray-400',
'link' => 'text-blue-600 hover:text-blue-700 hover:underline focus:ring-blue-500',
'delet' => 'bg-[#E84B3D] hover:bg-red-700 text-white focus:ring-red-500',
'edit'=>'bg-[#FFBF00] hover:bg-[#EAA221] text-black focus:ring-yellow-500',
'cancel'=>'bg-[#CBCBCB] text-black hover:bg-[#575757] focus:ring-[#575757] hover:text-[#CBCBCB]',
default => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500',
};

// Tamaños
$sizeClasses = match($size) {
'xs' => 'px-2.5 py-1 text-xs',
'sm' => 'px-3 py-1.5 text-sm',
'md' => 'px-4 py-2 text-base',
'lg' => 'px-6 py-3 text-lg',
'xl' => 'px-8 py-4 text-xl',
default => 'px-4 py-2 text-base',
};

// Definir iconos SVG predefinidos
$icons = [
'save' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
</svg>',

'delete' => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
    <path
        d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z" />
</svg>',

'edit' => '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000000">
    <path
        d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z" />
</svg>',

'download' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
</svg>',

'upload' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
</svg>',

'search' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
</svg>',

'cancel'=>'<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
    <path
        d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
</svg>',
'plus' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
</svg>',

'check' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
</svg>',

'close' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
    class="lucide lucide-circle-x-icon lucide-circle-x">
    <circle cx="12" cy="12" r="10" />
    <path d="m15 9-6 6" />
    <path d="m9 9 6 6" />
</svg>',

'refresh' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
</svg>',
'create'=>'<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
    <path
        d="M440-280h80v-160h160v-80H520v-160h-80v160H280v80h160v160Zm40 200q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z" />
</svg>',
'edit-user'=>'<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
    fill="#FFFFFF">
    <path
        d="M480-240Zm-320 80v-112q0-34 17.5-62.5T224-378q62-31 126-46.5T480-440q37 0 73 4.5t72 14.5l-67 68q-20-3-39-5t-39-2q-56 0-111 13.5T260-306q-9 5-14.5 14t-5.5 20v32h240v80H160Zm400 40v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T903-340L683-120H560Zm300-263-37-37 37 37ZM620-180h38l121-122-18-19-19-18-122 121v38Zm141-141-19-18 37 37-18-19ZM480-480q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47Zm0-80q33 0 56.5-23.5T560-640q0-33-23.5-56.5T480-720q-33 0-56.5 23.5T400-640q0 33 23.5 56.5T480-560Zm0-80Z" />
</svg>',
'create-user'=>'<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
    fill="#FFFFFF">
    <path
        d="M720-400v-120H600v-80h120v-120h80v120h120v80H800v120h-80Zm-360-80q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm80-80h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0-80Zm0 400Z" />
</svg>',
'view'=>'<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#1f1f1f">
    <path d="M607.5-372.5Q660-425 660-500t-52.5-127.5Q555-680 480-680t-127.5 52.5Q300-575 300-500t52.5 127.5Q405-320 480-320t127.5-52.5Zm-204-51Q372-455 372-500t31.5-76.5Q435-608 480-608t76.5 31.5Q588-545 588-500t-31.5 76.5Q525-392 480-392t-76.5-31.5ZM214-281.5Q94-363 40-500q54-137 174-218.5T480-800q146 0 266 81.5T920-500q-54 137-174 218.5T480-200q-146 0-266-81.5ZM480-500Zm207.5 160.5Q782-399 832-500q-50-101-144.5-160.5T480-720q-113 0-207.5 59.5T128-500q50 101 144.5 160.5T480-280q113 0 207.5-59.5Z" />
</svg>'
];

$iconSvg = $icon && array_key_exists($icon, $icons)
? $icons[$icon]
: null;

// Determina si está deshabilitado
$isDisabled = $disabled || $loading;

@endphp
<style>
    /* app.css */
    .btn-sliding-green {
        min-width: 130px;
        height: 40px;
        color: #fff;
        font-weight: bold;
        transition: all 0.3s ease;
        position: relative;
        display: inline-flex;
        /* Cambiado para alinear iconos */
        align-items: center;
        justify-content: center;
        outline: none;
        border-radius: 5px;
        border: none;
        box-shadow: inset 2px 2px 2px 0px rgba(255, 255, 255, .5), 7px 7px 20px 0px rgba(0, 0, 0, .1), 4px 4px 5px 0px rgba(0, 0, 0, .1);
        background: #57cc99;
        z-index: 1;
        overflow: hidden;
        /* Importante para el efecto sliding */
    }

    .btn-sliding-green:after {
        border-radius: 5px;
        position: absolute;
        content: "";
        width: 0;
        height: 100%;
        top: 0;
        z-index: -1;
        box-shadow: inset 2px 2px 2px 0px rgba(255, 255, 255, .5), 7px 7px 20px 0px rgba(0, 0, 0, .1), 4px 4px 5px 0px rgba(0, 0, 0, .1);
        transition: all 0.3s ease;
        background-color: #80ed99;
        right: 0;
    }

    .btn-sliding-green:hover:after {
        width: 100%;
        left: 0;
    }

    .btn-sliding-green:active {
        transform: translateY(2px);
    }
</style>

<button type="{{ $type }}" {{ $attributes->class([
    $baseClasses,
    $variantClasses,
    $sizeClasses,
    ])->merge([
    'disabled' => $isDisabled,
    ]) }}>
    {{-- Icono de carga --}}
    @if($loading)
    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor"
            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
        </path>
    </svg>
    @endif

    {{-- Icono izquierda --}}
    @if($iconSvg && $iconPosition === 'left' && !$loading)
    <span class="shrink-0">
        {!! $iconSvg !!}
    </span>
    @endif

    {{-- Contenido del botón --}}

    {{-- Envolvemos el slot (texto) para ocultarlo en móviles --}}
    <span class="{{ $icon ? 'hidden md:inline' : '' }}">
        {{ $slot }}
    </span>
    {{-- Icono a la derecha --}}
    @if($iconSvg && $iconPosition === 'right' && !$loading)
    <span class="shrink-0">
        {!! $iconSvg !!}
    </span>
    @endif

</button>

{{-- DISTINTOS MODO DE USAR EL BOTON 

<x-ui.button icon-name="save">
    Guardar
</x-ui.button>

{{-- Botón con variante --
<x-ui.button icon-name="delete" variant="danger">
    Eliminar
</x-ui.button>

{{-- Botón con tamaño --
<x-ui.button icon-name="plus" variant="success" size="lg">
    Confirmar
</x-ui.button>

{{-- Botón deshabilitado --
<x-ui.button :disabled="true">
    No disponible
</x-ui.button>

{{-- Botón con estado de carga --
<x-ui.button :loading="true">
    Guardar
</x-ui.button>

{{-- Botón con Livewire --
<x-ui.button variant="primary" wire:click="save">
    Guardar cambios
</x-ui.button>

{{-- Botón de tipo submit --
<x-ui.button type="submit" variant="success">
    Enviar formulario
</x-ui.button>

{{-- Botón con clases adicionales --
<x-ui.button class="w-full mt-4">
    Botón ancho completo
</x-ui.button>

{{-- Botón outline --
<x-ui.button variant="outline">
    Cancelar
</x-ui.button>

-- Botón tipo link --
<x-ui.button variant="link" size="sm">
    Ver más
</x-ui.button>

-- Botón con icono SVG -
<x-ui.button variant="primary">
    <x-slot:icon>
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="m11.25 9-3 3m0 0 3 3m-3-3h7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>

    </x-slot:icon>
    Con Icono
</x-ui.button>
--}}