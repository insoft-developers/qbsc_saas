<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\AssetPage;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ResellerDownloadController extends Controller
{
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = AssetPage::get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('asset_description', function ($row) {
                    return '<div style="white-space:normal;width:300px;">' . $row->asset_description . '</div>';
                })
                ->addColumn('android_link', function ($row) {
                    if(empty($row->android_link)) {
                        return '';
                    }

                    return '<a href="' . $row->android_link . '" target="_blank">Download APK</a>';
                })

                ->addColumn('ios_link', function ($row) {
                    if(empty($row->ios_link)) {
                        return '';
                    }
                    return '<a href="' . $row->ios_link . '" target="_blank">Download IOS</a>';
                })

                ->addColumn('created_at', function($row){
                    return date('d F Y H:i', strtotime($row->created_at));
                })
                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    if ($row->android_link !== null && ! empty($row->android_link)) {
                        $button .= '<button data-link="'.$row->android_link.'" title="Copy Android Link" class="me-0 btn btn-insoft btn-success android-link"><i class="ri ri-android-line"></i></button>';
                    } else {
                         $button .= '<button disabled title="Copy Android Link" class="me-0 btn btn-insoft btn-success"><i class="ri ri-android-line"></i></button>';
                    }

                    if ($row->ios_link !== null && ! empty($row->ios_link)) {
                        $button .= '<button data-link="'.$row->ios_link.'" title="Copy IOS Link" class="btn btn-insoft btn-danger ios-link"><i class="ri ri-apple-line"></i></button>';
                    } else {
                        $button .= '<button disabled title="Copy IOS Link" class="btn btn-insoft btn-danger"><i class="ri ri-apple-line"></i></button>';
                    }

                    $button .= '</center>';
                    return $button;
                })

                ->addColumn('comid', function ($row) {
                    return $row->company->company_name ?? '';
                })

                ->rawColumns(['action', 'asset_description', 'android_link','ios_link'])
                ->make(true);

            // bi bi-trash3
        }
    }

    public function index()
    {
        $view = 'download';
        return view('reseller.download.download', compact('view'));
    }
}
