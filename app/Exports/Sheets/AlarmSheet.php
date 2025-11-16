<?php

namespace App\Exports\Sheets;

use App\Models\KandangAlarm;
use App\Models\KandangKipas;
use App\Traits\CommonTrait;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class AlarmSheet implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle
{
    use CommonTrait;

    protected $start;
    protected $end;
    protected $satpam_id;
    protected $kandang_id;

    public function __construct($start = null, $end = null, $satpam_id = null, $kandang_id = null)
    {
        $this->start = $start;
        $this->end = $end;
        $this->satpam_id = $satpam_id;
        $this->kandang_id = $kandang_id;
    }

    public function collection()
    {
        $query = KandangAlarm::select([
            'id','tanggal','jam','kandang_id','satpam_id','is_alarm_on','latitude','longitude','note','comid','created_at'
        ])
        ->where('comid', $this->comid())
        ->with(['satpam:id,name', 'company:id,company_name', 'kandang:id,name']);

        if ($this->start && $this->end) {
            $query->whereBetween('tanggal', [$this->start, $this->end]);
        }
        if ($this->satpam_id) $query->where('satpam_id', $this->satpam_id);
        if ($this->kandang_id) $query->where('kandang_id', $this->kandang_id);

        return $query->get()->map(function ($row) {
            return [
                'Tanggal' => date('d-m-Y', strtotime($row->tanggal)),
                'Jam' => $row->jam,
                'Kandang' => $row->kandang->name ?? '-',
                'Nama Satpam' => optional($row->satpam)->name ?? '-',
                'Alarm' => $row->is_alarm_on == 1 ? 'ON':'OFF',
                'Latitude' => $row->latitude ?? '-',
                'Longitude' => $row->longitude ?? '-',
                'Catatan' => $row->note ?? '-',
                'Sync Date' => date('d-m-Y H:i', strtotime($row->created_at)),
                'Perusahaan' => $row->company->company_name ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tanggal','Jam','Kandang','Nama Satpam','Alarm','Latitude','Longitude','Catatan','Sync Date','Perusahaan'
        ];
    }

    public function title(): string
    {
        return 'Alarm';
    }
}
