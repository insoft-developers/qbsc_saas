<?php

namespace App\Exports\Sheets;

use App\Models\Broadcast;
use App\Traits\CommonTrait;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class BroadcastSheet implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
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
        $query = Broadcast::where('comid', $this->comid());

        if ($this->start && $this->end) {
            $query->whereBetween('created_at', [$this->start, $this->end]);
        }

        if ($this->satpam_id) {
            $query->where('satpam_id', $this->satpam_id);
        }

        return $query->get()->map(function($row){
            return [
                'Tanggal' => date('d-m-Y H:i', strtotime($row->created_at)),
                'Pesan' => $row->message,
                'Foto' => $row->foto ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal','Pesan','Foto'];
    }

    public function title(): string
    {
        return 'Broadcast';
    }
}
