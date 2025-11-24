<?php

namespace App\Exports;

use App\Models\DocChick;
use App\Models\LaporanSituasi;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SituasiExport implements FromCollection, WithHeadings, ShouldAutoSize
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
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Pilih kolom yang diperlukan saja agar query ringan
        $query = LaporanSituasi::where('comid', $this->comid())->with(['satpam:id,name', 'company:id,company_name']);

        // Filter tanggal (opsional)
        if (!empty($this->start) && !empty($this->end)) {
            $start = Carbon::parse($this->start)->startOfDay();
            $end = Carbon::parse($this->end)->endOfDay();

            $query->whereBetween('tanggal', [$start, $end]);
        }

        // Filter satpam (opsional)
        if (!empty($this->satpam_id)) {
            $query->where('satpam_id', $this->satpam_id);
        }

        // Jalankan query
        $data = $query->get();

        // Transform data ke format Excel
        return $data->map(function ($row) {
            return [
                'Nama Satpam' => optional($row->satpam)->name ?? '-',
                'Waktu' => date('d-m-Y', strtotime($row->tanggal)),
                'Laporan' => $row->laporan,
                'Perusahaan' => $row->company->company_name ?? '', // nanti bisa ditambah kalau sudah ada relasi
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama Satpam','Waktu','Laporan','Perusahaan'];
    }
}
