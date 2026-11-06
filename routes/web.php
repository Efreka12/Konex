<?php

use App\Http\Controllers\AsistenteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuzonController;
use App\Http\Controllers\InstitucionalController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RecoveryController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'landing'])->name('landing');

Route::get('/buzon', [BuzonController::class, 'index'])->name('buzon');
Route::post('/buzon/leidos', [BuzonController::class, 'marcarLeidos'])->name('buzon.leidos');
Route::post('/buzon/vaciar', [BuzonController::class, 'vaciar'])->name('buzon.vaciar');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/registro', [AuthController::class, 'registroForm'])->name('registro');
    Route::post('/registro', [AuthController::class, 'registro'])->name('registro.store');

    Route::get('/recuperar', [RecoveryController::class, 'index'])->name('recuperar');
    Route::get('/recuperar/correo', [RecoveryController::class, 'correoForm'])->name('recuperar.correo');
    Route::post('/recuperar/correo', [RecoveryController::class, 'pedirOtp'])->name('recuperar.correo.store');
    Route::get('/recuperar/otp', [RecoveryController::class, 'otpForm'])->name('recuperar.otp');
    Route::post('/recuperar/otp', [RecoveryController::class, 'confirmarOtp'])->name('recuperar.otp.store');
    Route::get('/recuperar/archivo', [RecoveryController::class, 'archivoForm'])->name('recuperar.archivo');
    Route::post('/recuperar/archivo', [RecoveryController::class, 'confirmarArchivo'])->name('recuperar.archivo.store');
    Route::get('/recuperar/clave', [RecoveryController::class, 'claveForm'])->name('recuperar.clave');
    Route::post('/recuperar/clave', [RecoveryController::class, 'guardarClave'])->name('recuperar.clave.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/inicio', [PageController::class, 'inicio'])->name('inicio');
    Route::get('/grupos', [PageController::class, 'grupos'])->name('grupos');
    Route::get('/grupos/crear', [PageController::class, 'crearGrupo'])->middleware('role:Docente')->name('grupos.crear');
    Route::post('/grupos', [PageController::class, 'guardarGrupo'])->middleware('role:Docente')->name('grupos.store');
    Route::get('/grupos/unirse', [PageController::class, 'unirseGrupo'])->name('grupos.unirse');
    Route::post('/grupos/unirse', [PageController::class, 'guardarUnirseGrupo'])->name('grupos.unirse.store');
    Route::get('/recursos', [PageController::class, 'recursos'])->name('recursos');
    Route::get('/recursos/crear', [PageController::class, 'crearRecurso'])->name('recursos.crear');
    Route::post('/recursos', [PageController::class, 'guardarRecurso'])->name('recursos.store');
    Route::get('/recursos/{recurso}/descargar', [PageController::class, 'descargarRecurso'])->name('recursos.descargar');
    Route::get('/publicaciones/crear', [PageController::class, 'crearPublicacion'])->name('publicaciones.crear');
    Route::post('/publicaciones', [PageController::class, 'guardarPublicacion'])->name('publicaciones.store');
    Route::post('/publicaciones/{publicacion}/reaccion', [PageController::class, 'reaccionar'])->name('publicaciones.reaccion');
    Route::post('/publicaciones/{publicacion}/comentarios', [PageController::class, 'comentar'])->name('publicaciones.comentarios');
    Route::get('/chat', [PageController::class, 'chat'])->name('chat');
    Route::post('/chat', [PageController::class, 'guardarMensaje'])->name('chat.store');
    Route::get('/perfil', [PageController::class, 'perfil'])->name('perfil');
    Route::get('/perfil/editar', [PageController::class, 'editarPerfil'])->name('perfil.editar');
    Route::post('/perfil', [PageController::class, 'guardarPerfil'])->name('perfil.store');
    Route::get('/configuracion', [PageController::class, 'configuracion'])->name('configuracion');
    Route::get('/asistente', [AsistenteController::class, 'index'])->name('asistente');
    Route::post('/asistente/consultar', [AsistenteController::class, 'consultar'])->name('asistente.consultar');
    Route::get('/configuracion/archivo', [RecoveryController::class, 'descargarArchivo'])->name('configuracion.archivo');
    Route::get('/institucional', [InstitucionalController::class, 'index'])->name('institucional');
    Route::post('/institucional/eventos', [InstitucionalController::class, 'guardarEvento'])->middleware('role:Directivo')->name('institucional.eventos.store');
    Route::post('/institucional/noticias', [InstitucionalController::class, 'guardarNoticia'])->middleware('role:Directivo')->name('institucional.noticias.store');
    Route::get('/horas-sociales', [InstitucionalController::class, 'horas'])->middleware('role:Estudiante,Directivo')->name('horas.index');
    Route::post('/horas-sociales', [InstitucionalController::class, 'guardarHoras'])->middleware('role:Estudiante')->name('horas.store');
    Route::patch('/horas-sociales/{horaSocial}', [InstitucionalController::class, 'actualizarHoras'])->middleware('role:Directivo')->name('horas.update');
    Route::get('/horas-sociales/{horaSocial}/evidencia', [InstitucionalController::class, 'descargarEvidencia'])->middleware('role:Estudiante,Directivo')->name('horas.evidencia');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
