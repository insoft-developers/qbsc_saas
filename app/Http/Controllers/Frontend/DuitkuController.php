<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\PaketLangganan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class DuitkuController extends Controller
{
    public function create_payment(Request $request)
    {
        $cfg = config('services.duitku');
        
        $merchantCode = $cfg['merchnat']
        $merchantKey = 'XXXXXXXCX17XXXX5XX5XXXXXX0X3XXAF'; // dari duitku

        $timestamp = round(microtime(true) * 1000); //in milisecond
        $paymentAmount = 40000;
        $merchantOrderId = time() . ''; // dari merchant, unique
        $productDetails = 'Test Pay with duitku';
        $email = 'test@test.com'; // email pelanggan merchant
        $phoneNumber = '08123456789'; // nomor tlp pelanggan merchant (opsional)
        $additionalParam = ''; // opsional
        $merchantUserInfo = ''; // opsional
        $customerVaName = 'John Doe'; // menampilkan nama pelanggan pada tampilan konfirmasi bank
        $callbackUrl = 'http://example.com/api-pop/backend/callback.php'; // url untuk callback
        $returnUrl = 'http://example.com/api-pop/backend/redirect.php'; //'http://example.com/return'; // url untuk redirect
        $expiryPeriod = 10; // untuk menentukan waktu kedaluarsa dalam menit
        $signature = hash('sha256', $merchantCode . $timestamp . $merchantKey);
        //$paymentMethod = 'VC'; //digunakan untuk direksional pembayaran

        // Detail pelanggan
        $firstName = 'John';
        $lastName = 'Doe';

        // Detail Alamat
        $alamat = 'Jl. Kembangan Raya';
        $city = 'Jakarta';
        $postalCode = '11530';
        $countryCode = 'ID';

        $address = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'address' => $alamat,
            'city' => $city,
            'postalCode' => $postalCode,
            'phone' => $phoneNumber,
            'countryCode' => $countryCode,
        ];

        $customerDetail = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'email' => $email,
            'phoneNumber' => $phoneNumber,
            'billingAddress' => $address,
            'shippingAddress' => $address,
            'merchantCustomerId' => $merchantCustomerId,
        ];

        $item1 = [
            'name' => 'Test Item 1',
            'price' => 10000,
            'quantity' => 1,
        ];

        $item2 = [
            'name' => 'Test Item 2',
            'price' => 30000,
            'quantity' => 3,
        ];

        $itemDetails = [$item1, $item2];

        /*Khusus untuk metode pembayaran Kartu Kredit
    $creditCardDetail = array (
        'saveCardToken' => 2
        'acquirer' => '014',
        'binWhitelist' => array (
            '014',
            '400000'
        )
    );*/

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
        //echo $params_string;
        $url = 'https://api-sandbox.duitku.com/api/merchant/createinvoice'; // Sandbox
        // $url = 'https://api-prod.duitku.com/api/merchant/createinvoice'; // Production

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
            //header('location: '. $result['paymentUrl']);
            print_r($result, false);
            // echo "paymentUrl :". $result['paymentUrl'] . "<br />";
            // echo "reference :". $result['reference'] . "<br />";
            // echo "statusCode :". $result['statusCode'] . "<br />";
            // echo "statusMessage :". $result['statusMessage'] . "<br />";
        } else {
            // echo $httpCode . " " . $request ;
            echo $request;
        }
    }

    // public function createPaymentUrl($amount, $orderId, $productDetail, $customer)
    // {
    //     $cfg = config('services.duitku');

    //     $signature = md5($cfg['merchant_code'] . $orderId . $amount . $cfg['api_key']);

    //     $data = [
    //         'merchantCode' => $cfg['merchant_code'],
    //         'paymentAmount' => $amount,
    //         'merchantOrderId' => $orderId,
    //         'productDetails' => $productDetail,
    //         'customerVaName' => $customer['name'],
    //         'email' => $customer['email'],
    //         'phoneNumber' => $customer['phone'],
    //         'callbackUrl' => route('duitku.callback'),
    //         'returnUrl' => route('duitku.return'),
    //         'signature' => $signature,
    //     ];

    //     $response = Http::post($cfg['base_url'] . '/createInvoice', $data);

    //     return $response->json();
    // }

    // public function create_payment(Request $request)
    // {
    //     $input = $request->all();
    //     $paket = PaketLangganan::find($input['id']);
    //     $user = User::find(Auth::user()->id);

    //     $orderId = 'INV-' . time();

    //     $result = $this->createPaymentUrl($paket->amount, $orderId, 'Pembelian ' . $paket->nama_paket, [
    //         'name' => $user->name . ' - ' . $user->company->company_name ?? '',
    //         'email' => $user->email,
    //         'phone' => $user->whatsapp,
    //     ]);

    //     return response()->json($result);
    // }
}
