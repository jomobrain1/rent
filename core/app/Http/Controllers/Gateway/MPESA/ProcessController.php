<?php

namespace App\Http\Controllers\Gateway\MPESA;

use App\Models\Deposit;
use App\Models\GeneralSetting;
use App\Http\Controllers\Gateway\PaymentController;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\WithdrawalRequests;
use App\Models\WithdrawalTransactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProcessController extends Controller
{

    /*
     * MPESA Gateway
     */

    public static function process($deposit) {
        $basic =  GeneralSetting::first();

        $gateway_currency = $deposit->gatewayCurrency();

        $perfectAcc = json_decode($gateway_currency->gateway_parameter);
        $alias = $deposit->gateway->alias;
        $access_token = mpesautils::get_token($perfectAcc);
        $result = mpesautils::send_stk($perfectAcc, $access_token, $deposit);
        if (isset($result['data'])) {
            $data = $result['data'];
            $data['error'] = $data['errorCode'];
            if($data['error'] == 400){
                $data['message'] = $data['errorMessage'];
            } if($data['error'] == 401){
                $data['message'] = 'Returns unauthorised';
            }else {
                $data['message'] = 'Transaction has experienced an error';
            }
            return json_encode($data);
        }
        if (isset($result['status'])) {
            if($result['status'] == 'success'){
                $result['redirect_url'] = gatewayRedirectUrl(true);
                $result['view'] = 'user.payment.' . $alias;
                return json_encode($result);
            }
        }
        $result['message'] = 'Transaction has experienced an error 2';
        return json_encode($result);
    }

    public function ipn(Request $request) {
        $res = json_decode(json_encode($request->data));
        if($res->status == 'Success'){
            $client = $res->metadata;
            $deposit = Deposit::where('trx', $client->reference)->orderBy('id', 'DESC')->first();
            $transact = new Transaction();
            $transact->transaction_id = $res->id;
            $transact->transaction_reference = $res->reference;
            $transact->originationTime = $res->originationTime;
            $transact->amount = $res->amount;
            $transact->status = $res->status;
            $transact->deposit_trx = $client->reference;
            $transact->notes = $client->notes;
            $transact->client_id = $client->customerId;
            $transact->save();

            PaymentController::userDataUpdate($deposit->trx);
            $notify[] = ['success', 'Transaction was successful'];
            return response('success', 200);
        } else {
            $notify[] = ['error', "Invalid Request"];
            return response('error', 404);
        }
        //Update User Data
    }

    public function mpesa_payment(Request $request){
        $res = $request['data'];
        if($res->status == 'Sent'){
            $client = json_decode($res->metadata);
            $withd = WithdrawalRequests::where('reference', $client->reference)->orderBy('id', 'DESC')->first();
            $transact = new WithdrawalTransactions();
            $transact->transaction_id = $res->id;
            $transact->time = $res->createdAt;
            $transact->amount = $res->amount;
            $transact->status = $res->status;
            $transact->request_reference = $client->reference;
            $transact->notes = $client->notes;
            $transact->host_id = $client->customer_id;
            $transact->save();

            PaymentController::hostRequestUpdate($withd->reference);
        }
    }

}
