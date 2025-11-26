<?php

namespace App\Exports;

use App\Models\DocChick;
use App\Models\LaporanSituasi;
use App\Models\Tamu;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TamuExport implements FromCollection, WithHeadings, ShouldAutoSize
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
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Pilih kolom yang diperlukan saja agar query ringan
        $query = Tamu::where('comid', $this->comid())->with(['satpam:id,name', 'company:id,company_name', 'satpam_pulang:id,name', 'user:id,name']);

        // Filter tanggal (opsional)
        if (!empty($this->start) && !empty($this->end)) {
            $start = Carbon::parse($this->start)->startOfDay();
            $end = Carbon::parse($this->end)->endOfDay();

            $query->whereBetween('created_at', [$start, $end]);
        }

        // Filter satpam (opsional)
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

        // Jalankan query
        $data = $query->get();

        // Transform data ke format Excel
        return $data->map(function ($row) {
            $status = '';
            if($row->is_status == 1) {
                $status = 'Appointment';
            }
            else if($row->is_status == 2) {
                $status = 'Masuk';
            }
            else if($row->is_status == 3) {
                $status = 'Pulang';
            }
            return [
                'Tanggal' => date('d-m-Y H:i', strtotime($row->created_at)),
                'Nama Tamu' => $row->nama_tamu,
                'Jumlah Tamu' => $row->jumlah_tamu,
                'Tujuan' => $row->tujuan,
                'Whatsapp' => $row->whatsapp,
                'Waktu Tiba' => $row->arrive_at == null ? '': date('d-m-Y H:i', strtotime($row->arrive_at)),
                'Waktu Pulang' => $row->leave_at == null ? '': date('d-m-Y H:i', strtotime($row->leave_at)),
                'Status' => $status,
                'Satpam Masuk' => $row->satpam->name ?? '',
                'Satpam Pulang' => $row->satpam_pulang->name ?? '',
                'Catatan' => $row->catatan,
                'Dibuat Oleh' => $row->user->name ?? '', 
                'Perusahaan' => $row->company->company_name ?? '', // nanti bisa ditambah kalau sudah ada relasi
            ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal','Nama Tamu', 'Jumlah Tamu','Tujuan','Whatsapp', 'Waktu Tiba', 'Waktu Pulang', 'Status', 'Satpam Masuk', 'Satpam Pulang', 'Catatan', 'Dibuat Oleh','Perusahaan' ];
    }
}
