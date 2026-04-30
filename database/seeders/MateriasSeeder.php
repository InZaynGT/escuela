<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\GradoSeccion;

class MateriasSeeder extends Seeder
{
    public function run()
    {
        $materiasBase = [
            'Matemática',
            'Comunicación y Lenguaje',
            'Ciencias Naturales',
            'Estudios Sociales',
            'Formación Ciudadana',
            'Educación Física',
            'Expresión Artística',
            'Productividad y Desarrollo',
            'Tecnología de la Información',
            'Inglés',
            'Lectura',
            'Escritura',
        ];

        $gradoSecciones = GradoSeccion::with(['grado', 'seccion'])->get();

        foreach ($gradoSecciones as $gs) {

            $nombreGrado = strtolower($gs->grado->nombre);

            // Solo aplicar a 1ro, 2do y 3ro
            if (!in_array($nombreGrado, ['primero', 'segundo', 'tercero'])) {
                continue;
            }

            foreach ($materiasBase as $materia) {
                DB::table('materias')->insert([
                    'nombre' => $materia,
                    'id_grado_seccion' => $gs->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
