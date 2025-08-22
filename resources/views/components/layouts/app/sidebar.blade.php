<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    @include('sweetalert2::index')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable class=" border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <flux:brand href="#" name="Futsal">
            <x-slot name="logo" class="size-6 rounded-full bg-cyan-500 text-white text-xs font-bold">
                <flux:icon name="rocket-launch" variant="micro" />
            </x-slot>
        </flux:brand>

        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Platform')" class="grid">
            @adminOrCan()    
            <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
            @endadminOrCan
            @adminOrCan()
                <flux:navlist.item icon="shield-ban" :href="route('equipo.index')" :current="request()->routeIs('equipo.index')" wire:navigate>{{ __('Equipo') }}</flux:navlist.item>
               @endadminOrCan
                @adminOrCan()
                <flux:navlist.item icon="users" :href="route('jugadores.index')" :current="request()->routeIs('jugadores.index')" wire:navigate>{{ __('Jugadores') }}</flux:navlist.item>
                @endadminOrCan
                @adminOrCan()
                <flux:navlist.item icon="circuit-board" :href="route('fixture.index')" :current="request()->routeIs('fixture.index')" wire:navigate>{{ __('Fixture') }}</flux:navlist.item>
               @endadminOrCan
               @adminOrCan()
                <flux:navlist.item icon="book-user" :href="route('listado-buena-fe')" :current="request()->routeIs('listado-buena-fe')" wire:navigate>{{ __('Listado Buena Fe') }}</flux:navlist.item>
                @endadminOrCan

                @adminOrCan()
                <flux:navlist.item icon="id-card" :href="route('sanciones.index')" :current="request()->routeIs('sanciones.index')" wire:navigate>{{ __('Estadisticas') }}</flux:navlist.item>
                @endadminOrCan
                <flux:navlist.item icon="clipboard-list" :href="route('tabla-posiciones')" :current="request()->routeIs('tabla-posiciones')" wire:navigate>{{ __('Tabla Posición') }}</flux:navlist.item>
                @adminOrCan()   
                <flux:navlist.item icon="user-plus" :href="route('altas-bajas.index')" :current="request()->routeIs('altas-bajas.index')" wire:navigate>{{ __('Altas y Bajas') }}</flux:navlist.item>
                @endadminOrCan
                @adminOrCan()
                <flux:navlist.group expandable heading="Config" class="hidden lg:grid">
                    <flux:navlist.item icon="trophy" :href="route('campeonato.index')" :current="request()->routeIs('campeonato.index')" wire:navigate>{{ __('Campeonato') }}</flux:navlist.item>
                    <flux:navlist.item icon="shield-check" :href="route('categoria.index')" :current="request()->routeIs('categoria.index')" wire:navigate>{{ __('Categoria') }}</flux:navlist.item>
                    <flux:navlist.item icon="device-phone-mobile" :href="route('canchas.index')" :current="request()->routeIs('Canchas.index')" wire:navigate>{{ __('Canchas') }}</flux:navlist.item>
                </flux:navlist.group>
                @endadminOrCan
                @adminOrCan('ver equipo')
                <flux:navlist.item icon="shield-check" :href="route('rol.panel')" :current="request()->routeIs('rol.panel')" wire:navigate>{{ __('Roles y Permisos') }}</flux:navlist.item>

                <flux:navlist.item icon="shield-check" :href="route('listado.roles.permisos')" :current="request()->routeIs('rol.panel')" wire:navigate>{{ __('Listado Roles y Permisos') }}</flux:navlist.item>
                @endadminOrCan
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />



        <!-- Desktop User Menu -->
        <flux:dropdown class="hidden lg:block" position="bottom" align="start">
            <flux:profile
                :name="auth()->user()->name"
                :initials="auth()->user()->initials()"
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
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
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
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile
                :initials="auth()->user()->initials()"
                icon-trailing="chevron-down" />

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
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Configuración') }}</flux:menu.item>
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
    @livewireScripts
    @stack('js')
    @fluxScripts
</body>

</html>