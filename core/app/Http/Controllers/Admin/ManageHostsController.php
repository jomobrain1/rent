<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\MPESA\mpesautils;
use App\Models\Deposit;
use App\Models\EmailLog;
use App\Models\GatewayCurrency;
use App\Models\Host;
use App\Models\HostEmailLogs;
use App\Models\RentLog;
use App\Models\Vehicle;
use App\Models\Wallet;
use App\Models\WithdrawalRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageHostsController extends Controller
{
    public function allHosts()
    {
        $pageTitle = 'Manage Vehicle Owners';
        $emptyMessage = 'No owners found';
        $hosts = Host::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.hosts.list', compact('pageTitle', 'emptyMessage', 'hosts'));
    }

    public function activeHosts()
    {
        $pageTitle = 'Manage Active Car Owners';
        $emptyMessage = 'No active owners found';
        $hosts = Host::active()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.hosts.list', compact('pageTitle', 'emptyMessage', 'hosts'));
    }

    public function bannedHosts()
    {
        $pageTitle = 'Banned Car Owners';
        $emptyMessage = 'No banned owners found';
        $hosts = Host::banned()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.hosts.list', compact('pageTitle', 'emptyMessage', 'hosts'));
    }

    public function emailUnverifiedHosts()
    {
        $pageTitle = 'Email Unverified Owners';
        $emptyMessage = 'No email unverified owners found';
        $hosts = Host::emailUnverified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.hosts.list', compact('pageTitle', 'emptyMessage', 'hosts'));
    }
    public function emailVerifiedHosts()
    {
        $pageTitle = 'Email Verified Owners';
        $emptyMessage = 'No email verified owners found';
        $hosts = Host::emailVerified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.hosts.list', compact('pageTitle', 'emptyMessage', 'hosts'));
    }

    public function smsUnverifiedHosts()
    {
        $pageTitle = 'SMS Unverified Owners';
        $emptyMessage = 'No sms unverified owner found';
        $hosts = Host::smsUnverified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.hosts.list', compact('pageTitle', 'emptyMessage', 'hosts'));
    }


    public function smsVerifiedHosts()
    {
        $pageTitle = 'SMS Verified Owners';
        $emptyMessage = 'No sms verified owner found';
        $hosts = Host::smsVerified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.hosts.list', compact('pageTitle', 'emptyMessage', 'hosts'));
    }

    public function detail($id)
    {
        $pageTitle = 'Owner Detail';
        $host = Host::findOrFail($id);
        $wallet = json_decode($host->wallet->pay_details);

        $totalDeposit = Deposit::where('host_id',$host->id)->where('status',1)->sum('amount');
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $type = [
            'mobile_wallet' => 'Mobile Wallet',
        ];
        $networks = [
            'Safaricom' => 'Safaricom',
        ];

        //Vehicle booking
        $data['total_vehicle_booking'] = RentLog::active()->where('host_id', $host->id)->count();
        $data['upcoming_vehicle_booking'] = RentLog::active()->where('host_id', $host->id)->upcoming()->count();
        $data['running_vehicle_booking'] = RentLog::active()->where('host_id', $host->id)->running()->count();
        $data['completed_vehicle_booking'] = RentLog::active()->where('host_id', $host->id)->completed()->count();
        $data['total_vehicles'] = Vehicle::where('host_id', $host->id)->count();


        return view('admin.hosts.detail', compact('pageTitle', 'host','totalDeposit','countries', 'type', 'networks', 'data','wallet'));
    }

    public function search(Request $request, $scope)
    {
        $search = $request->search;
        $hosts = Host::where(function ($host) use ($search) {
            $host->where('username', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        });
        $pageTitle = '';
        if ($scope == 'active') {
            $pageTitle = 'Active ';
            $hosts = $hosts->where('status', 1);
        }elseif($scope == 'banned'){
            $pageTitle = 'Banned';
            $hosts = $hosts->where('status', 0);
        }elseif($scope == 'emailUnverified'){
            $pageTitle = 'Email Unverified ';
            $hosts = $hosts->where('ev', 0);
        }elseif($scope == 'smsUnverified'){
            $pageTitle = 'SMS Unverified ';
            $hosts = $hosts->where('sv', 0);
        }elseif($scope == 'withBalance'){
            $pageTitle = 'With Balance ';
            $hosts = $hosts->where('balance','!=',0);
        }

        $hosts = $hosts->paginate(getPaginate());
        $pageTitle .= 'Car Owner Search - ' . $search;
        $emptyMessage = 'No search result found';
        return view('admin.hosts.list', compact('pageTitle', 'search', 'scope', 'emptyMessage', 'hosts'));
    }

    public function deposits(Request $request, $id)
    {
        $host = Host::findOrFail($id);
        $hostId = $host->id;
        if ($request->search) {
            $search = $request->search;
            $pageTitle = 'Search Owner Earnings : ' . $host->username;
            $deposits = $host->deposits()->where('trx', $search)->orderBy('id','desc')->paginate(getPaginate());
            $emptyMessage = 'No deposits';
            return view('admin.deposit.log', compact('pageTitle', 'search', 'host', 'deposits', 'emptyMessage','hostId'));
        }

        $pageTitle = 'Owner Earnings : ' . $host->username;
        $deposits = $host->deposits()->orderBy('id','desc')->paginate(getPaginate());
        $successful = $deposits->where('status',1)->sum('amount');
        $pending = $deposits->where('status',2)->sum('amount');
        $rejected = $deposits->where('status',3)->sum('amount');
        $emptyMessage = 'No deposits';
        $scope = 'all';
        return view('admin.deposit.log', compact('pageTitle', 'host', 'deposits', 'emptyMessage','hostId','scope','successful','pending','rejected'));
    }

    public function update(Request $request, $id)
    {
        $host = Host::findOrFail($id);

        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        $request->validate([
            'name' => 'required|max:50',
            'username' => 'required|max:50',
            'email' => 'required|email|max:90|unique:hosts,email,' . $host->id,
            'mobile' => 'required|unique:hosts,mobile,' . $host->id,
            'country' => 'required',
        ]);
        $countryCode = $request->country;
        $host->mobile = $request->mobile;
        $host->country_code = $countryCode;
        $host->name = $request->name;
        $host->username = $request->username;
        $host->email = $request->email;
        $host->address = [
                            'address' => $request->address,
                            'city' => $request->city,
                            'state' => $request->state,
                            'zip' => $request->zip,
                            'country' => @$countryData->$countryCode->country,
                        ];
        $host->status = $request->status ? 1 : 0;
        $host->ev = $request->ev ? 1 : 0;
        $host->sv = $request->sv ? 1 : 0;
        $host->save();

        $notify[] = ['success', 'Owner detail has been updated'];
        return redirect()->back()->withNotify($notify);
    }

    public function pay_update(Request $request, $id)
    {
        $wallet = Wallet::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|max:50',
            'phone_number' => 'required|numeric|digits:12',
            'network' => 'required|string|max:50',
        ],[
            'first_name.required'=>'First Name field is required',
            'phone_number.digits'=>'Phone Number must be in the format 254712121212'
        ]);

        $in['pay_details'] = [
            'type' => $request->type,
            'firstName' => $request->first_name,
            'lastName' => $request->last_name,
            'email' => $request->email,
            'phoneNumber' => $request->phone_number,
            'network' => $request->network,
        ];
        $gateway = GatewayCurrency::where('gateway_alias', 'MPESA')->firstOrFail();
        $perfectAcc = json_decode($gateway->gateway_parameter);
        $access_token = mpesautils::get_token($perfectAcc);
        $add_pay = mpesautils::add_recipient($perfectAcc, $access_token, $in['pay_details']);
        if($add_pay['status'] == 'success'){
            $location = parse_url($add_pay['location'], PHP_URL_PATH);
            $paths = explode("/", $location);
            $in['pay_details']['reference'] = $paths[4];
            $wallet->fill($in)->save();
            $notify[] = ['success', 'PAY Details updated successfully.'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'PAY Details were not saved.If this persists, please check on the kopokopo dashboard.'];
            return back()->withNotify($notify);
        }
    }

    public function wallet($id)
    {
        $pageTitle = 'Owner Wallet';
        $emptyMessage = 'No request history available.';
        $wallet = Wallet::findOrFail($id);
        $host = $wallet->host;

        $requests = $host->requests()->with('host')->orderBy('id','desc')->paginate(getPaginate());
        $successful = WithdrawalRequests::where('host_id', $host->id)->successful()->sum('amount');;
        $pending = WithdrawalRequests::where('host_id', $host->id)->pending()->sum('amount');;
        $rejected = WithdrawalRequests::where('host_id', $host->id)->rejected()->sum('amount');;
        return view('admin.request.log', compact('pageTitle', 'emptyMessage', 'wallet', 'requests','successful','pending','rejected'));
    }

    public function showEmailAllForm()
    {
        $pageTitle = 'Send Email To All Car Owners';
        return view('admin.hosts.email_all', compact('pageTitle'));
    }

    public function sendEmailAll(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        foreach (Host::where('status', 1)->cursor() as $host) {
            sendGeneralEmail($host->email, $request->subject, $request->message, $host->username);
        }

        $notify[] = ['success', 'All users will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function login($id){
        $host = Host::findOrFail($id);
        Auth('host')->login($host);
        return redirect()->route('host.dashboard');
    }

    public function hostLoginHistory($id) {
        $host = Host::findOrFail($id);
        $pageTitle = 'Car Owner Login History - ' . $host->hostname;
        $emptyMessage = 'Car Owner logins not found.';
        $login_logs = $host->login_logs()->orderBy('id','desc')->with('host')->paginate(getPaginate());
        return view('admin.hosts.logins', compact('pageTitle', 'emptyMessage', 'login_logs'));
    }

    public function showEmailSingleForm($id)
    {
        $host = Host::findOrFail($id);
        $pageTitle = 'Send Email To: ' . $host->hostname;
        return view('admin.hosts.email_single', compact('pageTitle', 'host'));
    }

    public function sendEmailSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        $host = Host::findOrFail($id);
        sendGeneralEmail($host->email, $request->subject, $request->message, $host->hostname);
        $notify[] = ['success', $host->hostname . ' will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function emailLog($id){
        $host = Host::findOrFail($id);
        $pageTitle = 'Email log of '.$host->hostname;
        $logs = HostEmailLogs::where('host_id',$id)->with('host')->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = 'No data found';
        return view('admin.hosts.email_log', compact('pageTitle','logs','emptyMessage','host'));
    }

    public function emailDetails($id){
        $email = HostEmailLogs::findOrFail($id);
        $pageTitle = 'Email details';
        return view('admin.hosts.email_details', compact('pageTitle','email'));
    }

}
