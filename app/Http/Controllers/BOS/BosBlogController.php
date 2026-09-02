<?php

namespace App\Http\Controllers\BOS;


use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BosBlogController extends Controller
{
    /**
     * List Blog
     */
    public function index(Request $request)
    {
        try {
            $limit = (int) $request->get('limit', 20);

            // Batasi supaya tidak terlalu besar
            $limit = min($limit, 50);

            $blogs = Blog::query()
                ->where('is_active', 1)
                ->orderByDesc('created_at')
                ->paginate($limit);

            // Tambahkan URL image
            $blogs->getCollection()->transform(function ($blog) {
                $blog->image_url = $blog->image
                    ? asset('storage/' . $blog->image)
                    : null;

                return $blog;
            });

            return response()->json([
                'success' => true,
                'message' => 'Data blog berhasil diambil',
                'data' => $blogs,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data blog',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Detail Blog
     */
    public function show($slug)
    {
        try {
            $blog = Blog::query()
                ->where('is_active', 1)
                ->where('slug', $slug)
                ->first();

            if (!$blog) {
                return response()->json([
                    'success' => false,
                    'message' => 'Blog tidak ditemukan',
                ], 404);
            }

            $blog->image_url = $blog->image
                ? asset('storage/' . $blog->image)
                : null;

            return response()->json([
                'success' => true,
                'message' => 'Detail blog berhasil diambil',
                'data' => $blog,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail blog',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}