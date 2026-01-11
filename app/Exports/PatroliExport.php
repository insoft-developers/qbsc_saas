<?php

namespace App\Exports;

use App\Models\Patroli;
use App\Traits\CommonTrait;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PatroliExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    use CommonTrait;

    protected $start;
    protected $end;
    protected $satpam_id;
    protected $location_id;

    public function __construct($start = null, $end = null, $satpam_id = null, $location_id = null)
    {
        $this->start = $start;
        $this->end = $end;
        $this->satpam_id = $satpam_id;
        $this->location_id = $location_id;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Pilih kolom yang diperlukan saja agar query ringan
        $query = Patroli::select(['id', 'tanggal', 'jam', 'jam_awal_patroli', 'jam_akhir_patroli', 'location_id', 'satpam_id', 'latitude', 'longitude', 'note', 'comid', 'created_at'])
            ->where('comid', $this->comid())
            ->with(['satpam:id,name', 'company:id,company_name', 'lokasi:id,nama_lokasi']);

        // Filter tanggal (opsional)
        if (!empty($this->start) && !empty($this->end)) {
            $query->whereBetween('tanggal', [$this->start, $this->end]);
        }

        // Filter satpam (opsional)
        if (!empty($this->satpam_id)) {
            $query->where('satpam_id', $this->satpam_id);
        }

        // Filter status (opsional)
        if (!empty($this->location_id)) {
            $query->where('location_id', $this->location_id);
        }

        // Jalankan query
        $data = $query->get();

        // Transform data ke format Excel
        return $data->map(function ($row) {
            $isInRange = $this->jamDalamRange(
                $row->jam,
                $row->jam_awal_patroli, // sesuaikan sumber jam_awal
                $row->jam_akhir_patroli, // sesuaikan sumber jam_akhir
            );

            
            return [
                'Tanggal' => date('d-m-Y', strtotime($row->tanggal)),
                'Jam Patroli' => $row->jam,
                'Jam Jadwal' => $row->jam_awal_patroli.' - '.$row->jam_akhir_patroli,
                'Lokasi' => $row->lokasi->nama_lokasi ?? '-',
                'Nama Satpam' => optional($row->satpam)->name ?? '-',
                'Latitude' => $row->latitude ?? '-',
                'Longitude' => $row->longitude ?? '-',
                'Catatan' => $row->note ?? '-',
                'Sync Date' => date('d-m-Y H:i', strtotime($row->created_at)),
                'Perusahaan' => $row->company->company_name ?? '',
                '_out' => !$isInRange, // nanti bisa ditambah kalau sudah ada relasi
            ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal', 'Jam', 'Jam Patroli', 'Lokasi', 'Nama Satpam', 'Latitude', 'Longitude', 'Catatan', 'Sync Date', 'Perusahaan', '_out'];
    }

    public function styles(Worksheet $sheet)
    {
        $rowNum = 2; // row 1 = headings

        foreach ($this->collection() as $row) {
            if ($row['_out']) {
                // Kolom JAM = kolom B
                $sheet->getStyle("B{$rowNum}")->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => 'FF0000'],
                        'bold' => true,
                    ],
                ]);
            }

            $rowNum++;
        }
    }
}
