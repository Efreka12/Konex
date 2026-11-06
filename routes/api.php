<?php

use App\Http\Controllers\Api\AsignaturaController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ComentarioController;
use App\Http\Controllers\Api\EventoController;
use App\Http\Controllers\Api\EventoCrudController;
use App\Http\Controllers\Api\GrupoEstudioController;
use App\Http\Controllers\Api\HoraSocialController;
use App\Http\Controllers\Api\MensajeController;
use App\Http\Controllers\Api\MiembroGrupoController;
use App\Http\Controllers\Api\NotificacionController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProgramaController;
use App\Http\Controllers\Api\ProgramaCrudController;
use App\Http\Controllers\Api\PublicacionController;
use App\Http\Controllers\Api\ReaccionController;
use App\Http\Controllers\Api\RecursoController;
use App\Http\Controllers\Api\RolController;
use App\Http\Controllers\Api\UsuarioAsignaturaController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\TaskController;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('eventos', EventoCrudController::class)->only(['index', 'show']);
Route::apiResource('programas', ProgramaCrudController::class)->only(['index', 'show']);
Route::apiResource('products', ProductController::class);

Route::post('/auth/register', [AuthApiController::class, 'register']);
Route::post('/auth/login', [AuthApiController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users/profile', [AuthApiController::class, 'profile']);
    Route::put('/users/update', [AuthApiController::class, 'update']);
});
Route::middleware(['auth:sanctum', 'role:Directivo'])->group(function () {
    Route::apiResource('eventos', EventoCrudController::class)->except(['index', 'show']);
    Route::apiResource('programas', ProgramaCrudController::class)->except(['index', 'show']);
});

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::post('/login', function (Request $request) {
        $validated = $request->validate([
            'correo_institucional' => 'required|email',
            'contrasena' => 'required|string',
        ]);

        $user = Usuario::query()
            ->where('correo_institucional', $validated['correo_institucional'])
            ->where('activo', true)
            ->first();

        if (! $user || ! Hash::check($validated['contrasena'], $user->contrasena)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        return response()->json([
            'token' => $user->createToken('api')->plainTextToken,
            'user' => $user,
        ]);
    })->name('login');

    $konex = [
        'roles' => RolController::class,
        'programas' => ProgramaController::class,
        'usuarios' => UsuarioController::class,
        'asignaturas' => AsignaturaController::class,
        'usuario_asignatura' => UsuarioAsignaturaController::class,
        'grupos_estudio' => GrupoEstudioController::class,
        'miembro_grupo' => MiembroGrupoController::class,
        'mensajes' => MensajeController::class,
        'recursos' => RecursoController::class,
        'publicaciones' => PublicacionController::class,
        'comentarios' => ComentarioController::class,
        'reacciones' => ReaccionController::class,
        'eventos' => EventoController::class,
        'horas_sociales' => HoraSocialController::class,
        'notificaciones' => NotificacionController::class,
    ];

    foreach ($konex as $uri => $controller) {
        Route::apiResource($uri, $controller)
            ->parameters([$uri => 'id'])
            ->where(['id' => '[0-9]+'])
            ->only(['index', 'show']);
    }

    Route::apiResource('tasks', TaskController::class)
        ->where(['task' => '[0-9]+'])
        ->only(['index', 'show']);

    Route::middleware('auth:sanctum')->group(function () use ($konex) {
        $rolesEscritura = [
            'roles' => 'Directivo',
            'programas' => 'Directivo',
            'usuarios' => 'Directivo',
            'asignaturas' => 'Directivo',
            'usuario_asignatura' => 'Directivo',
            'grupos_estudio' => 'Docente',
            'recursos' => 'Estudiante,Docente',
            'eventos' => 'Directivo',
            'horas_sociales' => 'Estudiante,Directivo',
            'notificaciones' => 'Directivo',
        ];

        foreach ($rolesEscritura as $uri => $roles) {
            Route::apiResource($uri, $konex[$uri])
                ->parameters([$uri => 'id'])
                ->where(['id' => '[0-9]+'])
                ->except(['index', 'show'])
                ->middleware('role:'.$roles);
        }

        foreach (['miembro_grupo', 'mensajes', 'publicaciones', 'comentarios', 'reacciones'] as $uri) {
            Route::apiResource($uri, $konex[$uri])
                ->parameters([$uri => 'id'])
                ->where(['id' => '[0-9]+'])
                ->except(['index', 'show']);
        }

        Route::apiResource('tasks', TaskController::class)
            ->where(['task' => '[0-9]+'])
            ->except(['index', 'show']);
    });
});
