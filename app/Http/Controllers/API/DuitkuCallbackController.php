<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CustomFeature;
use App\Models\Kandang;
use App\Models\Lokasi;
use App\Models\PaketLangganan;
use App\Models\Pembelian;
use App\Models\Reseller;
use App\Models\Satpam;
use App\Models\User;
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
                        $active_id = $company->paket_id;
                        $company->paket_id = $pembelian->paket_id;
                        $company->expired_date = $expired_date;
                        $company->is_active = 1;
                        $company->save();

                        $this->setting_feature($active_id, $pembelian->paket_id, $pembelian->comid) ;
                        $this->count_referal_fee($company, $pembelian->id);

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


    protected function count_referal_fee($com, $pembelian_id) 
    {
        $referal_code = $com->referal_code;
        $pembelian = Pembelian::find($pembelian_id);
        if($referal_code !== null && !empty($referal_code)) {
            $reseller = Reseller::where('referal_code', $referal_code)->first();
            if($reseller) {
                if($pembelian->payment_status == 'PAID') {
                    $percent = $reseller->percent_fee;
                    $nilai = $percent * $pembelian->amount / 100;

                    $pembelian->referal_fee = $nilai;
                    $pembelian->save();
                }
                

            }
        }
    }

    protected function setting_feature($active_id, $new_id, $comid) 
    {
        if($active_id === $new_id) {

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
            

            if($jumlah_satpam_baru < $jumlah_satpam_lama) {
                Satpam::where('comid', $comid)->update([
                    "is_active" => 0
                ]);
            }
            
            if($jumlah_lokasi_baru < $jumlah_lokasi_lama) {
                Lokasi::where('comid', $comid)->update([
                    "is_active" => 0
                ]);
            }

            if($jumlah_user_baru < $jumlah_user_lama) {
                User::where('company_id', $comid)
                ->where('level', 'user')
                ->update([
                    "is_active" => 0
                ]);
            }

            if($jumlah_farm_baru < $jumlah_farm_lama) {
                Kandang::where('comid', $comid)
                ->update(['is_active'=> 0]);
            }

            if($paket_baru->is_user_area !== 1) {
                User::where('company_id', $comid)
                ->update([
                    'is_area' => 0
                ]);
            }

        }
    }
}
