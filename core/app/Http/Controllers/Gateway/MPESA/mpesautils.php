<?php

namespace App\Http\Controllers\Gateway\MPESA;

use App\Models\Deposit;
use App\Models\GeneralSetting;
use App\Http\Controllers\Gateway\PaymentController;
use App\Http\Controllers\Controller;
use App\Models\AuthToken;
use Illuminate\Support\Facades\Auth;
use Kopokopo\SDK\K2;
use Illuminate\Support\Facades\Http;

class mpesautils extends Controller
{

    /*
     * MPESA Functions
     */

    public static function get_token($client){
        $auth = AuthToken::first();
        if(!$auth && ($auth->expires_in > time())){
            return $auth->access_token;
        } else {
            $options = [
                'clientId' => $client->clientId ,
                'clientSecret' => $client->clientSecret,
                'apiKey' => $client->clientApi,
                'baseUrl' => $client->baseUrl
            ];
            $K2 = new K2($options);

            // Get one of the services
            $tokens = $K2->TokenService();

            // Use the service
            $result = $tokens->getToken();
            if($result['status'] == 'success'){
                $data = $result['data'];
                if($auth){
                    $auth->access_token = $data['accessToken'];
                    $auth->tokenType = $data['tokenType'];
                    $auth->created_at = $data['createdAt'];
                    $auth->expires_in = $data['createdAt'] + $data['expiresIn'];
                    $auth->save();
                } else {
                    $auth = new AuthToken();
                    $auth->access_token = $data['accessToken'];
                    $auth->tokenType = $data['tokenType'];
                    $auth->created_at = $data['createdAt'];
                    $auth->expires_in = $data['createdAt'] + $data['expiresIn'];
                    $auth->save();
                }
                return $auth->access_token;
            } else {
                return $result;
            }
        }

    }

    public static function token_information($client, $access_token){
        $options = [
            'clientId' => $client->clientId ,
            'clientSecret' => $client->clientSecret,
            'apiKey' => $client->clientApi,
            'baseUrl' => $client->baseUrl
        ];
        $K2 = new K2($options);

        // Get one of the services
        $tokens = $K2->TokenService();

        // Use the service
        $result = $tokens->infoToken([
            'accessToken' => $access_token
        ]);
        dd($result);
    }

    public static function send_stk($client, $access_token, $deposit){
        $alias = $deposit->gateway->alias;
        $options = [
            'clientId' => $client->clientId ,
            'clientSecret' => $client->clientSecret,
            'apiKey' => $client->clientApi,
            'baseUrl' => $client->baseUrl
        ];
        $K2 = new K2($options);
        $stk = $K2->StkService();
        $response = $stk->initiateIncomingPayment([
            'paymentChannel' => 'M-PESA STK Push',
            'tillNumber' => $client->tillNumber,
            'firstName' => $deposit->user->firstname,
            'lastName' => $deposit->user->lastname,
            'phoneNumber' => '+'.$deposit->user->mobile,
            'amount' => $deposit->final_amo,
            'currency' => 'KES',
            'email' => $deposit->user->email,
            'callbackUrl' => route('ipn.'.$alias),
            'metadata' => [
                'customerId' => $deposit->user->id,
                'reference' => $deposit->trx,
                'notes' => 'Payment for invoice '. $deposit->trx
            ],
            'accessToken' => $access_token,
        ]);
        return $response;
    }

    public static function add_recipient($client, $access_token, $details){
        $options = [
            'clientId' => $client->clientId ,
            'clientSecret' => $client->clientSecret,
            'apiKey' => $client->clientApi,
            'baseUrl' => $client->baseUrl
        ];
        $K2 = new K2($options);
        $pay = $K2->PayService();
        $details['accessToken'] = $access_token;
        $details['phoneNumber'] = '+'.$details['phoneNumber'];
        $response = $pay->addPayRecipient($details);
        return $response;
    }

    public static function send_payment($client, $access_token, $request, $wallet){
        $options = [
            'clientId' => $client->clientId ,
            'clientSecret' => $client->clientSecret,
            'apiKey' => $client->clientApi,
            'baseUrl' => $client->baseUrl
        ];
        $K2 = new K2($options);
        $pay = $K2->PayService();

        $response = $pay->sendPay([
            'destinationType' => $wallet->type,
            'destinationReference' =>$wallet->reference,
            'amount' => $request->amount,
            'currency' => 'KES',
            'description' => 'Withdrawal Request',
            'category' => 'general',
            'tags' => ["withdrawal_request"],
            'callbackUrl' => route('ipn.mpesa_payment'),
            'metadata' => [
                'requestId' => $request->id,
                'reference' => $request->reference,
                'notes' => 'Withdrawal Request for - ' . $request->host->name,
            ],
            'accessToken' => $access_token
        ]);
        return $response;
    }
}
