<?php

namespace App\Exports\Sheets;

use App\Models\LaporanSituasi;
use App\Traits\CommonTrait;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SituasiSheet implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
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

    public function collection()
    {
        $query = LaporanSituasi::with(['satpam:id,name','company:id,company_name'])
            ->where('comid', $this->comid());

        if ($this->start && $this->end) {
            $query->whereBetween('tanggal', [$this->start, $this->end]);
        }

        if ($this->satpam_id) {
            $query->where('satpam_id', $this->satpam_id);
        }

        return $query->get()->map(function($row){
            return [
                'Nama Satpam' => optional($row->satpam)->name ?? '-',
                'Tanggal' => date('d-m-Y H:i', strtotime($row->tanggal)),
                'Laporan' => $row->laporan,
                'Foto' => $row->foto ?? '-',
                'Perusahaan' => optional($row->company)->company_name ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama Satpam','Tanggal','Laporan','Foto','Perusahaan'];
    }

    public function title(): string
    {
        return 'Situasi';
    }
}
