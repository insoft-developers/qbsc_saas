<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsenLocation;
use App\Models\Absensi;
use App\Models\Broadcast;
use App\Models\Company;
use App\Models\CustomFeature;
use App\Models\DocBoxOption;
use App\Models\DocChick;
use App\Models\Ekspedisi;
use App\Models\EmergencyList;
use App\Models\JadwalPatroli;
use App\Models\JadwalPatroliDetail;
use App\Models\JamShift;
use App\Models\Kandang;
use App\Models\KandangAlarm;
use App\Models\KandangKipas;
use App\Models\KandangLampu;
use App\Models\KandangSuhu;
use App\Models\LaporanSituasi;
use App\Models\Lokasi;
use App\Models\Notifikasi;
use App\Models\PaketLangganan;
use App\Models\Patroli;
use App\Models\Pembelian;
use App\Models\RunningText;
use App\Models\Satpam;
use App\Models\Tamu;
use App\Models\User;
use App\Models\UserArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AdminUserController extends Controller
{
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('company.paket')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('is_active', function ($row) {
                    return $row->is_active === 1 ? '<center><span class="badge bg-success rounded-pill">Aktif</span></center>' : '<center><span class="badge bg-danger rounded-pill">Tidak</span></center>';
                })

                ->addColumn('name', function ($row) {
                    if ($row->company == null) {
                        return $row->name;
                    } else {
                        $is_peternakan = $row->company->is_peternakan == 1 ? 'Peternakan' : 'Reguler';
                        return $row->name . '<br>(' . $row->company->company_name . ')<br>(' . $is_peternakan . ')';
                    }
                })

                ->addColumn('paket', function ($row) {
                    if ($row->company == null || $row->company->paket == null) {
                        return '-';
                    } else {
                        $active = $row->company->is_active == 1 ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>';
                        return $row->company->paket->nama_paket . '<br>(' . date('d-m-Y', strtotime($row->company->expired_date)) . ') - ' . $active;
                    }
                })
                ->addColumn('profile_image', function ($row) {
                    if (!empty($row->profile_image)) {
                        $url = asset('storage/' . $row->profile_image);
                        return '<a href="' . asset('storage/' . $row->profile_image) . '" target="_blank"><img  style="cursor:pointer;" src="' . $url . '" alt="Foto" width="50" height="50" class="rounded-circle border"></a>';
                    } else {
                        return '<center>-</center>';
                    }
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y', strtotime($row->created_at));
                })
                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';

                    if ($row->is_active == 1) {
                        $button .= '<button onclick="activate(' . $row->id . ', 0)" title="Non Aktifkan" class="me-0 btn btn-insoft btn-danger"><i class="bi bi-x-lg"></i></button>';
                    } else {
                        $button .= '<button onclick="activate(' . $row->id . ', 1)" title="Aktifkan" class="me-0 btn btn-insoft btn-success"><i class="bi bi-check-circle"></i></button>';
                    }
                    $button .= '<a target="_blank" href="' . route('admin.impersonate', $row->id) . '"><button title="Masuk Ke User" class="me-0 btn btn-insoft btn-success"><i class="bi bi-check"></i></button></a>';
                    $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';

                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'is_active', 'profile_image', 'name', 'paket'])
                ->make(true);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'user';
        $pakets = PaketLangganan::all();
        return view('admin.user.user', compact('view', 'pakets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return User::with('company')->find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $user = User::find($id);
        $comid = $user->company_id;

        Company::where('id', $comid)->update([
            'paket_id' => $input['paket_id'],
            'is_active' => $input['is_active'],
            'expired_date' => date('Y-m-d', strtotime($input['expired_date'])),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sukses',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if ($user->level == 'owner') {
            DB::beginTransaction();

            try {
                $comid = $user->company_id;
                Company::where('id', $comid)->delete();
                Absensi::where('comid', $comid)->delete();
                AbsenLocation::where('comid', $comid)->delete();
                DocBoxOption::where('comid', $comid)->delete();
                DocChick::where('comid', $comid)->delete();
                Ekspedisi::where('comid', $comid)->delete();
                EmergencyList::where('comid', $comid)->delete();
                JadwalPatroli::where('comid', $comid)->delete();
                JadwalPatroliDetail::where('comid', $comid)->delete();
                JamShift::where('comid', $comid)->delete();
                Kandang::where('comid', $comid)->delete();
                KandangAlarm::where('comid', $comid)->delete();
                KandangKipas::where('comid', $comid)->delete();
                KandangSuhu::where('comid', $comid)->delete();
                KandangLampu::where('comid', $comid)->delete();
                LaporanSituasi::where('comid', $comid)->delete();
                Lokasi::where('comid', $comid)->delete();
                Notifikasi::where('comid', $comid)->delete();
                Patroli::where('comid', $comid)->delete();
                Pembelian::where('comid', $comid)->delete();
                RunningText::where('comid', $comid)->delete();
                DB::table('satpam_locations')
                    ->whereIn('satpam_id', function ($q) use ($comid) {
                        $q->select('id')->from('satpams')->where('comid', $comid);
                    })
                    ->delete();

                User::where('company_id', $comid)->delete();
                Broadcast::where('comid', $comid)->delete();
                CustomFeature::where('comid', $comid)->delete();
                Tamu::where('created_by', $id)->delete();
                Satpam::where('comid', $comid)->delete();
                UserArea::where('comid', $comid)->delete();

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'success',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]);
            }
        } else {
            DB::beginTransaction();

            try {
                $user->delete();
                Broadcast::where('pengirim', $id)->delete();
                CustomFeature::where('userid', $id)->delete();
                Tamu::where('created_by', $id)->delete();

                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'success',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]);
            }
        }
    }

    public function activate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
        ]);

        $data = User::findOrFail($request->id);
        $data->is_active = $data->is_active ? 0 : 1;
        $data->save();

        $message = $data->is_active ? 'Data berhasil diaktifkan.' : 'Data berhasil dinonaktifkan.';

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function impersonate($id)
    {
        
        $user = User::findOrFail($id);

        Auth::login($user);

        return redirect('/'); // arahkan ke halaman user
    }
}
