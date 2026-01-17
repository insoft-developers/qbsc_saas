<?php

namespace App\Exports;

use App\Models\Satpam;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class KinerjaExport implements FromCollection, WithHeadings, ShouldAutoSize, WithDrawings, WithEvents
{
    use CommonTrait;

    protected $periode;
    protected $tahun;
    protected $rows; // simpan data satpam

    public function __construct($periode = null, $tahun = null)
    {
        $this->periode = $periode;
        $this->tahun = $tahun;
    }

    /**
     * ======================
     * DATA
     * ======================
     */
    public function collection()
    {
        $bulan = $this->periode ?? date('m');
        $tahun = $this->tahun ?? date('Y');

        $this->rows = Satpam::with([
            'absensi' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            },
            'patroli' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            },
            'company:id,company_name',
        ])
            ->where('comid', $this->comid())
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->rows->map(function ($row) {
            $totalTerlambat = 0;
            $pulangCepat = 0;

            foreach ($row->absensi as $abs) {
                if ($abs->status == 2 && str_contains($abs->catatan_masuk, 'terlambat')) {
                    preg_match('/\d+/', $abs->catatan_masuk, $m);
                    $totalTerlambat += (int) ($m[0] ?? 0);
                }

                if ($abs->status == 2 && str_contains($abs->catatan_keluar, 'pulang lebih cepat')) {
                    preg_match('/\d+/', $abs->catatan_keluar, $m);
                    $pulangCepat += (int) ($m[0] ?? 0);
                }
            }

            return [
                'Foto' => '',
                'Nama Satpam' => $row->name,
                'Jabatan' => $row->is_danru ? 'Danru' : 'Anggota',
                'Hadir' => $row->absensi->where('status', 2)->count(),
                'Tepat Waktu' => $row->absensi->where('status', 2)->where('is_terlambat', 0)->where('is_pulang_cepat', 0)->count(),
                'Terlambat' => $row->absensi->where('status', 2)->where('is_terlambat', 1)->count() . ' X',
                'Total Terlambat' => $totalTerlambat . ' menit',
                'Cepat Pulang' => $row->absensi->where('status', 2)->where('is_pulang_cepat', 1)->count() . ' X',
                'Total Cepat Pulang' => $pulangCepat . ' menit',
                'Titik Patroli' => $row->patroli->count(),
                'Patroli Diluar Jadwal' => $row->patroli
                    ->filter(function ($p) {
                        if (empty($p->jam_awal_patroli) || empty($p->jam_akhir_patroli)) {
                            return true; // dianggap di luar range
                        }

                        return !Carbon::createFromFormat('H:i:s', $p->jam)->between(Carbon::createFromFormat('H:i', $p->jam_awal_patroli), Carbon::createFromFormat('H:i', $p->jam_akhir_patroli), true);
                    })
                    ->count(),
                'Perusahaan' => $row->company->company_name ?? '',
            ];
        });
    }

    /**
     * ======================
     * FOTO (IMAGE)
     * ======================
     */
    public function drawings()
    {
        $drawings = [];

        foreach ($this->rows as $i => $row) {
            if (!$row->face_photo_path) {
                continue;
            }

            $path = public_path('storage/' . $row->face_photo_path);
            if (!file_exists($path)) {
                continue;
            }

            $drawing = new Drawing();
            $drawing->setName('Foto Satpam');
            $drawing->setPath($path);

            // 🔒 SERAGAM
            $drawing->setHeight(80); // tinggi semua foto
            $drawing->setWidth(80); // lebar semua foto
            $drawing->setResizeProportional(false);

            $drawing->setCoordinates('A' . ($i + 2));
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(5);

            $drawings[] = $drawing;
        }

        return $drawings;
    }

    /**
     * ======================
     * AUTO ROW HEIGHT
     * ======================
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // 🔒 Lebar kolom Foto
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(15);

                // 🔒 Tinggi row menyesuaikan foto
                foreach ($this->rows as $i => $row) {
                    $event->sheet
                        ->getDelegate()
                        ->getRowDimension($i + 2)
                        ->setRowHeight(90);
                }
            },
        ];
    }

    /**
     * ======================
     * HEADER
     * ======================
     */
    public function headings(): array
    {
        return ['Foto', 'Nama Satpam', 'Jabatan', 'Hadir', 'Tepat Waktu', 'Terlambat', 'Total Terlambat', 'Cepat Pulang', 'Total Cepat Pulang', 'Titik Patroli', 'Patroli Diluar Jadwal', 'Perusahaan'];
    }
}
