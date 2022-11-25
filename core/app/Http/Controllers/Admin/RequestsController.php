<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\MPESA\mpesautils;
use App\Models\GatewayCurrency;
use App\Models\GeneralSetting;
use App\Models\Host;
use App\Models\RentLog;
use App\Models\Wallet;
use App\Models\WithdrawalRequests;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RequestsController extends Controller
{
    public function pending()
    {
        $pageTitle = 'Pending Requests';
        $emptyMessage = 'No pending requests.';
        $requests = WithdrawalRequests::where('status', 1)->with(['host'])->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.request.log', compact('pageTitle', 'emptyMessage', 'requests'));
    }


    public function approved()
    {
        $pageTitle = 'Approved Requests';
        $emptyMessage = 'No approved requests.';
        $requests = WithdrawalRequests::where('status', 2)->with(['host'])->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.request.log', compact('pageTitle', 'emptyMessage', 'requests'));
    }

    public function successful()
    {
        $pageTitle = 'Successful Requests';
        $emptyMessage = 'No successful requests.';
        $requests = WithdrawalRequests::where('status', 4)->with(['host'])->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.request.log', compact('pageTitle', 'emptyMessage', 'requests'));
    }

    public function rejected()
    {
        $pageTitle = 'Rejected Requests';
        $emptyMessage = 'No rejected requests.';
        $requests = WithdrawalRequests::where('status', 3)->with(['host'])->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.request.log', compact('pageTitle', 'emptyMessage', 'requests'));
    }

    public function requests()
    {
        $pageTitle = 'Request History';
        $emptyMessage = 'No request history available.';
        $requests = WithdrawalRequests::with(['host'])->where('status','!=',0)->orderBy('id','desc')->paginate(getPaginate());
        $successful = WithdrawalRequests::where('status',4)->sum('amount');
        $pending = WithdrawalRequests::where('status',1)->sum('amount');
        $rejected = WithdrawalRequests::where('status',3)->sum('amount');
        return view('admin.request.log', compact('pageTitle', 'emptyMessage', 'requests','successful','pending','rejected'));
    }

    public function search(Request $request, $scope)
    {
        $search = $request->search;
        $emptyMessage = 'No search result was found.';
        $requests = WithdrawalRequests::with(['host'])->where('status','!=',0)->where(function ($q) use ($search) {
            $q->whereHas('host', function ($user) use ($search) {
                $user->where('username', 'like', "%$search%");
            });
        });
        if ($scope == 'pending') {
            $pageTitle = 'Pending Payments Search';
            $requests = $requests->where('status', 1);
        }elseif($scope == 'approved'){
            $pageTitle = 'Approved Payments Search';
            $requests = $requests->where('status', 2);
        }elseif($scope == 'rejected'){
            $pageTitle = 'Rejected Payments Search';
            $requests = $requests->where('status', 3);
        }else{
            $pageTitle = 'Payments History Search';
        }

        $requests = $requests->paginate(getPaginate());
        $pageTitle .= '-' . $search;

        return view('admin.request.log', compact('pageTitle', 'search', 'scope', 'emptyMessage', 'requests'));
    }

    public function dateSearch(Request $request,$scope = null){
        $search = $request->date;
        if (!$search) {
            return back();
        }
        $date = explode('-',$search);
        $start = @$date[0];
        $end = @$date[1];
        // date validation
        $pattern = "/\d{2}\/\d{2}\/\d{4}/";
        if ($start && !preg_match($pattern,$start)) {
            $notify[] = ['error','Invalid date format'];
            return redirect()->route('admin.requests.list')->withNotify($notify);
        }
        if ($end && !preg_match($pattern,$end)) {
            $notify[] = ['error','Invalid date format'];
            return redirect()->route('admin.requests.list')->withNotify($notify);
        }


        if ($start) {
            $requests = WithdrawalRequests::where('status','!=',0)->whereDate('created_at',Carbon::parse($start));
        }
        if($end){
            $requests = WithdrawalRequests::where('status','!=',0)->whereDate('created_at','>=',Carbon::parse($start))->whereDate('created_at','<=',Carbon::parse($end));
        }
        if ($scope == 'pending') {
            $requests = $requests->where('status', 1);
        }elseif($scope == 'approved'){
            $requests = $requests->where('status', 2);
        }elseif($scope == 'rejected'){
            $requests = $requests->where('status', 3);
        }
        $requests = $requests->with(['host'])->orderBy('id','desc')->paginate(getPaginate());
        $pageTitle = ' Requests Log';
        $emptyMessage = 'No Requests Found';
        $dateSearch = $search;
        return view('admin.request.log', compact('pageTitle', 'emptyMessage', 'requests','dateSearch','scope'));
    }

    public function details($id)
    {
        $general = GeneralSetting::first();
        $request = WithdrawalRequests::where('id', $id)->with('host')->firstOrFail();
        $pageTitle = $request->host->name.' requested ' . showAmount($request->amount) . ' '.$general->cur_text;
        $details = json_decode($request->host->wallet->pay_details);
        return view('admin.request.detail', compact('pageTitle', 'request','details'));
    }


    public function approve(Request $request) {
        $request->validate(['id' => 'required|integer']);
        $request = WithdrawalRequests::where('id',$request->id)->where('status',1)->firstOrFail();
        $request->status = 2;
        $request->save();

        $notify[] = ['success', 'Payment request has been approved.'];

        return redirect()->route('admin.requests.pending')->withNotify($notify);
    }

    public function reject(Request $request){
        $request->validate([
            'id' => 'required|integer',
            'message' => 'required|max:250'
        ]);
        $request = WithdrawalRequests::where('id',$request->id)->firstOrFail();

        $request->admin_feedback = $request->message;
        $request->status = 3;
        $request->save();

        $request->host->fullname = $request->host->name;

        $general = GeneralSetting::first();
        // notify($request->host, 'WITHDRAWAL_REJECTED', [
        //     'amount' => showAmount($request->amount),
        //     'currency' => $general->cur_text,
        //     'rejection_message' => $request->message
        // ]);

        $notify[] = ['success', 'Withdrawal request has been rejected.'];
        return  redirect()->route('admin.requests.pending')->withNotify($notify);

    }

    public function send(Request $request) {
        $withd = WithdrawalRequests::where('id',$request->id)->firstOrFail();
        $gateway = GatewayCurrency::where('gateway_alias', 'MPESA')->firstOrFail();
        $host = $withd->host;

        $perfectAcc = json_decode($gateway->gateway_parameter);
        $access_token = mpesautils::get_token($perfectAcc);
        $wallet = json_decode($host->wallet->pay_details);
        $send_pay = mpesautils::send_payment($perfectAcc, $access_token, $withd, $wallet);
        if($send_pay['status'] == 'success'){
            $general = GeneralSetting::first();

            $request->validate(['id' => 'required|integer']);
            $withd->status = 4;
            $withd->save();

            $wallet = Wallet::firstWhere('host_id', $host->id);
            $total_amount = floatval($wallet->total_amount);
            $total_withdrawn = floatval($wallet->total_withdrawn);
            $wallet->total_amount = $total_amount - floatval($withd->amount) ;
            $wallet->total_withdrawn = $total_withdrawn + floatval($withd->amount);
            $wallet->save();

            $host->fullname = $host->name;
            // notify($host, 'WITHDRAWAL_APPROVE', [
            //     'amount' => showAmount($request->amount),
            //     'currency' => $general->cur_text,
            // ]);
            $notify[] = ['success', 'Withdrawal request has been approved and sent to the owner.'];

            return redirect()->route('admin.requests.successful')->withNotify($notify);
        } else {
            dd($send_pay);
            $notify[] = ['error', 'Withdrawal request was not processed successfully'];
            return redirect()->route('admin.requests.pending')->withNotify($notify);
        }

    }
}
