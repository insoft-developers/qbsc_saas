<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Satpam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AbsenController extends Controller
{
    public function verifyFace(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240', // max 10MB
            'user_id' => 'required|integer',
        ]);

        $userId = $request->user_id;
        $file = $request->file('image');

        // Ambil embedding lama dari DB
        $user = Satpam::find($userId);
        if (!$user || !$user->face_embedding) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'User tidak ditemukan atau belum punya embedding wajah.',
                ],
                404,
            );
        }

        $storedEmbedding = $user->face_embedding;

        // Kirim gambar + embedding lama ke Flask /verify
        $response = Http::attach('image', file_get_contents($file->getRealPath()), $file->getClientOriginalName())->post('http://192.168.100.3:5001/verify', [
            // pastikan dikirim sebagai JSON string, bukan string biasa
            'stored_embedding' => json_encode(json_decode($storedEmbedding)),
        ]);

        if ($response->failed()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal terhubung ke server Face API',
                ],
                500,
            );
        }

        $result = $response->json();

        return response()->json([
            'success' => $result['success'] ?? false,
            'matched' => $result['matched'] ?? false,
            'distance' => $result['distance'] ?? null,
            'message' => $result['message'] ?? 'Gagal verifikasi wajah',
        ]);
    }

    private function compareEmbeddings(array $emb1, array $emb2, $threshold = 0.6)
    {
        if (count($emb1) !== count($emb2)) {
            return false;
        }

        $dot = 0.0;
        $normA = 0.0;
        $normB = 0.0;

        for ($i = 0; $i < count($emb1); $i++) {
            $dot += $emb1[$i] * $emb2[$i];
            $normA += $emb1[$i] * $emb1[$i];
            $normB += $emb2[$i] * $emb2[$i];
        }

        $cosSim = $dot / (sqrt($normA) * sqrt($normB));

        return $cosSim > $threshold;
    }
}
