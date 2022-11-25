<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Wallet;
use App\Models\WithdrawalRequests;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(){
        $this->activeTemplate = activeTemplate();
    }

    public function requests() {
        $wallet = Wallet::where('host_id', auth('host')->id())->firstOrFail();
        $requests = WithdrawalRequests::where('host_id', auth('host')->id())->latest()->paginate(getPaginate());
        $pageTitle = 'Wallet';
        return view($this->activeTemplate.'host.wallet.requests',compact('requests','pageTitle', 'wallet'));
    }

    public function updateRequests(Request $request){
        $request->validate([
            'amount' => ['required','numeric','gt:0',
                    function($attribute, $value, $fail){
                        $wallet = Wallet::where('host_id', auth('host')->id())->firstOrFail();
                        if($value > $wallet->total_amount){
                            $fail('The '. $attribute. ' should be less than what you have in your wallet');
                        }
                    }
            ],
        ]);
        $withdrawal = new WithdrawalRequests();
        $withdrawal->reference = getTrx();
        $withdrawal->amount = $request->amount;
        $withdrawal->host_id = auth('host')->id();
        $withdrawal->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth('host')->id();
        $adminNotification->title = 'New Withdrawal Request';
        $adminNotification->click_url = urlPath('admin.requests.pending');
        $adminNotification->save();

        $notify[] = ['success', 'Withdrawal Request Sent Successfully!'];
        return back()->withNotify($notify);
    }

}
