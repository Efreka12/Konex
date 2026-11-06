<?php

namespace Tests\Feature;

use App\Models\BuzonMensaje;
use App\Models\HoraSocial;
use App\Models\Publicacion;
use App\Models\Recurso;
use App\Models\Usuario;
use Database\Seeders\KonexSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class KonexClosureFlowsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        $this->seed(KonexSeeder::class);
    }

    public function test_docente_crea_grupo_y_estudiante_solo_participa(): void
    {
        $ana = $this->usuario('ana.torres@uniespinal.edu.co');
        $marta = $this->usuario('marta.ramirez@uniespinal.edu.co');

        $this->actingAs($ana)->get('/grupos/crear')->assertForbidden();

        $this->actingAs($marta)->post('/grupos', [
            'id_asignatura' => 2,
            'nombre' => 'Acompañamiento de Algoritmos',
            'descripcion' => 'Preparación de talleres.',
            'cupo' => 15,
            'horario' => 'Viernes 2:00 p. m.',
        ])->assertRedirect(route('grupos'));

        $this->assertDatabaseHas('grupos_estudio', ['nombre' => 'Acompañamiento de Algoritmos']);
    }

    public function test_recurso_real_comentario_y_reaccion_funcionan(): void
    {
        $marta = $this->usuario('marta.ramirez@uniespinal.edu.co');
        $publicacion = Publicacion::query()->first();

        $this->actingAs($marta)->post('/recursos', [
            'titulo' => 'Taller de consultas SQL',
            'id_asignatura' => 3,
            'descripcion' => 'Ejercicios de consulta.',
            'tipo_archivo' => 'PDF',
            'archivo' => UploadedFile::fake()->create('taller.pdf', 20, 'application/pdf'),
        ])->assertRedirect(route('recursos'));

        $recurso = Recurso::query()->where('titulo', 'Taller de consultas SQL')->firstOrFail();
        Storage::disk('local')->assertExists($recurso->ruta_archivo);
        $this->actingAs($marta)->get(route('recursos.descargar', $recurso))->assertOk();

        $this->post(route('publicaciones.comentarios', $publicacion), ['contenido' => 'Material revisado.'])
            ->assertRedirect();
        $this->post(route('publicaciones.reaccion', $publicacion))->assertRedirect();
        $this->assertDatabaseHas('comentarios', ['contenido' => 'Material revisado.']);
        $this->assertDatabaseHas('reacciones', ['id_usuario' => $marta->id_usuario, 'tipo' => 'like']);
    }

    public function test_directivo_publica_y_gestiona_horas_sociales(): void
    {
        $ana = $this->usuario('ana.torres@uniespinal.edu.co');
        $carlos = $this->usuario('carlos.pena@uniespinal.edu.co');

        $this->actingAs($ana)->post('/institucional/eventos', [
            'titulo' => 'Evento no autorizado',
            'fecha' => now()->addDay()->format('Y-m-d H:i:s'),
            'lugar' => 'Auditorio',
        ])->assertForbidden();

        $this->actingAs($carlos)->post('/institucional/eventos', [
            'titulo' => 'Encuentro de egresados',
            'descripcion' => 'Agenda institucional.',
            'fecha' => now()->addWeek()->format('Y-m-d H:i:s'),
            'lugar' => 'Biblioteca',
        ])->assertRedirect();

        $this->actingAs($ana)->post('/horas-sociales', [
            'actividad' => 'Apoyo en biblioteca',
            'horas' => 4,
        ])->assertRedirect();

        $solicitud = HoraSocial::query()->where('actividad', 'Apoyo en biblioteca')->firstOrFail();
        $this->actingAs($carlos)->patch(route('horas.update', $solicitud), ['estado' => 'aprobado'])
            ->assertRedirect();
        $this->assertSame('aprobado', $solicitud->fresh()->estado);
        $this->assertSame($carlos->id_usuario, $solicitud->fresh()->id_directivo);
    }

    public function test_asistente_responde_solo_con_datos_de_konex(): void
    {
        $ana = $this->usuario('ana.torres@uniespinal.edu.co');

        $this->actingAs($ana)->postJson('/asistente/consultar', ['pregunta' => '¿Qué eventos hay?'])
            ->assertOk()
            ->assertJsonFragment(['tipo' => 'evento'])
            ->assertJsonPath('fuentes.0.titulo', 'Feria de prácticas');

        $this->postJson('/asistente/consultar', ['pregunta' => 'estado de mis horas sociales'])
            ->assertOk()
            ->assertJsonFragment(['tipo' => 'hora_social'])
            ->assertJsonFragment(['titulo' => 'Apoyo en inducción de primer semestre']);

        $this->postJson('/asistente/consultar', ['pregunta' => '¿Cuál es el precio de la matrícula?'])
            ->assertOk()
            ->assertJsonPath('fuentes', [])
            ->assertJsonPath('respuesta', 'No tengo ese dato en Konex. Puedo consultar eventos, noticias, recursos verificados y el estado de horas sociales.');
    }

    public function test_buzon_solo_muestra_el_destinatario_de_la_recuperacion(): void
    {
        $propio = BuzonMensaje::create([
            'correo' => 'ana.torres@uniespinal.edu.co',
            'asunto' => 'Código de recuperación Konex',
            'codigo' => '12345678',
            'cuerpo' => 'Código Ana',
        ]);
        BuzonMensaje::create([
            'correo' => 'otro@uniespinal.edu.co',
            'asunto' => 'Código de recuperación Konex',
            'codigo' => '87654321',
            'cuerpo' => 'Código ajeno',
        ]);

        $this->get('/buzon')->assertForbidden();
        $this->withSession(['recovery.correo' => 'ana.torres@uniespinal.edu.co'])
            ->get('/buzon?mensaje='.$propio->id)
            ->assertOk()
            ->assertSee('1234-5678')
            ->assertDontSee('8765-4321');
    }

    public function test_api_usa_usuarios_konex_y_respeta_el_rol(): void
    {
        $this->postJson('/api/v1/login', [
            'correo_institucional' => 'ana.torres@uniespinal.edu.co',
            'contrasena' => 'password',
        ])->assertOk()->assertJsonStructure(['token', 'user']);

        Sanctum::actingAs($this->usuario('ana.torres@uniespinal.edu.co'));
        $this->postJson('/api/programas', [
            'nombre' => 'Programa no autorizado',
            'facultad' => 'Prueba',
        ])->assertForbidden();
    }

    private function usuario(string $correo): Usuario
    {
        return Usuario::query()->where('correo_institucional', $correo)->firstOrFail();
    }
}
