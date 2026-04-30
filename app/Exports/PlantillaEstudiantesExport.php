<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class PlantillaEstudiantesExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    public function title(): string
    {
        return 'Estudiantes';
    }

    public function headings(): array
    {
        return ['Nombre', 'Apellidos', 'CUI', 'Fecha_Nacimiento', 'Telefono'];
    }

    public function array(): array
    {
        return [
            ['Juan Carlos',    'García López',      '3254789012345', '2010-05-15', '55551234'],
            ['María Fernanda', 'Hernández Pérez',   '2198765432109', '2011-03-22', ''],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 22,
            'B' => 24,
            'C' => 18,
            'D' => 20,
            'E' => 14,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Estilo de encabezados
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1a1a2e']],
        ]);

        // Estilo de filas de ejemplo (gris claro)
        $sheet->getStyle('A2:E3')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F5F5F5']],
        ]);

        // Nota explicativa en fila 5
        $sheet->setCellValue('A5', '* Nombre y Apellidos son obligatorios. CUI, Fecha_Nacimiento y Telefono son opcionales.');
        $sheet->mergeCells('A5:E5');
        $sheet->getStyle('A5')->applyFromArray([
            'font'      => ['italic' => true, 'color' => ['rgb' => '888888'], 'size' => 9],
        ]);

        return [];
    }
}
