<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Services\RecoveryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RecoveryController extends Controller
{
    public function __construct(private RecoveryService $recovery) {}

    public function index(): View
    {
        return view('auth.recuperar');
    }

    public function correoForm(): View
    {
        return view('auth.recuperar-correo');
    }

    public function pedirOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'correo_institucional' => 'required|email|confirmed',
        ]);

        $correo = Str::lower($validated['correo_institucional']);
        $limiteActivo = ! app()->environment('local');
        $ipLimitada = $limiteActivo && RateLimiter::tooManyAttempts('rec-otp-ip:'.$request->ip(), 5);
        $correoLimitado = $limiteActivo && RateLimiter::tooManyAttempts('rec-otp-mail:'.sha1($correo), 3);

        $usuario = Usuario::query()
            ->whereRaw('LOWER(correo_institucional) = ?', [$correo])
            ->first();

        if (! $ipLimitada && ! $correoLimitado && $this->recovery->cuentaActiva($usuario)) {
            $this->recovery->emitirOtp($usuario, $request);
        }

        if ($limiteActivo) {
            $this->limitarSolicitud($request, $correo);
        }

        $request->session()->put('recovery.correo', $correo);

        return redirect()->route('recuperar.otp')->with('ok', RecoveryService::MENSAJE_OTP);
    }

    public function otpForm(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('recovery.correo')) {
            return redirect()->route('recuperar.correo');
        }

        return view('auth.recuperar-otp', [
            'correo' => $request->session()->get('recovery.correo'),
        ]);
    }

    public function confirmarOtp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'codigo' => 'required|string|max:20',
        ]);

        $usuario = $this->usuarioDeSesion($request);
        if (! $usuario) {
            return back()->withErrors(['codigo' => 'El código no es válido o ya venció.']);
        }

        if ($this->recovery->verificarOtp($usuario, $validated['codigo'], $request)) {
            $this->marcarListo($request, 'otp');

            return redirect()->route('recuperar.clave');
        }

        return back()->withErrors(['codigo' => 'El código no es válido o ya venció.']);
    }

    public function archivoForm(): View
    {
        return view('auth.recuperar-archivo');
    }

    public function confirmarArchivo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'correo_institucional' => 'required|email',
            'archivo' => 'required|file|max:16',
        ]);

        $usuario = Usuario::query()
            ->where('correo_institucional', Str::lower($validated['correo_institucional']))
            ->first();

        $bytes = (string) $request->file('archivo')?->get();

        if ($this->recovery->cuentaActiva($usuario) && $this->recovery->verificarArchivo($usuario, $bytes, $request)) {
            $request->session()->put('recovery.correo', Str::lower($validated['correo_institucional']));
            $this->marcarListo($request, 'archivo');

            return redirect()->route('recuperar.clave');
        }

        if (! $usuario) {
            $this->recovery->auditar(null, 'archivo_fallido', $request);
        }

        return back()->withErrors(['archivo' => 'El correo y el archivo no coinciden.']);
    }

    public function claveForm(Request $request): View|RedirectResponse
    {
        if (! $request->session()->get('recovery.listo')) {
            return redirect()->route('recuperar');
        }

        return view('auth.restaurar');
    }

    public function guardarClave(Request $request): RedirectResponse
    {
        if (! $request->session()->get('recovery.listo')) {
            return redirect()->route('recuperar');
        }

        $validated = $request->validate([
            'contrasena' => 'required|string|min:8|confirmed',
        ]);

        $usuario = $this->usuarioDeSesion($request);
        if (! $usuario) {
            return redirect()->route('recuperar')->withErrors(['correo_institucional' => 'La sesión de recuperación expiró.']);
        }

        $usuario->update([
            'contrasena' => Hash::make($validated['contrasena']),
        ]);

        $this->recovery->auditar($usuario->id_usuario, 'contrasena_restablecida', $request);
        $request->session()->forget(['recovery.correo', 'recovery.listo', 'recovery.metodo', 'recovery.codigo_id']);

        return redirect()->route('login')->with('ok', 'La contraseña se actualizó. Ya puedes entrar.');
    }

    public function descargarArchivo(Request $request): StreamedResponse
    {
        $bytes = $this->recovery->emitirArchivo($request->user(), $request);

        return $this->descargaBinaria($bytes);
    }

    private function usuarioDeSesion(Request $request): ?Usuario
    {
        $correo = $request->session()->get('recovery.correo');
        if (! is_string($correo)) {
            return null;
        }

        $usuario = Usuario::query()->where('correo_institucional', $correo)->first();

        return $this->recovery->cuentaActiva($usuario) ? $usuario : null;
    }

    private function marcarListo(Request $request, string $metodo): void
    {
        $request->session()->put('recovery.listo', true);
        $request->session()->put('recovery.metodo', $metodo);
        $request->session()->regenerate();
    }

    private function limitarSolicitud(Request $request, string $correo): void
    {
        RateLimiter::hit('rec-otp-ip:'.$request->ip(), 900);
        RateLimiter::hit('rec-otp-mail:'.sha1($correo), 900);
    }

    private function descargaBinaria(string $bytes): StreamedResponse
    {
        return response()->streamDownload(function () use ($bytes) {
            echo $bytes;
        }, 'konex-recuperacion.bin', [
            'Content-Type' => 'application/octet-stream',
            'Content-Length' => '32',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
