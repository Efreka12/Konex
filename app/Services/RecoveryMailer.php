<?php

namespace App\Services;

use App\Mail\CodigoRecuperacionMail;
use App\Models\BuzonMensaje;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RecoveryMailer
{
    public function enviarOtp(string $correo, string $codigo): void
    {
        $correo = Str::lower($correo);

        Log::info('Correo de recuperación despachado.', [
            'correo' => Str::mask($correo, '*', 3, 8),
        ]);

        BuzonMensaje::query()->create([
            'remitente' => 'Konex <no-reply@konex.local>',
            'correo' => $correo,
            'asunto' => 'Código de recuperación Konex',
            'codigo' => $codigo,
            'cuerpo' => "Tu código de verificación Konex es:\n{$codigo}\n\nVence en 10 minutos. Si no pediste este código, ignora este correo.",
            'leido' => false,
            'created_at' => now(),
        ]);

        if (config('mail.default') === 'log') {
            Mail::mailer('array')->to($correo)->send(new CodigoRecuperacionMail($codigo));
            File::ensureDirectoryExists(storage_path('app/private'));
            File::put(
                storage_path('app/private/otp-inbox.eml'),
                "Asunto: Código de recuperación Konex\n\nTu código de recuperación Konex es:\n{$codigo}\n\nVence en 10 minutos. Si no pediste este código, ignora este correo.\n"
            );

            return;
        }

        Mail::to($correo)->send(new CodigoRecuperacionMail($codigo));
    }
}
