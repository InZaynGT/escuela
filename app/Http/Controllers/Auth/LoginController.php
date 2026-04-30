<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request): array
    {
        $input = trim($request->get($this->username(), ''));
        // Si no tiene @ es un CUI de estudiante → convertir al formato interno
        $email = str_contains($input, '@') ? $input : $input . '@eorm.local';

        return [$this->username() => $email, 'password' => $request->get('password')];
    }

    protected function authenticated(Request $request, $user)
    {
        activity('Accesos')
            ->causedBy($user)
            ->withProperties(['ip' => $request->ip(), 'rol' => $user->rol])
            ->log('Inicio de sesión');

        $rutas = [
            'admin'      => 'admin.dashboard',
            'docente'    => 'teacher.dashboard',
            'estudiante' => 'student.dashboard',
        ];

        $ruta = $rutas[$user->rol] ?? null;

        return $ruta ? redirect()->route($ruta) : redirect('/dashboard');
    }
}
