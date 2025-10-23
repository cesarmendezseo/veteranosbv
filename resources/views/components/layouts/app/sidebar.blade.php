<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
            <x-app-logo />
        </a>

        <flux:menu.separator />
        <flux:navlist.group :heading="__('')" class="grid">
            @role('administrador|comision')
            <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
            <flux:navlist.item icon="shirt" :href="route('jugadores.index')"
                :current="request()->routeIs('jugadores.index')" wire:navigate>{{ __('Jugadores') }}
            </flux:navlist.item>
            <flux:navlist.item icon="ticket" :href="route('canchas.index')"
                :current="request()->routeIs('canchas.index')" wire:navigate>{{ __('Canchas') }}
            </flux:navlist.item>
            <flux:navlist.item icon="git-fork" :href="route('fixture.index')"
                :current="request()->routeIs('fixture.index')" wire:navigate>{{ __('Fixture') }}
            </flux:navlist.item>
            <flux:navlist.item icon="numbered-list" :href="route('listado-buena-fe')"
                :current="request()->routeIs('listado-buena-fe')" wire:navigate>{{ __('Listado Buena fe') }}
            </flux:navlist.item>
            <flux:navlist.item icon="table-2" :href="route('tabla-posiciones')"
                :current="request()->routeIs('tabla-posiciones')" wire:navigate>{{ __('Tabla de Posición') }}
            </flux:navlist.item>
            <flux:navlist.item icon="pencil-line" :href="route('sanciones.index')"
                :current="request()->routeIs('sanciones.index')" wire:navigate>{{ __('Sanciones') }}
            </flux:navlist.item>
            <flux:navlist.item icon="identidication" :href="route('estadistica.index')"
                :current="request()->routeIs('estadistica.index')" wire:navigate>{{ __('Estadisticas') }}
            </flux:navlist.item>
            <flux:navlist.item icon="git-pull-request-draft" :href="route('altas-bajas.index')"
                :current="request()->routeIs('altas-bajas.index')" wire:navigate>{{ __('Altas y Bajas') }}
            </flux:navlist.item>
            <flux:navlist.item icon="album" :href="route('categoria.index')"
                :current="request()->routeIs('categoria.index')" wire:navigate>{{ __('Categoria') }}
            </flux:navlist.item>

            <flux:navlist.item icon="medal" :href="route('campeonato.index')"
                :current="request()->routeIs('campeonato.index')" wire:navigate>{{ __('Campeonato') }}
            </flux:navlist.item>
            <flux:navlist.item icon="shield" :href="route('equipo.index')" :current="request()->routeIs('equipo.index')"
                wire:navigate>{{ __('Equipos') }}</flux:navlist.item>
            @endrole
            @role('administrador|comision')
            <flux:navlist.item icon="cog" :href="route('config.index')" :current="request()->routeIs('config.index')"
                wire:navigate>{{ __('Config') }}
            </flux:navlist.item>
            @endrole
            @role('administrador')
            <flux:navlist.item icon="user-plus" :href="route('rol.panel')" :current="request()->routeIs('rol.panel')"
                wire:navigate>{{ __('Roles y Permisos') }}
            </flux:navlist.item>
            @endrole
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                    class="w-full cursor-pointer">
                    {{ __('Salir') }}
                </flux:menu.item>
            </form>
        </flux:navlist.group>
        <flux:menu.separator />
        <flux:navlist variant="outline">

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="bottom" align="start">
                <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down" />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{
                            __('Configuración') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Salir') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:navlist>








    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Configuración') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Salir') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>

    </flux:header>

    {{ $slot }}

    {{-- menu fijo en movil --}}
    <nav
        class="fixed bottom-0 left-0 right-0 z-50 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 sm:hidden">
        <div class="flex justify-around items-center h-16">
            <a href="{{ route('dashboard') }}"
                class="flex flex-col items-center text-xs text-gray-600 dark:text-gray-300 hover:text-blue-600">
                <x-icon name="home" class="w-5 h-5 mb-1" />
                Dashboard
            </a>
            <a href="{{ route('jugadores.index') }}"
                class="flex flex-col items-center text-xs text-gray-600 dark:text-gray-300 hover:text-blue-600">
                <x-icon name="shirt" class="w-5 h-5 mb-1" />
                Jugadores
            </a>
            <div class="flex flex-col items-center text-xs text-gray-600 dark:text-gray-300 hover:text-blue-600">
                <flux:sidebar.toggle class="" icon="mas">
                    ver mas
                </flux:sidebar.toggle>
            </div>
            <a href="{{ route('fixture.index') }}"
                class="flex flex-col items-center text-xs text-gray-600 dark:text-gray-300 hover:text-blue-600">
                <x-icon name="calendar" class="w-5 h-5 mb-1" />
                Fixture
            </a>
            <a href="{{ route('estadistica.index') }}"
                class="flex flex-col items-center text-xs text-gray-600 dark:text-gray-300 hover:text-blue-600">
                <x-icon name="chart-bar" class="w-5 h-5 mb-1" />
                Stats
            </a>

        </div>
    </nav>
    @fluxScripts
</body>

</html>