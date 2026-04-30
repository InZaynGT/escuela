<?php

namespace App\Imports;

use App\Models\Estudiante;
use App\Models\Inscripcion;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class EstudiantesImport implements ToCollection, WithHeadingRow
{
    public int   $importados = 0;
    public int   $omitidos   = 0;
    public array $advertencias = [];

    public function __construct(
        private int $idGradoSeccion,
        private int $anio
    ) {}

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $nombre    = trim((string) ($row['nombre']    ?? ''));
            $apellidos = trim((string) ($row['apellidos'] ?? ''));
            $cui       = $this->limpia($row['cui'] ?? null);

            if ($nombre === '' || $apellidos === '' || $cui === null) {
                $this->omitidos++;
                continue;
            }

            $telefono = $this->limpia($row['telefono'] ?? null);
            $fnac     = $this->parsearFecha($row['fecha_nacimiento'] ?? null);

            // Reusar estudiante existente por CUI o crear uno nuevo
            $estudiante = Estudiante::where('cui', $cui)->first()
                ?? Estudiante::create([
                    'nombre'           => $nombre,
                    'apellidos'        => $apellidos,
                    'cui'              => $cui,
                    'telefono'         => $telefono,
                    'fecha_nacimiento' => $fnac,
                ]);

            // Crear cuenta de acceso si no tiene
            if (!$estudiante->id_usuario) {
                $email = User::generarEmailEstudiante($estudiante->nombre, $estudiante->apellidos);
                $user  = User::create([
                    'name'     => $estudiante->nombre_completo,
                    'email'    => $email,
                    'password' => bcrypt($cui),
                    'rol'      => 'estudiante',
                ]);
                $estudiante->update(['id_usuario' => $user->id]);
            }

            // Verificar si ya está inscrito en este año
            $yaInscrito = Inscripcion::where('id_estudiante', $estudiante->id)
                ->where('anio', $this->anio)
                ->where('estado', 'activo')
                ->exists();

            if ($yaInscrito) {
                $this->advertencias[] = "{$estudiante->nombre_completo} ya tenía inscripción activa para {$this->anio}.";
                $this->omitidos++;
                continue;
            }

            // Desactivar inscripciones activas anteriores del mismo año
            Inscripcion::where('id_estudiante', $estudiante->id)
                ->where('anio', $this->anio)
                ->update(['estado' => 'inactivo']);

            Inscripcion::create([
                'id_estudiante'    => $estudiante->id,
                'id_grado_seccion' => $this->idGradoSeccion,
                'anio'             => $this->anio,
                'estado'           => 'activo',
            ]);

            $this->importados++;
        }
    }

    private function limpia($valor): ?string
    {
        $v = trim((string) ($valor ?? ''));
        return $v !== '' ? $v : null;
    }

    private function parsearFecha($valor): ?string
    {
        $v = trim((string) ($valor ?? ''));
        if ($v === '') {
            return null;
        }

        try {
            return is_numeric($valor)
                ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $valor)->format('Y-m-d')
                : \Carbon\Carbon::parse($v)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
