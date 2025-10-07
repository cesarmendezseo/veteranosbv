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
use App\Livewire\Equipo\ListadoBuenaFeIndex;
use App\Livewire\Fixture\Eliminatoria;
use App\Livewire\Fixture\EliminatoriaVer;
use App\Livewire\Fixture\FixtureCrear;
use App\Livewire\Fixture\FixtureEditar;
use App\Livewire\Fixture\FixtureIndex;
use App\Livewire\Fixture\FixtureVer;
use App\Livewire\Frontend\Fixture\FixtureIndex as FixtureFixtureIndex;
use App\Livewire\Frontend\Fixture\FixtureVer as FixtureFixtureVer;
use App\Livewire\Frontend\PaginaPrincipal;
use App\Livewire\Frontend\TablaPosicion\TablaPosicionIndex as TablaPosicionTablaPosicionIndex;
use App\Livewire\Frontend\TablaPosicion\TablaPosicionResultados;
use App\Livewire\Jugadore\JugadoresCrear;
use App\Livewire\Jugadore\JugadoresEditar;
use App\Livewire\Jugadore\JugadoresIndex;
use App\Livewire\Roles\AccessControlPanel;
use App\Livewire\Sanciones\SancionesIndex;
use App\Livewire\TablaPosicion\TablaPosiciones;
use App\Livewire\TablaPosicion\TablaPosicionIndex;
use App\Livewire\UserList;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Spatie\Permission\Middleware\PermissionMiddleware;

Route::get('/home', function () {
    return view('welcome');
})->name('home');


