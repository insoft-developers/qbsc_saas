<?php

namespace App\Exports\Sheets;

use App\Models\Absen;
use App\Models\Absensi;
use App\Traits\CommonTrait;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class AbsenSheet implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
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
        $query = Absensi::with(['satpam:id,name', 'company:id,company_name'])->where('comid', $this->comid());

        if ($this->start && $this->end) {
            $query->whereBetween('tanggal', [$this->start, $this->end]);
        }

        if ($this->satpam_id) {
            $query->where('satpam_id', $this->satpam_id);
        }

        return $query->get()->map(function($row) {
            return [
                'Tanggal' => date('d-m-Y', strtotime($row->tanggal)),
                'Jam Masuk' => $row->jam_masuk,
                'Jam Pulang' => $row->jam_pulang,
                'Nama Satpam' => optional($row->satpam)->name ?? '-',
                'Foto' => $row->foto ?? '-',
                'Perusahaan' => optional($row->company)->company_name ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal','Jam Masuk','Jam Pulang','Nama Satpam','Foto','Perusahaan'];
    }

    public function title(): string
    {
        return 'Absensi';
    }
}
