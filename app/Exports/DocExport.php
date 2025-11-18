<?php

namespace App\Exports;

use App\Models\DocChick;
use App\Traits\CommonTrait;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DocExport implements FromCollection, WithHeadings, ShouldAutoSize
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
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Pilih kolom yang diperlukan saja agar query ringan
        $query = DocChick::where('comid', $this->comid())->with(['satpam:id,name', 'company:id,company_name', 'ekspedisi:id,name']);

        // Filter tanggal (opsional)
        if (!empty($this->start) && !empty($this->end)) {
            $query->whereBetween('tanggal', [$this->start, $this->end]);
        }

        // Filter satpam (opsional)
        if (!empty($this->satpam_id)) {
            $query->where('satpam_id', $this->satpam_id);
        }

        // Filter status (opsional)
        if (!empty($this->ekspedisi_id)) {
            $query->where('ekspedisi_id', $this->ekspedisi_id);
        }

        // Jalankan query
        $data = $query->get();

        // Transform data ke format Excel
        return $data->map(function ($row) {
            return [
                'Tanggal' => date('d-m-Y', strtotime($row->tanggal)),
                'Jam' => $row->jam,
                'Tgl Input' => date('d-m-Y H:i', strtotime($row->input_date)),
                'Nama Satpam' => optional($row->satpam)->name ?? '-',
                'Jumlah' => $row->jumlah,
                'Ekspedisi' => optional($row->ekspedisi)->name ?? '-',
                'Tujuan' => $row->tujuan,
                'No Polisi' => $row->no_polisi,
                'Jenis' => $row->jenis == 1 ? 'Male' : 'Female',
                'Catatan' => $row->note ?? '-',
                'Perusahaan' => $row->company->company_name ?? '', // nanti bisa ditambah kalau sudah ada relasi
            ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal', 'Jam', 'Tgl Input','Nama Satpam', 'Jumlah', 'Ekspedisi', 'Tujuan', 'No Polisi', 'Jenis', 'Catatan', 'Perusahaan'];
    }
}
