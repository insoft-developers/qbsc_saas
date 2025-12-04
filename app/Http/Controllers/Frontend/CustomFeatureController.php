<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Models\CustomFeature;
use App\Models\User;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CustomFeatureController extends Controller
{
    use CommonTrait;
    public function custom_feature_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $data = CustomFeature::where('comid', $comid)->with('company:id,company_name','user:id,name');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function($row){
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->addColumn('finish_date', function($row){
                    return $row->finish_date ? date('d-m-Y H:i', strtotime($row->finish_date)) : ' - ';
                })
                ->addColumn('comid', function ($row) {
                    return $row->company->company_name ?? '-';
                })
                ->addColumn('userid', function ($row) {
                    return $row->user->name ?? '-';
                })
                ->addColumn('image', function ($row) {
                    if (!empty($row->image)) {
                        $url = asset('storage/' . $row->image);
                        return '<a href="' . asset('storage/' . $row->image) . '" target="_blank"><img  style="cursor:pointer;" src="' . $url . '" alt="Foto" width="50" height="50" class="rounded-circle border"></a>';
                    } else {
                        return '<center>-</center>';
                    }
                })
                ->addColumn('status', function($row){
                    $status = '';
                    if($row->status == 0) {
                        $status .= '<span class="badge bg-info">Pengajuan</span>';
                    }
                    else if($row->status == 1) {
                        $status .= '<span class="badge bg-warning">Proses</span>';
                    }
                    else if($row->status == 2) {
                        $status .= '<span class="badge bg-primary">Dikerjakan</span>';
                    }
                    else if($row->status == 3) {
                        $status .= '<span class="badge bg-success">Selesai</span>';
                    }
                    else if($row->status == 4) {
                        $status .= '<span class="badge bg-danger">Batal</span>';
                    }
                    return $status;
                })
                ->addColumn('admin_response', function($row){
                    return '<div style="white-space:normal;width:200px;">'.$row->admin_response.'</div>';
                })
                ->addColumn('description', function ($row) {
                    $full = $row->description;
                    $maxChars = 200; // kira2 3 baris

                    if (strlen($full) > $maxChars) {
                        $short = substr($full, 0, $maxChars) . '...';
                        return '
            <div class="laporan-short" style="white-space:normal;width:400px; display:block;">' .
                            $short .
                            '</div>
            <div class="laporan-full" style="white-space:normal;width:400px; display:none;">' .
                            $full .
                            '</div>
            <a href="javascript:void(0)" class="read-more">Selengkapnya</a>
        ';
                    } else {
                        return '<div style="white-space:normal;width:400px;">' . $full . '</div>';
                    }
                })
                ->addColumn('price', function($row){
                    return $row->price ? number_format($row->price) : '-';
                })
                ->addColumn('payment_code', function($row){
                    if($row->payment_status == 'PAID') {
                         return '<div>'.$row->payment_code.' '.$row->reference.'<br>'.date('d-m-Y H:i', strtotime($row->payment_date)).'</div>';
                    } else {
                        return '';
                    }
                   
                })

                ->addColumn('payment_status', function($row){
                    if($row->payment_status === 'PAID') {
                        return '<span class="badge bg-success">PAID</span>';
                    } 
                    else if($row->payment_status === 'PENDING') {
                        return '<span class="badge bg-danger">PENDING</span>';
                    } 
                    else {
                        return '';
                    }
                })
                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    if($row->status == 1 && $row->payment_status !== 'PAID') {
                        $button .= '<button onclick="payData(' . $row->id . ')" title="Lakukan pembayaran" class="me-0 btn btn-insoft btn-success"><i class="bi bi-currency-dollar"></i></button>';
                    } else {
                         $button .= '<button disabled title="Lakukan pembayaran" class="me-0 btn btn-insoft btn-success"><i class="bi bi-currency-dollar"></i></button>';
                    }

                    if ($row->status == 0) {
                        $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                        $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    } else {
                        $button .= '<button disabled title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                        $button .= '<button disabled title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    }

                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'image','description','status','admin_response','payment_code','payment_status'])
                ->make(true);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'custom-feature';
        return view('frontend.custom_feature.custom_feature', compact('view'));
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'feature' => 'required|string|max:100',
            'description' => 'required',
        ]);

        $paket = $this->what_paket($this->comid());
        if($paket['is_request_feature'] !== 1) {
            return response()->json([
                "success" => false,
                "message" => "Silahkan upgrade paket anda untuk bisa request custom feature.!!"
            ]);
        }

        // Simpan foto ke storage
        $path = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('custom_feature', 'public');
        }

        // Simpan ke database
        $input['image'] = $path;
        $input['userid'] = Auth::user()->id;
        $input['comid'] = $this->comid();
        $input['order_id'] = "RF-".time();
        CustomFeature::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.',
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
    public function edit(string $id)
    {
        return Broadcast::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        
        $data = Broadcast::findOrFail($id);

        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'judul' => 'required|string|max:100',
            'pesan' => 'required',
        ]);

        $path = $data->image;

        // Jika ada foto baru diupload
        if ($request->hasFile('image')) {
            // Hapus foto lama jika ada
            if ($data->image && Storage::disk('public')->exists($data->image)) {
                Storage::disk('public')->delete($data->image);
            }

            // Upload foto baru
            $path = $request->file('image')->store('broadcast', 'public');
        }

        $input['image'] = $path;
        $input['pengirim'] = Auth::user()->id;
        $input['comid'] = $this->comid();

        $data->update($input);
        // Update data user
        
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Broadcast::destroy($id);
    }

    public function payment(Request $request)
    {
        $input = $request->all();

        $feature = CustomFeature::find($input['id']);
        $user = User::find(Auth::user()->id);

        $cfg = config('services.duitku');

        $merchantCode = $cfg['merchant_code'];
        $merchantKey = $cfg['api_key'];

        $timestamp = round(microtime(true) * 1000); //in milisecond
        $paymentAmount = $feature->price;
        $merchantOrderId = $feature->order_id; // dari merchant, unique
        $productDetails = 'Pembayaran Request Custom Feature ' . $feature->feature;
        $email = $user->email; // email pelanggan merchant
        $phoneNumber = $user->whtasapp; // nomor tlp pelanggan merchant (opsional)
        $additionalParam = ''; // opsional
        $merchantUserInfo = ''; // opsional
        $customerVaName = $user->name;
        $callbackUrl = $cfg['callback_url'];
        $returnUrl = url('custom_feature'); 
        $expiryPeriod = 10; 
        $signature = hash('sha256', $merchantCode . $timestamp . $merchantKey);

        // Detail pelanggan
        $firstName = $user->name;
        $lastName = '';

        // Detail Alamat
        $alamat = $user->company->company_name ?? '';

        $address = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'address' => $alamat,
            'phone' => $phoneNumber,
        ];

        $customerDetail = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'phoneNumber' => $phoneNumber,
            'billingAddress' => $address,
            'shippingAddress' => $address,
        ];

      
        $item1 = [
            'name' => 'Pembayaran Request Custom Feature ' . $feature->feature,
            'price' => $feature->price,
            'quantity' => 1,
        ];

        $itemDetails = [$item1];

        $params = [
            'paymentAmount' => $paymentAmount,
            'merchantOrderId' => $merchantOrderId,
            'productDetails' => $productDetails,
            'additionalParam' => $additionalParam,
            'merchantUserInfo' => $merchantUserInfo,
            'customerVaName' => $customerVaName,
            'email' => $email,
            'phoneNumber' => $phoneNumber,
            'itemDetails' => $itemDetails,
            'customerDetail' => $customerDetail,
            //'creditCardDetail' => $creditCardDetail,
            'callbackUrl' => $callbackUrl,
            'returnUrl' => $returnUrl,
            'expiryPeriod' => $expiryPeriod,
            //'paymentMethod' => $paymentMethod
        ];

        $params_string = json_encode($params);

        $url = $cfg['base_url'] . '/createinvoice'; // Sandbox

        //log transaksi untuk debug
        // file_put_contents('log_createInvoice.txt', "* log *\r\n", FILE_APPEND | LOCK_EX);
        // file_put_contents('log_createInvoice.txt', $params_string . "\r\n\r\n", FILE_APPEND | LOCK_EX);
        // file_put_contents('log_createInvoice.txt', 'x-duitku-signature:' . $signature . "\r\n\r\n", FILE_APPEND | LOCK_EX);
        // file_put_contents('log_createInvoice.txt', 'x-duitku-timestamp:' . $timestamp . "\r\n\r\n", FILE_APPEND | LOCK_EX);
        // file_put_contents('log_createInvoice.txt', 'x-duitku-merchantcode:' . $merchantCode . "\r\n\r\n", FILE_APPEND | LOCK_EX);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($params_string), 'x-duitku-signature:' . $signature, 'x-duitku-timestamp:' . $timestamp, 'x-duitku-merchantcode:' . $merchantCode]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //execute post
        $request = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode == 200) { 
            $result = json_decode($request, true);
            return response()->json($result['paymentUrl']);
        } else {
            // echo $httpCode . " " . $request ;
            echo $request;
        }
    }
}
