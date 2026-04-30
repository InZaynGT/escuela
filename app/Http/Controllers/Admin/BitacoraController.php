<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class BitacoraController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = Activity::with('causer')->latest();

        if ($request->filled('modulo')) {
            $query->where('log_name', $request->modulo);
        }

        if ($request->filled('evento')) {
            $query->where('event', $request->evento);
        }

        if ($request->filled('id_usuario')) {
            $query->where('causer_type', User::class)
                  ->where('causer_id', $request->id_usuario);
        }

        if ($request->filled('desde')) {
            $query->whereDate('created_at', '>=', $request->desde);
        }

        if ($request->filled('hasta')) {
            $query->whereDate('created_at', '<=', $request->hasta);
        }

        $registros = $query->paginate(50)->withQueryString();

        $modulos  = Activity::distinct()->orderBy('log_name')->pluck('log_name');
        $usuarios = User::orderBy('name')->get(['id', 'name', 'rol']);

        return view('admin.bitacora.index', compact('registros', 'modulos', 'usuarios'));
    }
}
