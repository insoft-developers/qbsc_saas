<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PaketLangganan;
use App\Models\Pembelian;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DuitkuController extends Controller
{
    public function create_payment(Request $request)
    {
        $input = $request->all();

        $paket = PaketLangganan::find($input['id']);
        $user = User::find(Auth::user()->id);

        $cfg = config('services.duitku');

        $merchantCode = $cfg['merchant_code'];
        $merchantKey = $cfg['api_key'];

        $timestamp = round(microtime(true) * 1000); //in milisecond
        $paymentAmount = $paket->harga;
        $merchantOrderId = time() . ''; // dari merchant, unique
        $productDetails = 'Pembelian ' . $paket->nama_paket;
        $email = $user->email; // email pelanggan merchant
        $phoneNumber = $user->whtasapp; // nomor tlp pelanggan merchant (opsional)
        $additionalParam = ''; // opsional
        $merchantUserInfo = ''; // opsional
        $customerVaName = $user->name;
        $callbackUrl = $cfg['callback_url'];
        $returnUrl = route('duitku.return'); 
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

        $periode = $paket->periode == 1 ? 'Bulanan' : 'Tahunan';
        $item1 = [
            'name' => $paket->nama_paket . $periode,
            'price' => $paket->harga,
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
            Pembelian::create([
                "invoice" => $merchantOrderId,
                "paket_id" => $input['id'],
                "userid" => Auth::user()->id,
                "comid" => $user->company->id,
                "amount" => $paket->harga,
                "payment_status" => "PENDING"
            ]);
            $result = json_decode($request, true);
            return response()->json($result['paymentUrl']);
        } else {
            // echo $httpCode . " " . $request ;
            echo $request;
        }
    }
}
