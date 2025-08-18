<?php

use App\Http\Controllers\FotoJugadorController;
use App\Http\Controllers\LogoEquipoController;

use App\Livewire\Campeonato\CampeonatoCrear;
use App\Livewire\Campeonato\CampeonatoEditar;
use App\Livewire\Campeonato\CampeonatoIndex;
use App\Livewire\Canchas\CanchasCrear;
use App\Livewire\Canchas\CanchasEditar;
use App\Livewire\Canchas\CanchasIndex;
use App\Livewire\Categoria\CategoriaCrear;
use App\Livewire\Categoria\CategoriaEdit;
use App\Livewire\Config\AsignarEquipos;
use App\Livewire\Equipo\EquipoCrear;
use App\Livewire\Equipo\EquipoEditar;
use App\Livewire\Equipo\EquipoIndex;
use App\Livewire\Equipo\ListadoBuenaFe;
use App\Livewire\Fixture\FixtureCrear;
use App\Livewire\Fixture\FixtureEditar;
use App\Livewire\Fixture\FixtureIndex;
use App\Livewire\Fixture\FixtureVer;
use App\Livewire\Jugadore\JugadoresCrear;
use App\Livewire\Jugadore\JugadoresEditar;
use App\Livewire\Jugadore\JugadoresIndex;
use App\Livewire\Roles\AccessControlPanel;

