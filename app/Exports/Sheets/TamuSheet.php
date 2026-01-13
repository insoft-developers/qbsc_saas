<?php

namespace App\Exports\Sheets;

use App\Models\Tamu;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class TamuSheet implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
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
        $query = Tamu::with(['satpam:id,name','satpam_pulang:id,name','user:id,name','company:id,company_name'])
            ->where('comid', $this->comid());

        if ($this->start && $this->end) {
            $query->whereBetween('created_at', [Carbon::parse($this->start)->startOfDay(), Carbon::parse($this->end)->endOfDay()]);
        }

        if ($this->satpam_id) {
            $satpam_id = $this->satpam_id;
            $query->where(function($q) use($satpam_id){
                $q->where('satpam_id', $satpam_id)->orWhere('satpam_id_pulang', $satpam_id);
            });
        }

        return $query->get()->map(function($row){
            $status = match($row->is_status) {
                1 => 'Appointment',
                2 => 'Masuk',
                3 => 'Pulang',
                default => '-',
            };
            return [
                'Tanggal' => date('d-m-Y H:i', strtotime($row->created_at)),
                'Nama Tamu' => $row->nama_tamu,
                'Jumlah Tamu' => $row->jumlah_tamu,
                'Tujuan' => $row->tujuan,
                'Whatsapp' => $row->whatsapp,
                'Waktu Tiba' => $row->arrive_at ? date('d-m-Y H:i', strtotime($row->arrive_at)) : '-',
                'Waktu Pulang' => $row->leave_at ? date('d-m-Y H:i', strtotime($row->leave_at)) : '-',
                'Status' => $status,
                'Satpam Masuk' => optional($row->satpam)->name ?? '-',
                'Satpam Pulang' => optional($row->satpam_pulang)->name ?? '-',
                'Foto' => $row->foto ?? '-',
                'Catatan' => $row->catatan,
                'Dibuat Oleh' => optional($row->user)->name ?? '-',
                'Perusahaan' => optional($row->company)->company_name ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal','Nama Tamu','Jumlah Tamu','Tujuan','Whatsapp','Waktu Tiba','Waktu Pulang','Status','Satpam Masuk','Satpam Pulang','Foto','Catatan','Dibuat Oleh','Perusahaan'];
    }

    public function title(): string
    {
        return 'Tamu';
    }
}
