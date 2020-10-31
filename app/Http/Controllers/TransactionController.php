<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use Veritrans_Config;
use Veritrans_Snap;
use Veritrans_Notification;

class TransactionController extends Controller
{
    public function __construct()
    {
        Veritrans_Config::$serverKey = config('services.midtrans.serverKey');
        Veritrans_Config::$isProduction = config('services.midtrans.isProduction');
        Veritrans_Config::$isSanitized = config('services.midtrans.isSanitized');
        Veritrans_Config::$is3ds = config('services.midtrans.is3ds');
    }
    public function index()
    {
        $transactions = Transaction::orderBy('id', 'DESC')->paginate(8);
        return view ('rtrwpay.index', compact('transactions'));
    }
    public function create()
    {
        return view ('rtrwpay.transaction');
    }
    public function store(Request $request)
    {
        \DB::transaction(function () use ($request){
            $transaction = Transaction::create([
                'transaction_code'      =>  'SANDBOX-' . uniqid(),
                'transaction_name'      =>  $request->transaction_name,
                'transaction_email'     =>  $request->transaction_email,
                'transaction_type'      =>  $request->transaction_type,
                'block_home'            =>  $request->block_home,
                'home_number'           =>  $request->home_number,
                'amount'                =>  \floatval($request->amount),
                'note'                  =>  ($request->note),
 
            ]);
            $payload = [
                        'transaction_details' => [
                            'order_id'      =>  $transaction->transaction_code,
                            'gross_amount'  =>  $transaction->amount,
                        ],
                        'customer_details'  => [
                            'first_name'    => $transaction->transaction_name,
                            'email'         => $transaction->transaction_email,
                            // 'phone'         => '08888888888',
                            // 'address'       => '',     
                        ],
                        'item_details'      =>[
                            [
                                'id'       => $transaction->transaction_type,
                                'price'    => $transaction->amount,
                                'quantity' => 1,
                                'name'     => ucwords(str_replace('_', ' ', $transaction->transaction_type))
                            ]
                        ]
                    ];
            $snapToken = Veritrans_Snap::getSnapToken($payload);
            $transaction->snap_token = $snapToken;
            $transaction->save();
        
            $this->response['snap_token'] = $snapToken;
        }); 
        return response()->json($this->response);

    }
    public function notification(){
        $notif = new Veritrans_Notification();
        \DB::transaction(function () use ($notif) {
            $transactionStatus = $notif->transaction_status;
            $paymentType       = $notif->payment_type;
            $orderId           = $notif->order_id;
            $fraudStatus       = $notif->fraud_status;
            $transaction          = Transaction::where('transaction_code', $orderId)->first();

            if($transactionStatus   == 'capture'){
                if($paymentType     == 'credit _card'){
                    if($fraudStatus == 'challenge'){
                        $transaction->setStatusPending();
                    }else{
                        $transaction->setStatusSuccess();
                    }
                }
            } elseif ($transactionStatus == 'settlement'){
                $transaction->setStatusSuccess();
            } elseif ($transactionStatus == 'pending'){
                $transaction->setStatusPending();
            } elseif ($transactionStatus == 'deny'){
                $transaction->setStatusFailed();
            } elseif ($transactionStatus == 'expired'){
                $transaction->setStatusExpired();
            } elseif ($transactionStatus == 'cancel'){
                $transaction->setStatusCancel();
            }

        });
        return;
    }
}