use App\Livewire\Sanciones\SancionesIndex;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\TablaPosicion\TablaPosiciones;
use App\Livewire\TablaPosicion\TablaPosicionIndex;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware([PermissionMiddleware::class . ':ver-usuarios'])->group(function () {});



Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    //=========================ROLES Y PERMISOS================================

    Route::get('/rol/panel-control', AccessControlPanel::class)->name('rol.panel')->middleware('role:admin');

    //=========================ROLES Y PERMISOS================================

    Route::get('/equipo', EquipoIndex::class)->name('equipo.index');
    Route::get('/equipo/crear', EquipoCrear::class)->name('equipo.crear')->middleware('role:admin');
    Route::get('/equipo/{equipoId}/editar', EquipoEditar::class)->name('equipo.editar')->middleware('role:admin');
    Route::get('/equipo/{equipoId}/logo', [LogoEquipoController::class, 'upload'])->name('equipo.logo.upload')->middleware('role:admin');
    Route::post('/equipo/{equipoId}/logo', [LogoEquipoController::class, 'guardarLogo'])->name('equipo.logo.guardar')->middleware('role:admin');
    Route::get('/equipo/listado-buena-fe', ListadoBuenaFe::class)->name('listado-buena-fe');

    //==============JUGADORES========================
    Route::get('/jugadores', JugadoresIndex::class)->name('jugadores.index');
    Route::get('/jugadores/{jugadorId}/editar', JugadoresEditar::class)->name('jugadores.editar')->middleware('role:admin|comision');
    Route::get('/jugadores/crear', JugadoresCrear::class)->name('jugadores.crear')->middleware('role:admin|comision');
    Route::get('/jugadores/{jugadorId}/foto', [FotoJugadorController::class, 'upload'])->name('jugadores.foto.upload')->middleware('role:admin|comision');
    Route::post('/jugadores/{jugadorId}/foto', [FotoJugadorController::class, 'guardarFoto'])->name('jugadores.foto.guardar')->middleware('role:admin|comision');
    //==============FIN JUGADORES========================
    //==============CAMPEONATO========================
    Route::get('/campeonato', CampeonatoIndex::class)->name('campeonato.index');
    Route::get('/campeonato/crear', CampeonatoCrear::class)->name('campeonato.crear')->middleware('role:admin|comision');
    Route::get('/campeonato/{campeonatoId}/editar', CampeonatoEditar::class)->name('campeonato.editar')->middleware('role:admin|comision');
    // Route::get('/campeonato/{campeonatoId}/logo', [LogoCampeonatoController::class, 'upload'])->name('campeonato.logo.upload');
    // Route::post('/campeonato/{campeonatoId}/logo', [LogoCampeonatoController::class, 'guardarLogo'])->name('campeonato.logo.guardar');
    //==============FIN CAMPEONATO========================
    //==============CATEGORIA========================
    Route::get('/categoria', \App\Livewire\Categoria\CategoriaIndex::class)->name('categoria.index');
    Route::get('/categoria/crear', CategoriaCrear::class)->name('categoria.crear')->middleware('role:admin|comision');
    Route::get('/categoria/{categoriaId}/editar', CategoriaEdit::class)->name('categoria.editar')->middleware('role:admin|comision');
    //==============FIN CATEGORIA========================  
    //==============FIN ESTADIOS======================== 
    Route::get('/estadios', CanchasIndex::class)->name('canchas.index');
    Route::get('/estadios/crear', CanchasCrear::class)->name('canchas.crear')->middleware('role:admin|comision');
    Route::get('/estadios/{estadioId}/editar', CanchasEditar::class)->name('canchas.editar')->middleware('role:admin|comision');
    //==============FIN ESTADIOS========================
    //==============FIXTURE======================== 
    Route::get('/fixture', FixtureIndex::class)->name('fixture.index');
    Route::get('/fixture/{campeonatoId}/crear', FixtureCrear::class)->name('fixture.crear')->middleware('role:admin|comision');
    Route::get('/fixture/{campeonatoId}/ver', FixtureVer::class)->name('fixture.ver');
    Route::get('/fixture/{estadioId}/editar', FixtureEditar::class)->name('fixture.editar')->middleware('role:admin|comision');
    Route::get('/fixture/automatico', \App\Livewire\Fixture\FixtureAutomatico::class)->name('fixture.automatico')->middleware('role:admin');
    //==============FIN FIXTURE========================
    //==============ASIGNAR EQUIPOS========================
    Route::get('/campeonato/{campeonatoId}/asignar-equipos', AsignarEquipos::class)->name('asignar-equipos')->middleware('role:admin');
    //==============FIN ASIGNAR EQUIPOS========================
    //==============TABLA DE POSICIONES========================
    Route::get('/tabla-posiciones', TablaPosicionIndex::class)->name('tabla-posiciones');
    Route::get('/tabla-posiciones/{campeonatoId}/ver', \App\Livewire\TablaPosicion\TablaPosiciones::class)->name('tabla-posiciones.ver');
    Route::get('/tabla-posiciones/{campeonato}/tabla-pdf', [TablaPosiciones::class, 'generarTablaPosicionesPDF'])->name('tabla.pdf')->middleware('role:admin|comision');
    Route::get('/exportar-encuentros', [FixtureCrear::class, 'exportar'])->name('encuentros.exportar')->middleware('role:admin|comision');
    //==============FIN TABLA DE POSICIONES========================

    //==============SANCIONES========================
    Route::get('/sanciones', SancionesIndex::class)->name('sanciones.index');
    Route::get('/sanciones/{campeonatoId}/crear', \App\Livewire\Sanciones\SancionesCrear::class)->name('sanciones.crear')->middleware('role:admin|comision');
    Route::get('/sanciones/actualizar-cumplimientos', [\App\Livewire\Sanciones\SancionesCrear::class, 'actualizarCumplimientosSanciones'])->name('sanciones.actualizar-cumplimientos');
    //==============FIN SANCIONES========================
    //==============ESTADISTICA========================
    Route::get('/estadistica', \App\Livewire\Estadistica\EstadisticaIndex::class)->name('estadistica.index');
    Route::get('/estadistica/{campeonatoId}/ver', \App\Livewire\Estadistica\EstadisticaVer::class)->name('estadistica.ver')->middleware('role:admin|comision');
    //==============FIN ESTADISTICA========================
    //==============ALTAS Y BAJAS========================
    Route::get('/altas-bajas', \App\Livewire\AltasBajas\AltasBajasIndex::class)->name('altas-bajas.index')->middleware('role:admin');;
});

require __DIR__ . '/auth.php';
