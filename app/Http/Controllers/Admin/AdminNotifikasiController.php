<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Notifikasi;
use App\Services\FcmService;
use Illuminate\Http\Request;

class AdminNotifikasiController extends Controller
{
    public function notifikasi() {
        
        $comid = 1;
        $input['pengirim'] = 'Admin QBSC';
        $input['judul'] = 'Selamat Datang di QBSC';
        $input['pesan'] = 'QBSC adalah aplikasi patroli satpam lengkap dan mudah digunakan, dirancang khusus untuk membantu pemilik dan manajemen perusahaan mengontrol kinerja keamanan secara real-time dan terukur. Dilengkapi dengan mode offline dan Anti Fake GPS, QBSC memastikan setiap patroli benar-benar dilakukan dan tercatat sebagai bukti nyata, bukan sekadar laporan manual. Cocok digunakan oleh perusahaan, instansi pemerintah, perumahan, hotel, perkebunan, hingga area operasional berskala luas yang membutuhkan sistem keamanan yang profesional dan dapat dipantau kapan saja.';
        
        // Simpan ke database
        $input['image'] = null;
        $input['comid'] = $comid;
        $notifikasi = Notifikasi::create($input);
        if ($notifikasi) {
            // $topic = 'qbsc_bos_' . $comid;
            $topic = 'qbsc_all';
            $title = $input['judul'];
            $body = $input['pesan'];
            $this->send($topic, $title, $body);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.',
        ]);
    }

    protected function send($topic, $title, $body)
    {
        $fcm = new FcmService();
        return $fcm->sendToTopic($topic, $title, $body, [
            'comid' => $topic,
        ]);
    }
}
