<?php

namespace App\Http\Controllers;

use App\Models\BuzonMensaje;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BuzonController extends Controller
{
    public function index(Request $request): View
    {
        $correo = $this->correoPermitido($request);
        abort_unless($correo, 403, 'Inicia una recuperación para consultar tu código.');

        $busqueda = trim((string) $request->query('q', ''));

        $consulta = BuzonMensaje::query()->where('correo', $correo)->latest('id');

        if ($busqueda !== '') {
            $consulta->where(function ($q) use ($busqueda) {
                $q->where('asunto', 'like', '%'.$busqueda.'%')
                    ->orWhere('codigo', 'like', '%'.$busqueda.'%');
            });
        }

        $mensajes = $consulta->limit(50)->get();
        $total = BuzonMensaje::query()->where('correo', $correo)->count();
        $sinLeer = BuzonMensaje::query()->where('correo', $correo)->where('leido', false)->count();

        $abierto = null;
        $id = $request->query('mensaje');
        if (is_numeric($id)) {
            $abierto = $mensajes->firstWhere('id', (int) $id)
                ?? BuzonMensaje::query()->where('correo', $correo)->find((int) $id);
            if ($abierto && ! $abierto->leido) {
                $abierto->update(['leido' => true]);
                $sinLeer = max(0, $sinLeer - 1);
            }
        }

        return view('buzon.index', [
            'mensajes' => $mensajes,
            'abierto' => $abierto,
            'busqueda' => $busqueda,
            'total' => $total,
            'sinLeer' => $sinLeer,
        ]);
    }

    public function marcarLeidos(Request $request): RedirectResponse
    {
        $correo = $this->correoPermitido($request);
        abort_unless($correo, 403);
        BuzonMensaje::query()->where('correo', $correo)->where('leido', false)->update(['leido' => true]);

        return back();
    }

    public function vaciar(Request $request): RedirectResponse
    {
        $correo = $this->correoPermitido($request);
        abort_unless($correo, 403);
        BuzonMensaje::query()->where('correo', $correo)->delete();

        return redirect()->route('buzon');
    }

    private function correoPermitido(Request $request): ?string
    {
        $correo = $request->user()?->correo_institucional
            ?? $request->session()->get('recovery.correo');

        return is_string($correo) && $correo !== '' ? mb_strtolower($correo) : null;
    }
}
