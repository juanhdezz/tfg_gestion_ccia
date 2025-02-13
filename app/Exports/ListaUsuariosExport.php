<?php

namespace App\Exports;

use App\Models\Usuario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class ListaUsuariosExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Usuario::select(
            'nombre', 
            'apellidos', 
            'nombre_abreviado', 
            'dni_pasaporte', 
            'correo', 
            'foto', 
            'id_despacho', 
            'telefono_despacho', 
            'telefono'
        )->get();
    }

    /**
     * Define los encabezados de las columnas en la exportación
     */
    public function headings(): array
    {
        return [
            'Nombre',
            'Apellidos',
            'Nombre Abreviado',
            'DNI/Pasaporte',
            'Correo Electrónico',
            'Foto',
            'ID Despacho',
            'Teléfono Despacho',
            'Teléfono Personal'
        ];
    }

    /**
     * Aplica estilos a las celdas del encabezado
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '4F81BD'],
                ],
            ],
        ];
    }

    /**
     * Ajusta automáticamente el tamaño de las columnas
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                foreach (range('A', 'I') as $columnID) {
                    $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}

