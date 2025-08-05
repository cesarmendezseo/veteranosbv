<?php

use App\Http\Controllers\FotoJugadorController;
use App\Http\Controllers\LogoEquipoController;
use App\Livewire\Campeonato\Campeonatocrear;
use App\Livewire\Campeonato\CampeonatoEditar;
use App\Livewire\Campeonato\CampeonatoIndex;
use App\Livewire\Categoria\CategoriaCrear;
use App\Livewire\Categoria\CategoriaEdit;
use App\Livewire\Config\AsignarEquipos;
use App\Livewire\Equipo\EquipoCrear;
use App\Livewire\Equipo\EquipoEditar;
use App\Livewire\Equipo\EquipoIndex;
use App\Livewire\Estadios\EstadiosCrear;
use App\Livewire\Estadios\EstadiosEditar;
use App\Livewire\Estadios\EstadiosIndex;
use App\Livewire\Jugadore\JugadoresCrear;
use App\Livewire\Jugadore\JugadoresEditar;
use App\Livewire\Jugadore\JugadoresIndex;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');



Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('/equipo', EquipoIndex::class)->name('equipo.index');
    Route::get('/equipo/crear', EquipoCrear::class)->name('equipo.crear');
    Route::get('/equipo/{equipoId}/editar', EquipoEditar::class)->name('equipo.editar');
    Route::get('/equipo/{equipoId}/logo', [LogoEquipoController::class, 'upload'])->name('equipo.logo.upload');
    Route::post('/equipo/{equipoId}/logo', [LogoEquipoController::class, 'guardarLogo'])->name('equipo.logo.guardar');
    //==============JUGADORES========================
    Route::get('/jugadores', JugadoresIndex::class)->name('jugadores.index');
    Route::get('/jugadores/{jugadorId}/editar', JugadoresEditar::class)->name('jugadores.editar');
    Route::get('/jugadores/crear', JugadoresCrear::class)->name('jugadores.crear');
    Route::get('/jugadores/{jugadorId}/foto', [FotoJugadorController::class, 'upload'])->name('jugadores.foto.upload');
    Route::post('/jugadores/{jugadorId}/foto', [FotoJugadorController::class, 'guardarFoto'])->name('jugadores.foto.guardar');
    //==============FIN JUGADORES========================
    //==============CAMPEONATO========================
    Route::get('/campeonato', CampeonatoIndex::class)->name('campeonato.index');
    Route::get('/campeonato/crear', CampeonatoCrear::class)->name('campeonato.crear');
    Route::get('/campeonato/{campeonatoId}/editar', CampeonatoEditar::class)->name('campeonato.editar');
    // Route::get('/campeonato/{campeonatoId}/logo', [LogoCampeonatoController::class, 'upload'])->name('campeonato.logo.upload');
    // Route::post('/campeonato/{campeonatoId}/logo', [LogoCampeonatoController::class, 'guardarLogo'])->name('campeonato.logo.guardar');
    //==============FIN CAMPEONATO========================
    //==============CATEGORIA========================
    Route::get('/categoria', \App\Livewire\Categoria\CategoriaIndex::class)->name('categoria.index');
    Route::get('/categoria/crear', CategoriaCrear::class)->name('categoria.crear');
    Route::get('/categoria/{categoriaId}/editar', CategoriaEdit::class)->name('categoria.editar');
    //==============FIN CATEGORIA========================  
    //==============FIN ESTADIOS======================== 
    Route::get('/estadios', EstadiosIndex::class)->name('estadios.index');
    Route::get('/estadios/crear', EstadiosCrear::class)->name('estadios.crear');
    Route::get('/estadios/{estadioId}/editar', EstadiosEditar::class)->name('estadios.editar');
    //==============FIN ESTADIOS========================
    //==============ASIGNAR EQUIPOS========================
    Route::get('/campeonato/{campeonatoId}/asignar-equipos', AsignarEquipos::class)->name('asignar-equipos');
    //==============FIN ASIGNAR EQUIPOS========================

});

require __DIR__ . '/auth.php';
