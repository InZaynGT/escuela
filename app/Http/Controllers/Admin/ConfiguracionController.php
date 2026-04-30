<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Configuracion;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index()
    {
        return view('admin.configuracion.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'nombre_centro' => 'required|string|max:100',
            'logo'          => 'nullable|image|mimes:png,jpg,jpeg,svg|max:1024',
        ]);

        Configuracion::set('nombre_centro', trim($request->nombre_centro));

        if ($request->hasFile('logo')) {
            $ext    = $request->file('logo')->getClientOriginalExtension();
            $nombre = 'logo_escuela.' . $ext;
            $request->file('logo')->move(public_path('uploads'), $nombre);
            Configuracion::set('logo_img', 'uploads/' . $nombre);
        }

        if ($request->boolean('eliminar_logo')) {
            Configuracion::set('logo_img', null);
        }

        return redirect()->route('admin.configuracion.index')
            ->with('success', 'Configuración actualizada correctamente.');
    }
}
