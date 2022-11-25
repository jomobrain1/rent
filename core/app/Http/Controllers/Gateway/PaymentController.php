<?php

namespace App\Http\Controllers\Gateway;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\GeneralSetting;
use App\Models\Host;
use App\Models\PlanLog;
use App\Models\RentLog;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawalRequests;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        return $this->activeTemplate = activeTemplate();
    }

    public function deposit()
    {
        if (!session()->has('rent_id') && !session()->has('plan_id')){
            $notify[] = ['error', 'Invalid request!'];
            return back()->withNotify($notify);
        }

        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->with('method')->orderby('method_code')->get();
        $pageTitle = 'Payment Methods';
        return view($this->activeTemplate . 'user.payment.deposit', compact('gatewayCurrency', 'pageTitle'));
    }

    public function depositInsert(Request $request) {
        $request->validate([
            'method_code' => 'required',
            'currency' => 'required',
        ]);

        if (!session()->has('rent_id') && !session()->has('plan_id')){
            $notify[] = ['error', 'Invalid request!'];
            return back()->withNotify($notify);
        }

        $user = auth()->user();
        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', 1);
        })->where('method_code', $request->method_code)->where('currency', $request->currency)->first();
        if (!$gate) {
            $notify[] = ['error', 'Invalid gateway'];
            return back()->withNotify($notify);
        }

        if (session()->has('rent_id')){
            $rent_log = RentLog::findOrFail(session('rent_id'));
            $amount = $rent_log->price;
            $pickprice=$rent_log->price_picked_area;
            $host_id = $rent_log->host_id;
        }

        if ($gate->min_amount > $amount || $gate->max_amount < $amount) {
            $notify[] = ['error', 'Please follow payment limit'];
            return back()->withNotify($notify);
        }

        $charge = $gate->fixed_charge + ($amount * $gate->percent_charge / 100);
        $payable = $amount + $charge;
        
        $final_amo =( $payable * $gate->rate ) + $pickprice ;

        $data = new Deposit();
        $data->user_id = $user->id;
        $data->host_id = $host_id;
        $data->rent_id = session('rent_id') ?? 0;
        $data->plan_id = session('plan_id') ?? 0;
        $data->method_code = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount = $amount;
        $data->charge = $charge;
        $data->rate = $gate->rate;
        $data->final_amo = $final_amo;
        $data->btc_amo = 0;
        $data->btc_wallet = "";
        $data->trx = getTrx();
        $data->try = 0;
        $data->status = 0;
        $data->save();
        session()->put('Track', $data->trx);
        return redirect()->route('user.deposit.preview');
    }


    public function depositPreview()
    {
        if (!session()->has('rent_id') && !session()->has('plan_id')){
            $notify[] = ['error', 'Invalid request!'];
            return back()->withNotify($notify);
        }

        $track = session()->get('Track');
        $data = Deposit::where('trx', $track)->where('status',0)->orderBy('id', 'DESC')->firstOrFail();
        $pageTitle = 'Payment Preview';
        return view($this->activeTemplate . 'user.payment.preview', compact('data', 'pageTitle'));
    }

    public function depositPending($log_id){
        $rent = RentLog::findOrFail($log_id);
        session()->put('rent_id', $log_id);
        $data = Deposit::where('rent_id', $log_id)->where('status',0)->firstOrFail();
        session()->put('Track', $data->trx);
        return redirect()->route('user.deposit.preview');
    }


    public function depositConfirm() {
        $track = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->where('status',0)->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

        if ($deposit->method_code >= 1000) {
            $this->userDataUpdate($deposit);
            $notify[] = ['success', 'Your payment request is queued for approval.'];
            return back()->withNotify($notify);
        }


        $dirName = $deposit->gateway->alias;
        $new = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';
        $client= json_decode($deposit->gatewayCurrency()->gateway_parameter);

        $data = $new::process($deposit);
        $data = json_decode($data);

        if (isset($data->error) || isset($data->status) && $data->status == 'error') {
            $notify[] = ['error', $data->message];
            return redirect()->route(gatewayRedirectUrl())->withNotify($notify);
        }else if (isset($data->redirect_url)) {
            $notify[] = ['success', 'Transaction is being processed'];
            return redirect()->route($data->redirect_url)->withNotify($notify);
        }

        // for Stripe V3
        if(@$data->session){
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        $pageTitle = 'Payment Confirm';
        return view($this->activeTemplate . $data->view, compact('data', 'pageTitle', 'deposit'));
    }

    public static function userDataUpdate($trx) {
        $general = GeneralSetting::first();
        $data = Deposit::where('trx', $trx)->first();
        if ($data->status == 0) {
            $data->status = 1;
            $data->save();

            $user = User::find($data->user_id);

            //update status
            $rent_log = RentLog::findOrFail($data->rent_id);
            $rent_log->status = 1;
            $rent_log->trx = $data->trx;
            $rent_log->save();


            $adminNotification = new AdminNotification();
            $adminNotification->user_id = $user->id;
            $adminNotification->title = 'Payment successful via '.$data->gatewayCurrency()->name;
            $adminNotification->click_url = urlPath('admin.deposit.successful');
            $adminNotification->save();

            $wallet = Wallet::firstWhere('host_id', $data->host_id);
            $total_amount = floatval($wallet->total_amount);
            $wallet->total_amount = $total_amount + (floatval($data->amount) - (floatval($data->amount) * $general->percentage_charge / 100));
            $wallet->save();

            // notify($user, 'PAYMENT_COMPLETE', [
            //     'method_name' => $data->gatewayCurrency()->name,
            //     'method_currency' => $data->method_currency,
            //     'method_amount' => showAmount($data->final_amo),
            //     'amount' => showAmount($data->amount),
            //     'charge' => showAmount($data->charge),
            //     'currency' => $general->cur_text,
            //     'rate' => showAmount($data->rate),
            //     'trx' => $data->trx
            // ]);

            //Forget session
            session()->forget(['rent_id', 'plan_id']);
        }
    }
    public static function hostRequestUpdate($reference) {
        $general = GeneralSetting::first();
        $data = WithdrawalRequests::where('reference', $reference)->first();
        $data->status = 4;
        $data->save();

        $host = Host::find($data->host_id);

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $host->id;
        $adminNotification->title = 'Withdrawal Successful';
        $adminNotification->click_url = urlPath('admin.requests.successful');
        $adminNotification->save();

        $wallet = Wallet::where('host_id', $data->host_id);
        $wallet->total_amount -= $data->amount;
        $wallet->total_withdrawn +=$data->amount;
        $wallet->save();

        $host->fullname = $host->name;
        notify($host, 'WITHDRAWAL_COMPLETE', [
            'amount' => showAmount($data->amount),
            'currency' => $general->cur_text,
        ]);

    }

    public function manualDepositConfirm()
    {
        if (!session()->has('rent_id') && !session()->has('plan_id')){
            $notify[] = ['error', 'Invalid request!'];
            return back()->withNotify($notify);
        }

        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', 0)->where('trx', $track)->first();
        if (!$data) {
            return redirect()->route(gatewayRedirectUrl());
        }
        if ($data->method_code > 999) {

            $pageTitle = 'Payment Confirm';
            $method = $data->gatewayCurrency();
            return view($this->activeTemplate . 'user.manual_payment.manual_confirm', compact('data', 'pageTitle', 'method'));
        }
        abort(404);
    }

    public function manualDepositUpdate(Request $request)
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', 0)->where('trx', $track)->first();
        if (!$data) {
            return redirect()->route(gatewayRedirectUrl());
        }

        $params = json_decode($data->gatewayCurrency()->gateway_parameter);

        $rules = [];
        $inputField = [];
        $verifyImages = [];

        if ($params != null) {
            foreach ($params as $key => $custom) {
                $rules[$key] = [$custom->validation];
                if ($custom->type == 'file') {
                    array_push($rules[$key], 'image');
                    array_push($rules[$key], new FileTypeValidate(['jpg','jpeg','png']));
                    array_push($rules[$key], 'max:2048');

                    array_push($verifyImages, $key);
                }
                if ($custom->type == 'text') {
                    array_push($rules[$key], 'max:191');
                }
                if ($custom->type == 'textarea') {
                    array_push($rules[$key], 'max:300');
                }
                $inputField[] = $key;
            }
        }
        $this->validate($request, $rules);


        $directory = date("Y")."/".date("m")."/".date("d");
        $path = imagePath()['verify']['deposit']['path'].'/'.$directory;
        $collection = collect($request);
        $reqField = [];
        if ($params != null) {
            foreach ($collection as $k => $v) {
                foreach ($params as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal->type == 'file') {
                            if ($request->hasFile($inKey)) {
                                try {
                                    $reqField[$inKey] = [
                                        'field_name' => $directory.'/'.uploadImage($request[$inKey], $path),
                                        'type' => $inVal->type,
                                    ];
                                } catch (\Exception $exp) {
                                    $notify[] = ['error', 'Could not upload your ' . $inKey];
                                    return back()->withNotify($notify)->withInput();
                                }
                            }
                        } else {
                            $reqField[$inKey] = $v;
                            $reqField[$inKey] = [
                                'field_name' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }
            $data->detail = $reqField;
        } else {
            $data->detail = null;
        }



        $data->status = 2; // pending
        $data->save();


        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $data->user->id;
        $adminNotification->title = 'Payment request from '.$data->user->username;
        $adminNotification->click_url = urlPath('admin.deposit.details',$data->id);
        $adminNotification->save();

        $general = GeneralSetting::first();
        notify($data->user, 'PAYMENT_REQUEST', [
            'method_name' => $data->gatewayCurrency()->name,
            'method_currency' => $data->method_currency,
            'method_amount' => showAmount($data->final_amo),
            'amount' => showAmount($data->amount),
            'charge' => showAmount($data->charge),
            'currency' => $general->cur_text,
            'rate' => showAmount($data->rate),
            'trx' => $data->trx
        ]);

        //Forget session
        session()->forget(['rent_id', 'plan_id']);

        $notify[] = ['success', 'You\'r payment request has been taken.'];
        return redirect()->route('user.deposit.history')->withNotify($notify);
    }


}
