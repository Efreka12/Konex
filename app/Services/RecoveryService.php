<?php

namespace App\Services;

use App\Models\ArchivoRecuperacion;
use App\Models\CodigoOtp;
use App\Models\CodigoRecuperacion;
use App\Models\EventoRecuperacion;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RecoveryService
{
    public const MENSAJE_OTP = 'Si el correo está en Konex y la cuenta está activa, enviamos un código. Ábrelo en el buzón de Konex. El código vence en 10 minutos.';

    public function __construct(
        private SecretHasher $hasher,
        private RecoveryMailer $mailer,
    ) {}

    public function cuentaActiva(?Usuario $usuario): bool
    {
        return $usuario !== null && (bool) $usuario->activo;
    }

    public function emitirOtp(Usuario $usuario, Request $request): string
    {
        CodigoOtp::query()
            ->where('id_usuario', $usuario->id_usuario)
            ->whereNull('usado_en')
            ->whereNull('anulado_en')
            ->update(['anulado_en' => now()]);

        $codigo = $this->hasher->otp();

        CodigoOtp::query()->create([
            'id_usuario' => $usuario->id_usuario,
            'codigo_hmac' => $this->hasher->hmac($codigo),
            'vence_en' => now()->addMinutes(10),
            'intentos' => 0,
            'max_intentos' => 5,
            'created_at' => now(),
        ]);

        $this->auditar($usuario->id_usuario, 'otp_solicitado', $request);
        $this->mailer->enviarOtp($usuario->correo_institucional, $codigo);

        return $codigo;
    }

    public function verificarOtp(Usuario $usuario, string $entrada, Request $request): bool
    {
        $digitos = $this->hasher->normalizarCodigo($entrada);
        $otp = CodigoOtp::query()
            ->where('id_usuario', $usuario->id_usuario)
            ->whereNull('usado_en')
            ->whereNull('anulado_en')
            ->latest('id')
            ->first();

        if (! $otp || ! $otp->vigente()) {
            $this->auditar($usuario->id_usuario, 'otp_fallido', $request);

            return false;
        }

        if ($otp->intentos >= $otp->max_intentos) {
            $otp->update(['anulado_en' => now()]);
            $this->auditar($usuario->id_usuario, 'otp_fallido', $request);

            return false;
        }

        if (strlen($digitos) !== 8 || ! $this->hasher->check($digitos, $otp->codigo_hmac)) {
            $otp->increment('intentos');
            $this->auditar($usuario->id_usuario, 'otp_fallido', $request);

            return false;
        }

        $otp->update(['usado_en' => now()]);
        $this->auditar($usuario->id_usuario, 'otp_verificado', $request);

        return true;
    }

    /**
     * @return list<string>
     */
    public function generarCodigos(Usuario $usuario, Request $request): array
    {
        CodigoRecuperacion::query()
            ->where('id_usuario', $usuario->id_usuario)
            ->whereNull('usado_en')
            ->whereNull('anulado_en')
            ->update(['anulado_en' => now()]);

        $lote = (string) Str::uuid();
        $visibles = [];

        for ($i = 0; $i < 8; $i++) {
            $digitos = $this->hasher->otp();
            CodigoRecuperacion::query()->create([
                'id_usuario' => $usuario->id_usuario,
                'lote' => $lote,
                'codigo_hmac' => $this->hasher->hmac($digitos),
                'created_at' => now(),
            ]);
            $visibles[] = $this->hasher->formatearCodigo($digitos);
        }

        $this->auditar($usuario->id_usuario, 'codigos_generados', $request);

        return $visibles;
    }

    public function verificarCodigo(Usuario $usuario, string $entrada, Request $request): ?CodigoRecuperacion
    {
        $digitos = $this->hasher->normalizarCodigo($entrada);

        if (strlen($digitos) !== 8) {
            $this->auditar($usuario->id_usuario, 'codigo_fallido', $request);

            return null;
        }

        $candidatos = CodigoRecuperacion::query()
            ->where('id_usuario', $usuario->id_usuario)
            ->whereNull('usado_en')
            ->whereNull('anulado_en')
            ->get();

        foreach ($candidatos as $codigo) {
            if ($this->hasher->check($digitos, $codigo->codigo_hmac)) {
                $codigo->update(['usado_en' => now()]);
                $this->auditar($usuario->id_usuario, 'codigo_usado', $request);

                return $codigo;
            }
        }

        $this->auditar($usuario->id_usuario, 'codigo_fallido', $request);

        return null;
    }

    public function emitirArchivo(Usuario $usuario, Request $request): string
    {
        $bytes = random_bytes(32);

        ArchivoRecuperacion::query()->updateOrCreate(
            ['id_usuario' => $usuario->id_usuario],
            ['secreto_hmac' => $this->hasher->hmacBytes($bytes)],
        );

        $this->auditar($usuario->id_usuario, 'archivo_emitido', $request);

        return $bytes;
    }

    public function verificarArchivo(Usuario $usuario, string $bytes, Request $request): bool
    {
        $archivo = ArchivoRecuperacion::query()
            ->where('id_usuario', $usuario->id_usuario)
            ->first();

        if (! $archivo || strlen($bytes) !== 32 || ! $this->hasher->checkBytes($bytes, $archivo->secreto_hmac)) {
            $this->auditar($usuario->id_usuario, 'archivo_fallido', $request);

            return false;
        }

        $this->auditar($usuario->id_usuario, 'archivo_confirmado', $request);

        return true;
    }

    public function auditar(?int $idUsuario, string $evento, Request $request): void
    {
        EventoRecuperacion::query()->create([
            'id_usuario' => $idUsuario,
            'evento' => $evento,
            'ip' => $request->ip(),
            'created_at' => now(),
        ]);
    }
}
