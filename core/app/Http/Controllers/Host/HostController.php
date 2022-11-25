<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\MPESA\mpesautils;
use App\Models\AdminNotification;
use App\Models\GatewayCurrency;
use App\Models\GeneralSetting;
use App\Models\PlanLog;
use App\Models\RentLog;
use App\Models\Wallet;
use App\Rules\FileTypeValidate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use GuzzleHttp\Psr7;


class HostController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
        $this->guard = Auth::guard('host');
    }

    public function home()
    {
        $pageTitle = 'Dashboard';

        //Vehicle booking
        $data['total_vehicle_booking'] = RentLog::active()->where('host_id', \auth('host')->user()->id)->count();
        $data['upcoming_vehicle_booking'] = RentLog::active()->where('host_id', \auth('host')->user()->id)->upcoming()->count();
        $data['running_vehicle_booking'] = RentLog::active()->where('host_id', \auth('host')->user()->id)->running()->count();
        $data['completed_vehicle_booking'] = RentLog::active()->where('host_id', \auth('host')->user()->id)->completed()->count();

        //Plan booking
        $data['total_plan_booking'] = PlanLog::active()->where('host_id', \auth('host')->user()->id)->count();
        $data['upcoming_plan_booking'] = PlanLog::active()->where('host_id', \auth('host')->user()->id)->upcoming()->count();
        $data['running_plan_booking'] = PlanLog::active()->where('host_id', \auth('host')->user()->id)->running()->count();
        $data['completed_plan_booking'] = PlanLog::active()->where('host_id', \auth('host')->user()->id)->completed()->count();

        $logs = $this->guard->user()->deposits()->with(['gateway', 'rent', 'planlog'])->orderBy('id','desc')->take(10)->get();
        // dd(Auth::guard('host')->user()->deposits()->with(['gateway', 'rent', 'planlog'])->get());
        return view($this->activeTemplate . 'host.dashboard', compact('pageTitle', 'logs', 'data'));
    }

    public function profile()
    {
        $pageTitle = "Profile Setting";
        $user = $this->guard->user();
        return view($this->activeTemplate. 'user.profile_setting', compact('pageTitle','user'));
    }

    public function submitProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'address' => 'sometimes|required|max:80',
            'state' => 'sometimes|required|max:80',
            'zip' => 'sometimes|required|max:40',
            'city' => 'sometimes|required|max:50',
            'image' => ['image',new FileTypeValidate(['jpg','jpeg','png'])]
        ],[
            'name.required'=>'Name field is required',
        ]);

        $user = $this->guard->user();

        $in['name'] = $request->name;

        $in['address'] = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => @$user->address->country,
            'city' => $request->city,
        ];


        if ($request->hasFile('image')) {
            $location = imagePath()['profile']['host']['path'];
            $size = imagePath()['profile']['host']['size'];
            $filename = uploadImage($request->image, $location, $size, $user->image);
            $in['image'] = $filename;
        }
        $user->fill($in)->save();
        $notify[] = ['success', 'Profile updated successfully.'];
        return back()->withNotify($notify);
    }

    public function payDetails(){
        $pageTitle = "PAY Details";
        $user = json_decode($this->guard->user()->wallet->pay_details);
        $type = [
            'mobile_wallet' => 'Mobile Wallet',
        ];
        $networks = [
            'Safaricom' => 'Safaricom',
        ];
        return view($this->activeTemplate. 'host.wallet.pay_setting', compact('pageTitle','user', 'type', 'networks'));
    }

    public function submitPayDetails(Request $request)
    {
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

        $user = $this->guard->user();
        $wallet = Wallet::where('host_id', $user->id)->firstorFail();

        $in['pay_details'] = [
            'type' => $request->type,
            'firstName' => $request->first_name,
            'lastName' => $request->last_name,
            'email' => $request->email,
            'phoneNumber' => $request->phone_number,
            'network' => $request->network,
        ];
        $wallet->fill($in)->save();
        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth('host')->id();
        $adminNotification->title = 'Host has updated their PAY Details. Click and Update to Kopokopo';
        $adminNotification->click_url = urlPath('admin.hosts.detail',auth('host')->id());
        $adminNotification->save();

        $notify[] = ['success', 'PAY Details updated successfully.'];
        return back()->withNotify($notify);
    }

    public function changePassword()
    {
        $pageTitle = 'Change password';
        return view($this->activeTemplate . 'user.password', compact('pageTitle'));
    }

    public function submitPassword(Request $request)
    {

        $password_validation = Password::min(6);
        $general = GeneralSetting::first();
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $this->validate($request, [
            'current_password' => 'required',
            'password' => ['required','confirmed',$password_validation]
        ]);


        try {
            $user = $this->guard->user();
            if (Hash::check($request->current_password, $user->password)) {
                $password = Hash::make($request->password);
                $user->password = $password;
                $user->save();
                $notify[] = ['success', 'Password changes successfully.'];
                return back()->withNotify($notify);
            } else {
                $notify[] = ['error', 'The password doesn\'t match!'];
                return back()->withNotify($notify);
            }
        } catch (\PDOException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    public function vehicleBookingLog()
    {
        $pageTitle = 'Vehicle Booking Log';
        $emptyMessage = 'No history found.';
        $booking_logs = RentLog::active()->where('host_id', $this->guard->id())->with(['vehicle', 'user', 'pick_up_location', 'drop_up_location'])->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.vehicle_booking_log', compact('pageTitle', 'emptyMessage', 'booking_logs'));
    }
}
