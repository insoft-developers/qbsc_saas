<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Kandang;
use App\Models\Lokasi;
use App\Models\PaketLangganan;
use App\Models\Pembelian;
use App\Models\Satpam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AdminTransactionController extends Controller
{
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = Pembelian::with('company', 'user', 'paket');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('payment_status', function ($row) {
                    return $row->payment_status === 'PAID' ? '<center><span class="badge bg-success rounded-pill">PAID</span></center>' : '<center><span class="badge bg-danger rounded-pill">' . $row->payment_status . '</span></center>';
                })

                ->addColumn('paket_id', function ($row) {
                    if ($row->paket == null) {
                        return '-';
                    } else {
                        return $row->paket->nama_paket;
                    }
                })
                ->addColumn('userid', function ($row) {
                    return $row->user == null ? '' : $row->user->email ?? '';
                })
                ->addColumn('whatsapp', function ($row) {
                    return $row->user == null ? '' : $row->user->whatsapp ?? '';
                })
                ->addColumn('comid', function ($row) {
                    return $row->company == null ? '' : $row->company->company_name ?? '';
                })
                ->addColumn('amount', function ($row) {
                    return number_format($row->amount);
                })
                ->addColumn('payment_amount', function ($row) {
                    return number_format($row->payment_amount);
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y', strtotime($row->created_at));
                })
                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';

                    if ($row->payment_status == 'PAID') {
                        $button .= '<button disabled class="me-0 btn btn-insoft btn-success"><i class="bi bi-check-circle"></i></button>';
                    } else {
                        $button .= '<button onclick="payment(' . $row->id . ', 1)" title="Ubah Jadi PAID" class="me-0 btn btn-insoft btn-success"><i class="bi bi-check-circle"></i></button>';
                    }

                    $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';

                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'payment_status', 'paket_id'])
                ->make(true);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'transaction';
        $pakets = PaketLangganan::all();
        $users = User::with('company:id,company_name')->where('level', 'owner')->get();
        return view('admin.transaction.transaction', compact('view', 'pakets', 'users'));
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
    public function store(Request $request)
    {
        $input = $request->all();
        $validated = $request->validate([
            'userid' => 'required',
            'paket_id' => 'required',
            'payment_status' => 'required',
            'payment_date' => 'required_if:payment_status,PAID',
            'note' => 'required_if:payment_status,PAID',
            'reference' => 'required_if:payment_status,PAID',
        ]);

        $input['invoice'] = time() . '';
        $input['payment_with'] = $input['note'];
        $pakets = PaketLangganan::find($input['paket_id']);
        $input['amount'] = $pakets->harga;
        $input['payment_amount'] = $input['payment_status'] == 'PAID' ? $pakets->harga : null;
        $users = User::find($input['userid']);
        $input['comid'] = $users->company_id;
        $input['payment_date'] = $request->payment_date . ' ' . date('H:i:s');

        $beli = Pembelian::create($input);
        $pembelian_id = $beli->id;

        $pembelian = Pembelian::find($pembelian_id);
        if ($pembelian && $input['payment_status'] == 'PAID') {
            $paket = PaketLangganan::find($pembelian->paket_id);
            $expired_date = null;
            if ($paket->periode == 1) {
                $expired_date = Carbon::now()->addMonth()->format('Y-m-d');
            } elseif ($paket->periode == 2) {
                $expired_date = Carbon::now()->addYear()->format('Y-m-d');
            }

            $company = Company::find($pembelian->comid);
            $active_id = $company->paket_id;
            $company->paket_id = $pembelian->paket_id;
            $company->expired_date = $expired_date;
            $company->is_active = 1;
            $company->save();

            $this->setting_feature($active_id, $pembelian->paket_id, $pembelian->comid);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sukses',
        ]);
    }

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
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        return Pembelian::destroy($id);
    }

    public function paid(Request $request)
    {
        $input = $request->all();
        $pembelian = Pembelian::find($input['id']);
        $input['payment_with'] = 'Admin Payment';
        $pakets = PaketLangganan::find($pembelian->paket_id);
        $input['payment_amount'] = $pakets->harga;
        $input['payment_date'] = date('Y-m-d H:i:s');
        $input['payment_status'] = 'PAID';
        $input['reference'] = time().'';

        $pembelian->update($input);

        $paket = PaketLangganan::find($pembelian->paket_id);
        $expired_date = null;
        if ($paket->periode == 1) {
            $expired_date = Carbon::now()->addMonth()->format('Y-m-d');
        } elseif ($paket->periode == 2) {
            $expired_date = Carbon::now()->addYear()->format('Y-m-d');
        }

        $company = Company::find($pembelian->comid);
        $active_id = $company->paket_id;
        $company->paket_id = $pembelian->paket_id;
        $company->expired_date = $expired_date;
        $company->is_active = 1;
        $company->save();

        $this->setting_feature($active_id, $pembelian->paket_id, $pembelian->comid);

        return response()->json([
            'success' => true,
            'message' => 'Sukses',
        ]);
    }

    protected function setting_feature($active_id, $new_id, $comid)
    {
        if ($active_id === $new_id) {
        } else {
            $paket_lama = PaketLangganan::find($active_id);
            $paket_baru = PaketLangganan::find($new_id);

            $jumlah_satpam_lama = $paket_lama->jumlah_satpam;
            $jumlah_satpam_baru = $paket_baru->jumlah_satpam;

            $jumlah_lokasi_lama = $paket_lama->jumlah_lokasi;
            $jumlah_lokasi_baru = $paket_baru->jumlah_lokasi;

            $jumlah_user_lama = $paket_lama->jumlah_user_admin;
            $jumlah_user_baru = $paket_baru->jumlah_user_admin;

            $jumlah_farm_lama = $paket_lama->jumlah_farm;
            $jumlah_farm_baru = $paket_baru->jumlah_farm;

            if ($jumlah_satpam_baru < $jumlah_satpam_lama) {
                Satpam::where('comid', $comid)->update([
                    'is_active' => 0,
                ]);
            }

            if ($jumlah_lokasi_baru < $jumlah_lokasi_lama) {
                Lokasi::where('comid', $comid)->update([
                    'is_active' => 0,
                ]);
            }

            if ($jumlah_user_baru < $jumlah_user_lama) {
                User::where('company_id', $comid)
                    ->where('level', 'user')
                    ->update([
                        'is_active' => 0,
                    ]);
            }

            if ($jumlah_farm_baru < $jumlah_farm_lama) {
                Kandang::where('comid', $comid)->update(['is_active' => 0]);
            }

            if ($paket_baru->is_user_area !== 1) {
                User::where('company_id', $comid)->update([
                    'is_area' => 0,
                ]);
            }
        }
    }
}
