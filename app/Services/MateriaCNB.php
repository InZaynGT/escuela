<?php

namespace App\Services;

use App\Models\GradoSeccion;
use App\Models\Materia;

class MateriaCNB
{
    private const MATERIAS_POR_NIVEL = [
        'primero' => [
            'Área de Comunicación y Lenguaje L1',
            'Área de Comunicación y Lenguaje L2',
            'Área de Comunicación y Lenguaje L3 - Inglés',
            'Área de Matemáticas',
            'Área de Medio Social y Natural',
            'Área de Expresión Artística',
            'Área de Educación Física',
            'Área de Formación Ciudadana',
        ],
        'segundo' => [
            'Área de Comunicación y Lenguaje L1',
            'Área de Comunicación y Lenguaje L2',
            'Área de Comunicación y Lenguaje L3 - Inglés',
            'Área de Matemáticas',
            'Área de Medio Social y Natural',
            'Área de Expresión Artística',
            'Área de Educación Física',
            'Área de Formación Ciudadana',
        ],
        'tercero' => [
            'Área de Comunicación y Lenguaje L1',
            'Área de Comunicación y Lenguaje L2',
            'Área de Comunicación y Lenguaje L3 - Inglés',
            'Área de Matemáticas',
            'Área de Medio Social y Natural',
            'Área de Expresión Artística',
            'Área de Educación Física',
            'Área de Formación Ciudadana',
        ],
        'cuarto' => [
            'Área de Comunicación y Lenguaje L1',
            'Área de Comunicación y Lenguaje L2',
            'Área de Comunicación y Lenguaje L3 - Inglés',
            'Área de Matemáticas',
            'Área de Ciencias Naturales y Tecnología',
            'Área de Ciencias Sociales',
            'Área de Expresión Artística',
            'Área de Educación Física',
            'Área de Productividad y Desarrollo',
            'Área de Formación Ciudadana',
        ],
        'quinto' => [
            'Área de Comunicación y Lenguaje L1',
            'Área de Comunicación y Lenguaje L2',
            'Área de Comunicación y Lenguaje L3 - Inglés',
            'Área de Matemáticas',
            'Área de Ciencias Naturales y Tecnología',
            'Área de Ciencias Sociales',
            'Área de Expresión Artística',
            'Área de Educación Física',
            'Área de Productividad y Desarrollo',
            'Área de Formación Ciudadana',
        ],
        'sexto' => [
            'Área de Comunicación y Lenguaje L1',
            'Área de Comunicación y Lenguaje L2',
            'Área de Comunicación y Lenguaje L3 - Inglés',
            'Área de Matemáticas',
            'Área de Ciencias Naturales y Tecnología',
            'Área de Ciencias Sociales',
            'Área de Expresión Artística',
            'Área de Educación Física',
            'Área de Productividad y Desarrollo',
            'Área de Formación Ciudadana',
        ],
    ];

    public static function materiasPara(string $nombreGrado): array
    {
        $lower = mb_strtolower($nombreGrado);

        foreach (self::MATERIAS_POR_NIVEL as $clave => $materias) {
            if (strpos($lower, $clave) !== false) {
                return $materias;
            }
        }

        return [];
    }

    /**
     * Crea las materias CNB para un GradoSeccion recién creado.
     * Si ya tiene materias, no hace nada (idempotente).
     * Retorna la cantidad de materias creadas.
     */
    public static function seedParaGradoSeccion(GradoSeccion $gs): int
    {
        if ($gs->materias()->exists()) {
            return 0;
        }

        $gs->loadMissing('grado');

        $materias = self::materiasPara($gs->grado->nombre ?? '');

        foreach ($materias as $nombre) {
            Materia::create([
                'nombre'           => $nombre,
                'id_grado_seccion' => $gs->id,
            ]);
        }

        return count($materias);
    }
}
