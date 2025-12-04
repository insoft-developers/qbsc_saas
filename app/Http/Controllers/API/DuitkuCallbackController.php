<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CustomFeature;
use App\Models\PaketLangganan;
use App\Models\Pembelian;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class DuitkuCallbackController extends Controller
{
    public function callback(Request $request)
    {
        $cfg = config('services.duitku');

        $apiKey = $cfg['api_key']; // API key anda
        $merchantCode = isset($_POST['merchantCode']) ? $_POST['merchantCode'] : null;
        $amount = isset($_POST['amount']) ? $_POST['amount'] : null;
        $merchantOrderId = isset($_POST['merchantOrderId']) ? $_POST['merchantOrderId'] : null;
        $productDetail = isset($_POST['productDetail']) ? $_POST['productDetail'] : null;
        $additionalParam = isset($_POST['additionalParam']) ? $_POST['additionalParam'] : null;
        $paymentCode = isset($_POST['paymentCode']) ? $_POST['paymentCode'] : null;
        $resultCode = isset($_POST['resultCode']) ? $_POST['resultCode'] : null;
        $merchantUserId = isset($_POST['merchantUserId']) ? $_POST['merchantUserId'] : null;
        $reference = isset($_POST['reference']) ? $_POST['reference'] : null;
        $signature = isset($_POST['signature']) ? $_POST['signature'] : null;
        $expiryDate = isset($_POST['expiryDate']) ? $_POST['expiryDate'] : null;

        if (!empty($merchantCode) && !empty($amount) && !empty($merchantOrderId) && !empty($signature)) {
            $params = $merchantCode . $amount . $merchantOrderId . $apiKey;
            $calcSignature = md5($params);

            if ($signature == $calcSignature) {
                $prefix = substr($merchantOrderId, 0, 2);
                if ($prefix === 'RF') {
                    $order = CustomFeature::where('order_id', $merchantOrderId)->where('payment_status','PENDING')->first();
                    if($order) {
                        $order->payment_amount = $amount;
                        $order->payment_status = 'PAID';
                        $order->payment_date = date('Y-m-d H:i:s');
                        $order->payment_code = $paymentCode;
                        $order->status = 2;
                        $order->reference = $reference;
                        $order->save();
                    } 

                } else {
                    $pembelian = Pembelian::where('invoice', $merchantOrderId)->where('payment_status', 'PENDING')->first();
                    if ($pembelian) {
                        $pembelian->payment_amount = $amount;
                        $pembelian->payment_status = 'PAID';
                        $pembelian->payment_with = $paymentCode;
                        $pembelian->payment_date = date('Y-m-d H:i:s');
                        $pembelian->reference = $reference;
                        $pembelian->save();

                        $paket = PaketLangganan::find($pembelian->paket_id);
                        $expired_date = null;
                        if ($paket->periode == 1) {
                            $expired_date = Carbon::now()->addMonth()->format('Y-m-d');
                        } elseif ($paket->periode == 2) {
                            $expired_date = Carbon::now()->addYear()->format('Y-m-d');
                        }

                        $company = Company::find($pembelian->comid);
                        $company->paket_id = $pembelian->paket_id;
                        $company->expired_date = $expired_date;
                        $company->is_active = 1;
                        $company->save();
                    }
                }
            } else {
                // file_put_contents('callback.txt', "* Bad Signature *\r\n\r\n", FILE_APPEND | LOCK_EX);
                throw new Exception('Bad Signature');
            }
        } else {
            // file_put_contents('callback.txt', "* Bad Parameter *\r\n\r\n", FILE_APPEND | LOCK_EX);
            throw new Exception('Bad Parameter');
        }
    }
}
