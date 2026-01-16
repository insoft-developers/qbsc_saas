<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Traits\CommonTrait;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AbsensiExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    use CommonTrait;

    protected $start;
    protected $end;
    protected $satpam_id;
    protected $status;
    protected $jam_absen;

    public function __construct($start = null, $end = null, $satpam_id = null, $status = null, $jam_absen = null)
    {
        $this->start = $start;
        $this->end = $end;
        $this->satpam_id = $satpam_id;
        $this->status = $status;
        $this->jam_absen = $jam_absen;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Pilih kolom yang diperlukan saja agar query ringan
        $query = Absensi::select(['id', 'tanggal', 'latitude', 'longitude', 'shift_name', 'jam_setting_masuk', 'jam_masuk', 'jam_setting_pulang', 'jam_keluar', 'status', 'catatan_masuk', 'catatan_keluar', 'satpam_id', 'comid', 'is_terlambat', 'is_pulang_cepat'])
            ->where('comid', $this->comid())
            ->with(['satpam:id,name', 'company:id,company_name']);

        // Filter tanggal (opsional)
        if (!empty($this->start) && !empty($this->end)) {
            $query->whereBetween('tanggal', [$this->start, $this->end]);
        }

        // Filter satpam (opsional)
        if (!empty($this->satpam_id)) {
            $query->where('satpam_id', $this->satpam_id);
        }

        // Filter status (opsional)
        if (!empty($this->status)) {
            $query->where('status', $this->status);
        }

        if (!empty($this->jam_absen)) {
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

        // Jalankan query
        $data = $query->get();

        // Transform data ke format Excel
        return $data->map(function ($row) {
            return [
                'Tanggal' => $row->tanggal,
                'Nama Satpam' => optional($row->satpam)->name ?? '-',
                'Latitude' => $row->latitude ?? '-',
                'Longitude' => $row->longitude ?? '-',
                'Shift' => $row->shift_name,
                'Jam Shift Masuk' => $row->jam_setting_masuk,
                'Masuk' => $row->jam_masuk ? date('d-m-Y H:i', strtotime($row->jam_masuk)) : '-',
                'Jam Shift Keluar' => $row->jam_setting_pulang,
                'Keluar' => $row->jam_keluar ? date('d-m-Y H:i', strtotime($row->jam_keluar)) : '-',
                'Status' => $row->status ?? '-',
                'Catatan Masuk' => $row->catatan_masuk,
                'Catatan Pulang' => $row->catatan_keluar,
                'Perusahaan' => $row->company->company_name ?? '', // nanti bisa ditambah kalau sudah ada relasi
            ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal', 'Nama Satpam', 'Latitude', 'Longitude', 'Shift', 'Jam Shift Masuk', 'Masuk', 'Jam Shift Keluar', 'Keluar', 'Status', 'Catatan Masuk', 'Catatan Pulang', 'Perusahaan'];
    }
}
