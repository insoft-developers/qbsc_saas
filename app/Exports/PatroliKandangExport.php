<?php

namespace App\Exports;

use App\Exports\Sheets\AlarmSheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use App\Exports\Sheets\KipasSheet;
use App\Exports\Sheets\LampuSheet;
use App\Exports\Sheets\SuhuSheet;

class PatroliKandangExport implements WithMultipleSheets
{
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

    public function sheets(): array
    {
        return [
            'Suhu' => new SuhuSheet($this->start, $this->end, $this->satpam_id, $this->kandang_id),
            'Kipas' => new KipasSheet($this->start, $this->end, $this->satpam_id, $this->kandang_id),
            'Alarm' => new AlarmSheet($this->start, $this->end, $this->satpam_id, $this->kandang_id),
            'Lampu' => new LampuSheet($this->start, $this->end, $this->satpam_id, $this->kandang_id),
        ];
    }
}
