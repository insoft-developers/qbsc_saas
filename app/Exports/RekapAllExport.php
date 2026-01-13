<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RekapAllExport implements WithMultipleSheets
{
    protected $start;
    protected $end;
    protected $satpam_id;

    public function __construct($start, $end, $satpam_id = null)
    {
        $this->start = $start;
        $this->end = $end;
        $this->satpam_id = $satpam_id;
    }

    public function sheets(): array
    {
        return [
            new \App\Exports\Sheets\AbsenSheet($this->start, $this->end, $this->satpam_id),
            new \App\Exports\Sheets\SuhuSheet($this->start, $this->end, $this->satpam_id),
            new \App\Exports\Sheets\KipasSheet($this->start, $this->end, $this->satpam_id),
            new \App\Exports\Sheets\AlarmSheet($this->start, $this->end, $this->satpam_id),
            new \App\Exports\Sheets\LampuSheet($this->start, $this->end, $this->satpam_id),
            new \App\Exports\Sheets\TamuSheet($this->start, $this->end, $this->satpam_id),
            new \App\Exports\Sheets\SituasiSheet($this->start, $this->end, $this->satpam_id),
            new \App\Exports\Sheets\BroadcastSheet($this->start, $this->end, $this->satpam_id),
        ];
    }
}
