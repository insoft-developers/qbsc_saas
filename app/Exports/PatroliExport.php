<?php

namespace App\Exports;

use App\Models\Patroli;
use App\Traits\CommonTrait;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class PatroliExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithStyles,
    WithDrawings
{
    use CommonTrait;

    protected $start;
    protected $end;
    protected $satpam_id;
    protected $location_id;

    public function __construct($start = null, $end = null, $satpam_id = null, $location_id = null)
    {
        $this->start = $start;
        $this->end = $end;
        $this->satpam_id = $satpam_id;
        $this->location_id = $location_id;
    }

    /**
     * ======================
     * DATA UTAMA (TEXT)
     * ======================
     */
    public function collection()
    {
        $query = Patroli::select([
                'id',
                'tanggal',
                'jam',
                'jam_awal_patroli',
                'jam_akhir_patroli',
                'location_id',
                'satpam_id',
                'latitude',
                'longitude',
                'note',
                'photo_path',
                'comid',
                'created_at'
            ])
            ->where('comid', $this->comid())
            ->with([
                'satpam:id,name',
                'company:id,company_name',
                'lokasi:id,nama_lokasi'
            ]);

        if ($this->start && $this->end) {
            $query->whereBetween('tanggal', [$this->start, $this->end]);
        }

        if ($this->satpam_id) {
            $query->where('satpam_id', $this->satpam_id);
        }

        if ($this->location_id) {
            $query->where('location_id', $this->location_id);
        }

        return $query->get()->map(function ($row) {

            $isInRange = $this->jamDalamRange(
                $row->jam,
                $row->jam_awal_patroli,
                $row->jam_akhir_patroli
            );

            return [
                'Tanggal'       => date('d-m-Y', strtotime($row->tanggal)),
                'Jam'           => $row->jam,
                'Jam Patroli'   => $row->jam_awal_patroli . ' - ' . $row->jam_akhir_patroli,
                'Lokasi'        => $row->lokasi->nama_lokasi ?? '-',
                'Nama Satpam'   => optional($row->satpam)->name ?? '-',
                'Latitude'      => $row->latitude ?? '-',
                'Longitude'     => $row->longitude ?? '-',
                'Catatan'       => $row->note ?? '-',
                'Sync Date'     => date('d-m-Y H:i', strtotime($row->created_at)),
                'Perusahaan'    => $row->company->company_name ?? '',
                'Foto'          => '', // placeholder FOTO
                '_out'          => !$isInRange,
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
            'Jam Patroli',
            'Lokasi',
            'Nama Satpam',
            'Latitude',
            'Longitude',
            'Catatan',
            'Sync Date',
            'Perusahaan',
            'Foto',
            '_out'
        ];
    }

    /**
     * ======================
     * STYLE (TEXT MERAH)
     * ======================
     */
    public function styles(Worksheet $sheet)
    {
        $rowNum = 2;

        foreach ($this->collection() as $row) {
            if ($row['_out']) {
                $sheet->getStyle("B{$rowNum}")->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => 'FF0000'],
                        'bold' => true,
                    ],
                ]);
            }
            $rowNum++;
        }

        // RAPIKAN FOTO
        $sheet->getColumnDimension('K')->setWidth(22);
        $sheet->getDefaultRowDimension()->setRowHeight(60);
    }

    /**
     * ======================
     * FOTO (DRAWING)
     * ======================
     */
    public function drawings()
    {
        $drawings = [];

        $rows = Patroli::where('comid', $this->comid())
            ->when($this->start && $this->end, function ($q) {
                $q->whereBetween('tanggal', [$this->start, $this->end]);
            })
            ->when($this->satpam_id, fn ($q) => $q->where('satpam_id', $this->satpam_id))
            ->when($this->location_id, fn ($q) => $q->where('location_id', $this->location_id))
            ->get();

        $rowIndex = 2;
        $fotoColumn = 'K';

        foreach ($rows as $row) {

            if (!$row->photo_path) {
                $rowIndex++;
                continue;
            }

            $path = public_path('storage/'.$row->photo_path);

            if (!file_exists($path)) {
                $rowIndex++;
                continue;
            }

            $drawing = new Drawing();
            $drawing->setName('Foto Patroli');
            $drawing->setDescription('Foto Patroli');
            $drawing->setPath($path);
            $drawing->setHeight(70);
            $drawing->setCoordinates($fotoColumn . $rowIndex);
            $drawing->setOffsetX(10);
            $drawing->setOffsetY(5);

            $drawings[] = $drawing;
            $rowIndex++;
        }

        return $drawings;
    }
}
