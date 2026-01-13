<?php

namespace App\Exports;

use App\Models\Tamu;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class TamuExport implements FromCollection, WithHeadings, ShouldAutoSize, WithDrawings, WithEvents
{
    use CommonTrait;

    protected $start;
    protected $end;
    protected $satpam_id;
    protected $user_id;

    public function __construct($start = null, $end = null, $satpam_id = null, $user_id = null)
    {
        $this->start = $start;
        $this->end = $end;
        $this->satpam_id = $satpam_id;
        $this->user_id = $user_id;
    }

    /**
     * ======================
     * DATA TEXT
     * ======================
     */
    public function collection()
    {
        $query = Tamu::where('comid', $this->comid())
            ->with(['satpam:id,name', 'company:id,company_name', 'satpam_pulang:id,name', 'user:id,name']);

        if (!empty($this->start) && !empty($this->end)) {
            $start = Carbon::parse($this->start)->startOfDay();
            $end = Carbon::parse($this->end)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        if (!empty($this->satpam_id)) {
            $satpam_id = $this->satpam_id;
            $query->where(function ($q) use ($satpam_id) {
                $q->where('satpam_id', $satpam_id)->orWhere('satpam_id_pulang', $satpam_id);
            });
        }

        if (!empty($this->user_id)) {
            if ($this->user_id == -1) {
                $query->whereNull('created_by');
            } else {
                $query->where('created_by', $this->user_id);
            }
        }

        return $query->get()->map(function ($row) {
            $status = '';
            if($row->is_status == 1) $status = 'Appointment';
            elseif($row->is_status == 2) $status = 'Masuk';
            elseif($row->is_status == 3) $status = 'Pulang';

            return [
                'Tanggal' => date('d-m-Y H:i', strtotime($row->created_at)),
                'Nama Tamu' => $row->nama_tamu,
                'Jumlah Tamu' => $row->jumlah_tamu,
                'Tujuan' => $row->tujuan,
                'Whatsapp' => $row->whatsapp,
                'Waktu Tiba' => $row->arrive_at ? date('d-m-Y H:i', strtotime($row->arrive_at)) : '',
                'Waktu Pulang' => $row->leave_at ? date('d-m-Y H:i', strtotime($row->leave_at)) : '',
                'Status' => $status,
                'Satpam Masuk' => $row->satpam->name ?? '',
                'Satpam Pulang' => $row->satpam_pulang->name ?? '',
                'Catatan' => $row->catatan,
                'Dibuat Oleh' => $row->user->name ?? '', 
                'Perusahaan' => $row->company->company_name ?? '',
                'Foto' => '', // placeholder foto
            ];
        });
    }

    /**
     * ======================
     * HEADER
     * ======================
     */
    public function headings(): array
    {
        return ['Tanggal','Nama Tamu', 'Jumlah Tamu','Tujuan','Whatsapp', 'Waktu Tiba', 'Waktu Pulang', 'Status', 'Satpam Masuk', 'Satpam Pulang', 'Catatan', 'Dibuat Oleh','Perusahaan','Foto' ];
    }

    /**
     * ======================
     * FOTO (DRAWING)
     * ======================
     */
    public function drawings()
    {
        $drawings = [];

        $rows = Tamu::where('comid', $this->comid())
            ->when($this->start && $this->end, fn($q) => $q->whereBetween('created_at', [Carbon::parse($this->start)->startOfDay(), Carbon::parse($this->end)->endOfDay()]))
            ->when($this->satpam_id, fn($q) => $q->where('satpam_id', $this->satpam_id)->orWhere('satpam_id_pulang', $this->satpam_id))
            ->when($this->user_id && $this->user_id != -1, fn($q) => $q->where('created_by', $this->user_id))
            ->get();

        $rowIndex = 2; // baris header
        $fotoColumn = 'N'; // kolom Foto (sesuaikan posisi terakhir)

        foreach ($rows as $row) {
            if (!$row->foto) { // sesuaikan field foto di table Tamu
                $rowIndex++;
                continue;
            }

            $path = public_path('storage/' . $row->foto);
            if (!file_exists($path)) {
                $rowIndex++;
                continue;
            }

            $drawing = new Drawing();
            $drawing->setName('Foto Tamu');
            $drawing->setDescription('Foto Tamu');
            $drawing->setPath($path);
            $drawing->setHeight(70);
            $drawing->setCoordinates($fotoColumn . $rowIndex);
            $drawing->setOffsetX(5);
            $drawing->setOffsetY(5);

            $drawings[] = $drawing;
            $rowIndex++;
        }

        return $drawings;
    }

    /**
     * ======================
     * SETTING HEIGHT & WIDTH KOLM FOTO
     * ======================
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $row = 2;

                $rows = Tamu::where('comid', $this->comid())
                    ->when($this->start && $this->end, fn($q) => $q->whereBetween('created_at', [Carbon::parse($this->start)->startOfDay(), Carbon::parse($this->end)->endOfDay()]))
                    ->when($this->satpam_id, fn($q) => $q->where('satpam_id', $this->satpam_id)->orWhere('satpam_id_pulang', $this->satpam_id))
                    ->when($this->user_id && $this->user_id != -1, fn($q) => $q->where('created_by', $this->user_id))
                    ->get();

                foreach ($rows as $r) {
                    if ($r->foto) {
                        $event->sheet->getRowDimension($row)->setRowHeight(80);
                    }
                    $row++;
                }

                // Lebar kolom foto
                $event->sheet->getColumnDimension('N')->setWidth(22);
            },
        ];
    }
}
