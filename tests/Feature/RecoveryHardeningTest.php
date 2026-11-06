<?php

namespace Tests\Feature;

use App\Mail\CodigoRecuperacionMail;
use App\Models\ArchivoRecuperacion;
use App\Models\BuzonMensaje;
use App\Models\CodigoOtp;
use App\Models\CodigoRecuperacion;
use App\Models\EventoRecuperacion;
use App\Models\Usuario;
use App\Services\RecoveryService;
use App\Services\SecretHasher;
use Database\Seeders\KonexSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RecoveryHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_correo_desconocido_muestra_el_mismo_mensaje_y_no_crea_otp(): void
    {
        $this->seed(KonexSeeder::class);
        Mail::fake();

        $this->post('/recuperar/correo', [
            'correo_institucional' => 'nadie@uniespinal.edu.co',
            'correo_institucional_confirmation' => 'nadie@uniespinal.edu.co',
        ])->assertRedirect(route('recuperar.otp'))
            ->assertSessionHas('ok', RecoveryService::MENSAJE_OTP);

        Mail::assertNothingSent();
        $this->assertSame(0, CodigoOtp::query()->count());
        $this->assertSame(0, BuzonMensaje::query()->count());
    }

    public function test_otp_se_guarda_como_hmac_y_permite_cambiar_la_clave(): void
    {
        $this->seed(KonexSeeder::class);
        Mail::fake();

        $this->post('/recuperar/correo', [
            'correo_institucional' => 'ana.torres@uniespinal.edu.co',
            'correo_institucional_confirmation' => 'ana.torres@uniespinal.edu.co',
        ])->assertRedirect(route('recuperar.otp'));

        $codigo = null;
        Mail::assertSent(CodigoRecuperacionMail::class, function (CodigoRecuperacionMail $mail) use (&$codigo) {
            $codigo = $mail->codigo;

            return strlen($codigo) === 8 && ctype_digit($codigo);
        });

        $otp = CodigoOtp::query()->first();
        $this->assertSame($codigo, BuzonMensaje::query()->value('codigo'));
        $this->get('/buzon')->assertOk()->assertSee('Código de recuperación Konex');
        $this->get('/buzon?mensaje='.BuzonMensaje::query()->value('id'))
            ->assertOk()
            ->assertSee(substr($codigo, 0, 4).'-'.substr($codigo, 4, 4));
        $this->assertNotSame($codigo, $otp->codigo_hmac);
        $this->assertTrue(app(SecretHasher::class)->check($codigo, $otp->codigo_hmac));
        $this->assertFalse(
            EventoRecuperacion::query()->get()->contains(fn ($evento) => str_contains(json_encode($evento), (string) $codigo))
        );

        $this->post('/recuperar/otp', ['codigo' => $codigo])->assertRedirect(route('recuperar.clave'));

        $this->post('/recuperar/clave', [
            'contrasena' => 'claveNueva12',
            'contrasena_confirmation' => 'claveNueva12',
        ])->assertRedirect(route('login'));

        $ana = Usuario::query()->where('correo_institucional', 'ana.torres@uniespinal.edu.co')->first();
        $this->assertTrue(Hash::check('claveNueva12', $ana->contrasena));
        $this->assertNotNull($otp->fresh()->usado_en);
    }

    public function test_archivo_binario_es_de_32_bytes_y_no_se_gasta_al_cambiar_clave(): void
    {
        $this->seed(KonexSeeder::class);
        $ana = Usuario::query()->where('correo_institucional', 'ana.torres@uniespinal.edu.co')->first();
        $bytes = app(RecoveryService::class)->emitirArchivo($ana, Request::create('/'));

        $this->assertSame(32, strlen($bytes));
        $this->assertNotSame('{', $bytes[0]);
        $hmac = ArchivoRecuperacion::query()->where('id_usuario', $ana->id_usuario)->value('secreto_hmac');
        $this->assertTrue(app(SecretHasher::class)->checkBytes($bytes, $hmac));

        $this->post('/recuperar/archivo', [
            'correo_institucional' => 'ana.torres@uniespinal.edu.co',
            'archivo' => UploadedFile::fake()->createWithContent('otro.bin', random_bytes(32)),
        ])->assertSessionHasErrors('archivo');

        $this->post('/recuperar/archivo', [
            'correo_institucional' => 'ana.torres@uniespinal.edu.co',
            'archivo' => UploadedFile::fake()->createWithContent('konex-recuperacion.bin', $bytes),
        ])->assertRedirect(route('recuperar.clave'));

        $this->post('/recuperar/clave', [
            'contrasena' => 'otraClave99',
            'contrasena_confirmation' => 'otraClave99',
        ])->assertRedirect(route('login'));

        $this->assertSame($hmac, ArchivoRecuperacion::query()->where('id_usuario', $ana->id_usuario)->value('secreto_hmac'));

        $this->post('/recuperar/archivo', [
            'correo_institucional' => 'ana.torres@uniespinal.edu.co',
            'archivo' => UploadedFile::fake()->createWithContent('konex-recuperacion.bin', $bytes),
        ])->assertRedirect(route('recuperar.clave'));
    }

    public function test_codigos_de_recuperacion_se_muestran_una_vez_y_no_son_el_ejemplo(): void
    {
        $this->seed(KonexSeeder::class);

        $this->post('/registro', [
            'nombre_completo' => 'Luis Pérez',
            'correo_institucional' => 'luis.perez@uniespinal.edu.co',
            'id_rol' => 1,
            'contrasena' => 'password12',
            'contrasena_confirmation' => 'password12',
        ])->assertRedirect(route('inicio'));

        $this->assertSame(8, CodigoRecuperacion::query()->count());
        $this->assertFalse(
            CodigoRecuperacion::query()->get()->contains(fn ($row) => str_contains($row->codigo_hmac, '6871'))
        );

        $usuario = Usuario::query()->where('correo_institucional', 'luis.perez@uniespinal.edu.co')->first();
        $codigos = app(RecoveryService::class)->generarCodigos($usuario, Request::create('/'));
        $this->assertCount(8, $codigos);
        $this->assertNotContains('6871-2368', $codigos);
        $this->assertMatchesRegularExpression('/^\d{4}-\d{4}$/', $codigos[0]);
    }
}
