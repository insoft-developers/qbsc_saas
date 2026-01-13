<?php

namespace App\Exports\Sheets;

use App\Models\KandangKipas;
use App\Traits\CommonTrait;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class KipasSheet implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithTitle,
    WithDrawings,
    WithEvents
{
    use CommonTrait;

    protected $start;
    protected $end;
    protected $satpam_id;
    protected $kandang_id;

    /** 🔴 SIMPAN DATA SEKALI (ANTI QUERY ULANG) */
    protected $rows;

    public function __construct($start = null, $end = null, $satpam_id = null, $kandang_id = null)
    {
        $this->start = $start;
        $this->end = $end;
        $this->satpam_id = $satpam_id;
        $this->kandang_id = $kandang_id;
    }

    /**
     * ======================
     * DATA TEXT
     * ======================
     */
    public function collection()
    {
        $this->rows = KandangKipas::select([
            'id',
            'tanggal',
            'jam',
            'kandang_id',
            'satpam_id',
            'kipas',
            'latitude',
            'longitude',
            'note',
            'foto',
            'comid',
            'created_at'
        ])
            ->where('comid', $this->comid())
            ->with(['satpam:id,name', 'company:id,company_name', 'kandang:id,name'])
            ->when($this->start && $this->end, fn($q) =>
                $q->whereBetween('tanggal', [$this->start, $this->end])
            )
            ->when($this->satpam_id, fn($q) =>
                $q->where('satpam_id', $this->satpam_id)
            )
            ->when($this->kandang_id, fn($q) =>
                $q->where('kandang_id', $this->kandang_id)
            )
            ->get();

        return $this->rows->map(function ($row) {
            return [
                'Tanggal' => date('d-m-Y', strtotime($row->tanggal)),
                'Jam' => $row->jam,
                'Kandang' => $row->kandang->name ?? '-',
                'Nama Satpam' => optional($row->satpam)->name ?? '-',
                'Kipas' => $row->kipas,
                'Latitude' => $row->latitude ?? '-',
                'Longitude' => $row->longitude ?? '-',
                'Catatan' => $row->note ?? '-',
                'Sync Date' => date('d-m-Y H:i', strtotime($row->created_at)),
                'Perusahaan' => $row->company->company_name ?? '-',
                'Foto' => '', // placeholder
            ];
        });
    }

    /**
     * ======================
     * HEADER
     * ======================
     */
    public function headings(): array
    {
        return [
            'Tanggal',
            'Jam',
            'Kandang',
            'Nama Satpam',
            'Kipas',
            'Latitude',
            'Longitude',
            'Catatan',
            'Sync Date',
            'Perusahaan',
            'Foto'
        ];
    }

    /**
     * ======================
     * JUDUL SHEET
     * ======================
     */
    public function title(): string
    {
        return 'Kipas';
    }

    /**
     * ======================
     * FOTO (DRAWING)
     * ======================
     */
    public function drawings()
    {
        $drawings = [];
        $rowIndex = 2; // header di row 1
        $fotoColumn = 'K';

        foreach ($this->rows as $row) {
            if (!$row->foto) {
                $rowIndex++;
                continue;
            }

            $path = public_path('storage/' . $row->foto);

            if (!file_exists($path)) {
                $rowIndex++;
                continue;
            }

            $drawing = new Drawing();
            $drawing->setName('Foto Kipas');
            $drawing->setDescription('Foto Monitoring Kipas');
            $drawing->setPath($path);
            $drawing->setHeight(75);
            $drawing->setCoordinates($fotoColumn . $rowIndex);
            $drawing->setOffsetX(10);
            $drawing->setOffsetY(5);

            $drawings[] = $drawing;
            $rowIndex++;
        }

        return $drawings;
    }

    /**
     * ======================
     * STYLE & SIZE
     * ======================
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                /** 🔹 Lebar kolom */
                $event->sheet->getColumnDimension('A')->setWidth(12);
                $event->sheet->getColumnDimension('B')->setWidth(8);
                $event->sheet->getColumnDimension('C')->setWidth(18);
                $event->sheet->getColumnDimension('D')->setWidth(20);
                $event->sheet->getColumnDimension('E')->setWidth(8);
                $event->sheet->getColumnDimension('F')->setWidth(12);
                $event->sheet->getColumnDimension('G')->setWidth(12);
                $event->sheet->getColumnDimension('H')->setWidth(20);
                $event->sheet->getColumnDimension('I')->setWidth(18);
                $event->sheet->getColumnDimension('J')->setWidth(22);
                $event->sheet->getColumnDimension('K')->setWidth(25); // FOTO

                /** 🔹 Tinggi baris mengikuti foto */
                $rowIndex = 2;

                foreach ($this->rows as $row) {
                    if ($row->foto) {
                        $event->sheet
                            ->getRowDimension($rowIndex)
                            ->setRowHeight(85);
                    }
                    $rowIndex++;
                }
            },
        ];
    }
}
