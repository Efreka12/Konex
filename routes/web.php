<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'landing'])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/registro', [AuthController::class, 'registroForm'])->name('registro');
    Route::post('/registro', [AuthController::class, 'registro'])->name('registro.store');
});

Route::get('/recuperar', [PageController::class, 'recuperar'])->name('recuperar');
Route::get('/restaurar', [PageController::class, 'restaurar'])->name('restaurar');

Route::middleware('auth')->group(function () {
    Route::get('/inicio', [PageController::class, 'inicio'])->name('inicio');
    Route::get('/grupos', [PageController::class, 'grupos'])->name('grupos');
    Route::get('/grupos/unirse', [PageController::class, 'unirseGrupo'])->name('grupos.unirse');
    Route::post('/grupos/unirse', [PageController::class, 'guardarUnirseGrupo'])->name('grupos.unirse.store');
    Route::get('/recursos', [PageController::class, 'recursos'])->name('recursos');
    Route::get('/recursos/crear', [PageController::class, 'crearRecurso'])->name('recursos.crear');
    Route::post('/recursos', [PageController::class, 'guardarRecurso'])->name('recursos.store');
    Route::get('/publicaciones/crear', [PageController::class, 'crearPublicacion'])->name('publicaciones.crear');
    Route::post('/publicaciones', [PageController::class, 'guardarPublicacion'])->name('publicaciones.store');
    Route::get('/chat', [PageController::class, 'chat'])->name('chat');
    Route::post('/chat', [PageController::class, 'guardarMensaje'])->name('chat.store');
    Route::get('/perfil', [PageController::class, 'perfil'])->name('perfil');
    Route::get('/perfil/editar', [PageController::class, 'editarPerfil'])->name('perfil.editar');
    Route::post('/perfil', [PageController::class, 'guardarPerfil'])->name('perfil.store');
    Route::get('/configuracion', [PageController::class, 'configuracion'])->name('configuracion');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