Route::get('/', PaginaPrincipal::class)->name('pagina-principal-index');
Route::get('/tabla-posicion-index', TablaPosicionTablaPosicionIndex::class)->name('tabla-posicion-index');
Route::get('/tabla-posicion/{campeonatoId}/ver', TablaPosicionResultados::class)->name('tabla-posicion-resultados');
Route::get('/fixture-index', FixtureFixtureIndex::class)->name('frontend.fixture.index');
Route::get('/fixture/{campeonatoId}/ver-fixture', FixtureFixtureVer::class)->name('frontend.fixture.verFixture');
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');



    Route::middleware([PermissionMiddleware::class . ':ver-usuarios'])->group(function () {});

    Route::redirect('settings', 'settings/profile');

    //===============ROLES Y PERMIOS ===========================
    Route::get('/roles-y-permisos', UserList::class)->name('listado.roles.permisos');
    Route::get('/rol/panel-control', AccessControlPanel::class)->name('rol.panel');
    // Rutas de editaristración de roles y permisos
    Route::middleware(['permission:comision|administrador'])->group(function () {});
    //=============== FIN ROLES Y PERMIOS ===========================  

    //===================EQUIPOS ===================================
    Route::get('/equipo', EquipoIndex::class)->name('equipo.index');
    Route::middleware(['permission:comision|administrador'])->group(function () {
        Route::get('/equipo/crear', EquipoCrear::class)->name('equipo.crear');
        Route::get('/equipo/{equipoId}/editar', EquipoEditar::class)->name('equipo.editar');
    });
    Route::get('/equipo/{equipoId}/logo', [LogoEquipoController::class, 'upload'])->name('equipo.logo.upload');
    Route::post('/equipo/{equipoId}/logo', [LogoEquipoController::class, 'guardarLogo'])->name('equipo.logo.guardar');
    Route::get('/equipo/listado-buena-fe', ListadoBuenaFeIndex::class)->name('listado-buena-fe');
    Route::get('/equipo/listado-buena-fe/{campeonatoId}/ver', ListadoBuenaFe::class)->name('listado-buena-fe.ver');

    //=================FIN EQUIPOS====================

    //==============JUGADORES========================
    Route::get('/jugadores', JugadoresIndex::class)->name('jugadores.index');
    Route::middleware(['permission:comision|administrador'])->group(function () {
        Route::get('/jugadores/{jugadorId}/editar', JugadoresEditar::class)->name('jugadores.editar');
        Route::get('/jugadores/crear', JugadoresCrear::class)->name('jugadores.crear');
    });
    Route::get('/jugadores/{jugadorId}/foto', [FotoJugadorController::class, 'upload'])->name('jugadores.foto.upload');
    Route::post('/jugadores/{jugadorId}/foto', [FotoJugadorController::class, 'guardarFoto'])->name('jugadores.foto.guardar');
    //==============FIN JUGADORES========================

    //==============CAMPEONATO========================
    Route::get('/campeonato', CampeonatoIndex::class)->name('campeonato.index');
    Route::middleware(['permission:comision|administrador'])->group(function () {
        Route::get('/campeonato/crear', CampeonatoCrear::class)->name('campeonato.crear');
        Route::get('/campeonato/{campeonatoId}/editar', CampeonatoEditar::class)->name('campeonato.editar');
    });
    //==============FIN CAMPEONATO========================
    //==============CATEGORIA========================
    Route::get('/categoria', \App\Livewire\Categoria\CategoriaIndex::class)->name('categoria.index');
    Route::middleware(['permission:comision|administrador'])->group(function () {
        Route::get('/categoria/crear', CategoriaCrear::class)->name('categoria.crear');
        Route::get('/categoria/{categoriaId}/editar', CategoriaEdit::class)->name('categoria.editar');
    });
    //==============FIN CATEGORIA========================  
    //==============FIN ESTADIOS======================== 
    Route::get('/estadios', CanchasIndex::class)->name('canchas.index');
    Route::middleware(['permission:comision|administrador'])->group(function () {
        Route::get('/estadios/crear', CanchasCrear::class)->name('canchas.crear');
        Route::get('/estadios/{estadioId}/editar', CanchasEditar::class)->name('canchas.editar');
    });
    //==============FIN ESTADIOS========================
    //==============FIXTURE======================== 
    Route::get('/fixture', FixtureIndex::class)->name('fixture.index');
    Route::get('/fixture/{campeonatoId}/ver', FixtureVer::class)->name('fixture.ver');
    Route::middleware(['permission:comision|administrador'])->group(function () {
        Route::get('/fixture/{campeonatoId}/crear', FixtureCrear::class)->name('fixture.crear');
        Route::get('/fixture/{estadioId}/editar', FixtureEditar::class)->name('fixture.editar');
        Route::get('/fixture/{campeonatoId}/eliminatorias', Eliminatoria::class)->name('fixture.eliminatoria');
        Route::get('/fixture/{campeonatoId}/eliminatorias/editar', EliminatoriaVer::class)->name('fixture.eliminatoria.ver');
    });
    Route::middleware(['permission:comision|administrador'])->group(function () {
        Route::get('/fixture/automatico', \App\Livewire\Fixture\FixtureAutomatico::class)->name('fixture.automatico');
    });
    //==============FIN FIXTURE========================
    //==============ASIGNAR EQUIPOS========================
    Route::middleware(['permission:comision|administrador'])->group(function () {
        Route::get('/campeonato/{campeonatoId}/asignar-equipos', AsignarEquipos::class)->name('asignar-equipos');
    });
    //==============FIN ASIGNAR EQUIPOS========================
    //==============TABLA DE POSICIONES========================
    Route::get('/tabla-posiciones', TablaPosicionIndex::class)->name('tabla-posiciones');
    Route::get('/tabla-posiciones/{campeonatoId}/ver', \App\Livewire\TablaPosicion\TablaPosiciones::class)->name('tabla-posiciones.ver');
    Route::middleware(['permission:comision|administrador'])->group(function () {
        Route::get('/tabla-posiciones/{campeonato}/tabla-pdf', [TablaPosiciones::class, 'generarTablaPosicionesPDF'])->name('tabla.pdf');
        Route::get('/exportar-encuentros', [FixtureCrear::class, 'exportar'])->name('encuentros.exportar');
    });
    //==============FIN TABLA DE POSICIONES========================

    //==============SANCIONES========================
    Route::get('/sanciones', SancionesIndex::class)->name('sanciones.index');
    Route::middleware(['permission:comision|administrador'])->group(function () {
        Route::get('/sanciones/{campeonatoId}/crear', \App\Livewire\Sanciones\SancionesCrear::class)->name('sanciones.crear');
        Route::get('/sanciones/actualizar-cumplimientos', [\App\Livewire\Sanciones\SancionesCrear::class, 'actualizarCumplimientosSanciones'])->name('sanciones.actualizar-cumplimientos');
    });
    //==============FIN SANCIONES========================
    //==============ESTADISTICA========================
    Route::get('/estadistica', \App\Livewire\Estadistica\EstadisticaIndex::class)->name('estadistica.index');
    Route::middleware(['permission:comision|administrador'])->group(function () {
        Route::get('/estadistica/{campeonatoId}/ver', \App\Livewire\Estadistica\EstadisticaVer::class)->name('estadistica.ver');
    });
    //==============FIN ESTADISTICA========================

    //==============ALTAS Y BAJAS========================
    Route::middleware(['permission:comision|administrador'])->group(function () {
        Route::get('/altas-bajas', \App\Livewire\AltasBajas\AltasBajasIndex::class)->name('altas-bajas.index');
    });
});

require __DIR__ . '/auth.php';
