<?php

namespace Database\Seeders;

use App\Models\Grado;
use App\Models\GradoSeccion;
use App\Models\Seccion;
use Illuminate\Database\Seeder;

class EscuelaInicialSeeder extends Seeder
{
    public function run(): void
    {
        // Grados según CNB primaria
        $nombresGrados = [
            'Primero Primaria',
            'Segundo Primaria',
            'Tercero Primaria',
            'Cuarto Primaria',
            'Quinto Primaria',
            'Sexto Primaria',
        ];

        foreach ($nombresGrados as $nombre) {
            Grado::firstOrCreate(['nombre' => $nombre]);
        }

        // Sección A por defecto
        $seccionA = Seccion::firstOrCreate(['nombre' => 'A']);

        // Crear GradoSeccion para cada grado + sección A
        // El observer GradoSeccionObserver siembra las materias CNB automáticamente
        foreach (Grado::all() as $grado) {
            $existe = GradoSeccion::where('id_grado', $grado->id)
                ->where('id_seccion', $seccionA->id)
                ->exists();

            if (!$existe) {
                GradoSeccion::create([
                    'id_grado'   => $grado->id,
                    'id_seccion' => $seccionA->id,
                ]);
            }
        }
    }
}
