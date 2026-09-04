<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'landing'])->name('landing');
Route::get('/login', [PageController::class, 'login'])->name('login');
Route::get('/registro', [PageController::class, 'registro'])->name('registro');
Route::get('/recuperar', [PageController::class, 'recuperar'])->name('recuperar');
Route::get('/restaurar', [PageController::class, 'restaurar'])->name('restaurar');

Route::get('/inicio', [PageController::class, 'inicio'])->name('inicio');
Route::get('/grupos', [PageController::class, 'grupos'])->name('grupos');
Route::get('/grupos/unirse', [PageController::class, 'unirseGrupo'])->name('grupos.unirse');
Route::get('/recursos', [PageController::class, 'recursos'])->name('recursos');
Route::get('/recursos/crear', [PageController::class, 'crearRecurso'])->name('recursos.crear');
Route::get('/publicaciones/crear', [PageController::class, 'crearPublicacion'])->name('publicaciones.crear');
Route::get('/chat', [PageController::class, 'chat'])->name('chat');
Route::get('/perfil', [PageController::class, 'perfil'])->name('perfil');
Route::get('/perfil/editar', [PageController::class, 'editarPerfil'])->name('perfil.editar');
Route::get('/configuracion', [PageController::class, 'configuracion'])->name('configuracion');
