<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Traits\CommonTrait;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class AbsensiExport implements FromCollection, WithHeadings, ShouldAutoSize, WithDrawings, WithEvents
{
    use CommonTrait;

    protected $start;
    protected $end;
    protected $satpam_id;
    protected $status;
    protected $jam_absen;

    protected $rows; // simpan data asli DB

    public function __construct($start = null, $end = null, $satpam_id = null, $status = null, $jam_absen = null)
    {
        $this->start = $start;
        $this->end = $end;
        $this->satpam_id = $satpam_id;
        $this->status = $status;
        $this->jam_absen = $jam_absen;
    }

    public function collection()
    {
        $query = Absensi::select(['id', 'tanggal', 'latitude', 'longitude', 'latitude2', 'longitude2', 'shift_name', 'jam_setting_masuk', 'jam_masuk', 'jam_setting_pulang', 'jam_keluar', 'status', 'catatan_masuk', 'catatan_keluar', 'satpam_id', 'comid', 'is_terlambat', 'is_pulang_cepat', 'foto_masuk', 'foto_pulang'])
            ->where('comid', $this->comid())
            ->with(['satpam:id,name', 'company:id,company_name']);

        if ($this->start && $this->end) {
            $query->whereBetween('tanggal', [$this->start, $this->end]);
        }

        if ($this->satpam_id) {
            $query->where('satpam_id', $this->satpam_id);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->jam_absen) {
            if ($this->jam_absen == 1) {
                $query->where('is_terlambat', 0)->where('is_pulang_cepat', 0);
            } elseif ($this->jam_absen == 2) {
                $query->where('is_terlambat', 1);
            } elseif ($this->jam_absen == 3) {
                $query->where('is_pulang_cepat', 1);
            } elseif ($this->jam_absen == 4) {
                $query->where('is_terlambat', 1)->where('is_pulang_cepat', 1);
            }
        }

        // 🔑 simpan data asli
        $this->rows = $query->get();

        return $this->rows->map(function ($row) {
            return [
                'Tanggal' => $row->tanggal,
                'Nama Satpam' => optional($row->satpam)->name ?? '-',
                'Latitude' => $row->latitude ?? '-',
                'Longitude' => $row->longitude ?? '-',
                'Lat Pulang' => $row->latitude2 ?? '-',
                'Lng Pulang' => $row->longitude2 ?? '-',
                'Shift' => $row->shift_name,
                'Jam Shift Masuk' => $row->jam_setting_masuk,
                'Masuk' => $row->jam_masuk ? date('d-m-Y H:i', strtotime($row->jam_masuk)) : '-',
                'Jam Shift Keluar' => $row->jam_setting_pulang,
                'Keluar' => $row->jam_keluar ? date('d-m-Y H:i', strtotime($row->jam_keluar)) : '-',
                'Status' => $row->status,
                'Foto Masuk' => '',
                'Foto Pulang' => '',
                'Catatan Masuk' => $row->catatan_masuk,
                'Catatan Pulang' => $row->catatan_keluar,
                'Perusahaan' => optional($row->company)->company_name ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal', 'Nama Satpam', 'Latitude', 'Longitude', 'Lat Pulang', 'Lng Pulang', 'Shift', 'Jam Shift Masuk', 'Masuk', 'Jam Shift Keluar', 'Keluar', 'Status', 'Foto Masuk', 'Foto Pulang', 'Catatan Masuk', 'Catatan Pulang', 'Perusahaan'];
    }

    public function drawings()
    {
        $drawings = [];

        $headers = $this->headings();
        $fotoMasukCol = array_search('Foto Masuk', $headers);
        $fotoPulangCol = array_search('Foto Pulang', $headers);

        foreach ($this->rows as $i => $row) {
            $excelRow = $i + 2;

            if ($row->foto_masuk && file_exists(public_path('storage/' . $row->foto_masuk))) {
                $img = new Drawing();
                $img->setName('Foto Masuk');
                $img->setPath(public_path('storage/' . $row->foto_masuk));
                $img->setHeight(70);
                $img->setCoordinates($this->colLetter($fotoMasukCol) . $excelRow);
                $img->setOffsetX(10);
                $img->setOffsetY(5);
                $drawings[] = $img;
            }

            if ($row->foto_pulang && file_exists(public_path('storage/' . $row->foto_pulang))) {
                $img = new Drawing();
                $img->setName('Foto Pulang');
                $img->setPath(public_path('storage/' . $row->foto_pulang));
                $img->setHeight(70);
                $img->setCoordinates($this->colLetter($fotoPulangCol) . $excelRow);
                $img->setOffsetX(10);
                $img->setOffsetY(5);
                $drawings[] = $img;
            }
        }

        return $drawings;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                foreach ($this->rows as $i => $row) {
                    $excelRow = $i + 2; // header di baris 1

                    if ($row->foto_masuk || $row->foto_pulang) {
                        // 70 px gambar ≈ 55 row height
                        $event->sheet->getRowDimension($excelRow)->setRowHeight(60);
                    }
                }
            },
        ];
    }

    private function colLetter($index)
    {
        $index += 1;
        $letter = '';

        while ($index > 0) {
            $temp = ($index - 1) % 26;
            $letter = chr($temp + 65) . $letter;
            $index = intval(($index - $temp - 1) / 26);
        }

        return $letter;
    }
}
