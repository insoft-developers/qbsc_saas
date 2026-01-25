<?php

namespace App\Exports;

use App\Models\DocChick;
use App\Traits\CommonTrait;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class DocExport implements FromCollection, WithHeadings, ShouldAutoSize, WithDrawings, WithEvents
{
    use CommonTrait;

    protected $start;
    protected $end;
    protected $satpam_id;
    protected $ekspedisi_id;

    public function __construct($start = null, $end = null, $satpam_id = null, $ekspedisi_id = null)
    {
        $this->start = $start;
        $this->end = $end;
        $this->satpam_id = $satpam_id;
        $this->ekspedisi_id = $ekspedisi_id;
    }

    /**
     * ======================
     * DATA TEXT
     * ======================
     */
    public function collection()
    {
        $query = DocChick::where('comid', $this->comid())->with(['satpam:id,name', 'company:id,company_name', 'ekspedisi:id,name']);

        if ($this->start && $this->end) {
            $query->whereBetween('tanggal', [$this->start, $this->end]);
        }

        if ($this->satpam_id) {
            $query->where('satpam_id', $this->satpam_id);
        }

        if ($this->ekspedisi_id) {
            $query->where('ekspedisi_id', $this->ekspedisi_id);
        }

        return $query->get()->map(function ($row) {
            return [
                'Tanggal' => date('d-m-Y', strtotime($row->tanggal)),
                'Jam' => $row->jam,
                'Tgl Input' => date('d-m-Y H:i', strtotime($row->input_date)),
                'Nama Satpam' => optional($row->satpam)->name ?? '-',
                'Jumlah Box' => $row->jumlah,
                'Total Ekor' => $row->total_ekor,

                'Box Detail' => $this->formatBoxDetail($row->doc_box_option ?? null),

                'Ekspedisi' => optional($row->ekspedisi)->name ?? '-',
                'Nama Supir' => $row->nama_supir ?? '',
                'Tujuan' => $row->tujuan,
                'No Polisi' => $row->no_polisi,
                'Nomor Segel' => $row->nomor_segel ?? '',
                'Catatan' => $row->note ?? '-',
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
        return ['Tanggal', 'Jam', 'Tgl Input', 'Nama Satpam', 'Jumlah Box', 'Total Ekor', 'Box Detail', 'Ekspedisi', 'Nama Supir', 'Tujuan', 'No Polisi', 'Nomor Segel', 'Catatan', 'Perusahaan', 'Foto'];
    }

    /**
     * ======================
     * FOTO (DRAWING)
     * ======================
     */
    public function drawings()
    {
        $drawings = [];

        $rows = DocChick::where('comid', $this->comid())
            ->when($this->start && $this->end, fn($q) => $q->whereBetween('tanggal', [$this->start, $this->end]))
            ->when($this->satpam_id, fn($q) => $q->where('satpam_id', $this->satpam_id))
            ->when($this->ekspedisi_id, fn($q) => $q->where('ekspedisi_id', $this->ekspedisi_id))
            ->get();

        $rowIndex = 2; // baris header
        $fotoColumn = 'O'; // kolom foto

        foreach ($rows as $row) {
            if (!$row->foto) {
                $rowIndex++;
                continue;
            }

            $foto = $this->getFirstPhoto($row->foto);

            if (!$foto) {
                $rowIndex++;
                continue;
            }

            $path = public_path('storage/' . $foto);

            if (!file_exists($path)) {
                $rowIndex++;
                continue;
            }

            $drawing = new Drawing();
            $drawing->setName('Foto Doc');
            $drawing->setDescription('Foto Dokumentasi Chick');
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
                $sheet = $event->sheet->getDelegate();

                // ===============================
                // WRAP TEXT BOX DETAIL (KOLOM G)
                // ===============================
                $sheet->getStyle('G:G')->getAlignment()->setWrapText(true);
                $sheet->getColumnDimension('G')->setWidth(40);

                $row = 2;

                $rows = DocChick::where('comid', $this->comid())
                    ->when($this->start && $this->end, fn($q) => $q->whereBetween('tanggal', [$this->start, $this->end]))
                    ->when($this->satpam_id, fn($q) => $q->where('satpam_id', $this->satpam_id))
                    ->when($this->ekspedisi_id, fn($q) => $q->where('ekspedisi_id', $this->ekspedisi_id))
                    ->get();

                foreach ($rows as $r) {
                    // jika ada box detail atau foto → naikkan tinggi baris
                    if ($r->box_detail || $r->foto) {
                        $sheet->getRowDimension($row)->setRowHeight(80);
                    }
                    $row++;
                }

                // ===============================
                // KOLOM FOTO (HARUS O, BUKAN L ❗)
                // ===============================
                $sheet->getColumnDimension('O')->setWidth(22);
            },
        ];
    }

    private function formatBoxDetail($json)
    {
        if (empty($json)) {
            return '-';
        }

        $data = json_decode($json, true);

        if (!is_array($data)) {
            return '-';
        }

        return collect($data)
            ->map(function ($item) {
                $nama = $item['option_name'] ?? '-';
                $box = (int) ($item['jumlah_box'] ?? 0);
                $isi = (int) ($item['isi'] ?? 0);
                $total = (int) ($item['total_ekor'] ?? $box * $isi);

                return "{$nama} : {$box} x {$isi} = {$total}";
            })
            ->implode("\n"); // ENTER di Excel
    }

    private function getFirstPhoto($foto)
    {
        if (empty($foto)) {
            return null;
        }

        // JSON array
        if (str_starts_with($foto, '[')) {
            $data = json_decode($foto, true);
            return is_array($data) && count($data) ? $data[0] : null;
        }

        // single string
        return $foto;
    }
}
