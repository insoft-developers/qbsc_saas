<?php

namespace App\Exports;

use App\Models\LaporanSituasi;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class SituasiExport implements FromCollection, WithHeadings, ShouldAutoSize, WithDrawings, WithEvents
{
    use CommonTrait;

    protected $start;
    protected $end;
    protected $satpam_id;

    public function __construct($start = null, $end = null, $satpam_id = null)
    {
        $this->start = $start;
        $this->end = $end;
        $this->satpam_id = $satpam_id;
    }

    /**
     * ======================
     * DATA TEXT
     * ======================
     */
    public function collection()
    {
        $query = LaporanSituasi::where('comid', $this->comid())
            ->with(['satpam:id,name', 'company:id,company_name']);

        if ($this->start && $this->end) {
            $start = Carbon::parse($this->start)->startOfDay();
            $end = Carbon::parse($this->end)->endOfDay();
            $query->whereBetween('tanggal', [$start, $end]);
        }

        if ($this->satpam_id) {
            $query->where('satpam_id', $this->satpam_id);
        }

        return $query->get()->map(function ($row) {
            return [
                'Nama Satpam' => optional($row->satpam)->name ?? '-',
                'Waktu' => date('d-m-Y', strtotime($row->tanggal)),
                'Laporan' => $row->laporan,
                'Perusahaan' => $row->company->company_name ?? '',
                'Foto' => '', // placeholder foto
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
        return ['Nama Satpam','Waktu','Laporan','Perusahaan','Foto'];
    }

    /**
     * ======================
     * FOTO (DRAWING)
     * ======================
     */
    public function drawings()
    {
        $drawings = [];

        $rows = LaporanSituasi::where('comid', $this->comid())
            ->when($this->start && $this->end, fn($q) => $q->whereBetween('tanggal', [$this->start, $this->end]))
            ->when($this->satpam_id, fn($q) => $q->where('satpam_id', $this->satpam_id))
            ->get();

        $rowIndex = 2; // baris header
        $fotoColumn = 'E'; // kolom Foto

        foreach ($rows as $row) {
            if (!$row->foto) { // sesuaikan field foto di table LaporanSituasi
                $rowIndex++;
                continue;
            }

            $path = public_path('storage/' . $row->foto);

            if (!file_exists($path)) {
                $rowIndex++;
                continue;
            }

            $drawing = new Drawing();
            $drawing->setName('Foto Situasi');
            $drawing->setDescription('Foto Laporan Situasi');
            $drawing->setPath($path);
            $drawing->setHeight(70);
            $drawing->setCoordinates($fotoColumn . $rowIndex);
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(5);

            $drawings[] = $drawing;
            $rowIndex++;
        }

        return $drawings;
    }

    /**
     * ======================
     * SETTING HEIGHT & WIDTH KOLM FOTO
     * ======================
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $row = 2;

                $rows = LaporanSituasi::where('comid', $this->comid())
                    ->when($this->start && $this->end, fn($q) => $q->whereBetween('tanggal', [$this->start, $this->end]))
                    ->when($this->satpam_id, fn($q) => $q->where('satpam_id', $this->satpam_id))
                    ->get();

                foreach ($rows as $r) {
                    if ($r->foto) {
                        $event->sheet->getRowDimension($row)->setRowHeight(80);
                    }
                    $row++;
                }

                // Lebar kolom foto
                $event->sheet->getColumnDimension('E')->setWidth(22);
            },
        ];
    }
}
