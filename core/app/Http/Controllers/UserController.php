<?php

namespace App\Http\Controllers;

use App\Lib\GoogleAuthenticator;
use App\Models\AdminNotification;
use App\Models\GeneralSetting;
use App\Models\PlanLog;
use App\Models\Rating;
use App\Models\RentLog;
use App\Models\Vehicle;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function home()
    {
        $pageTitle = 'Dashboard';

        //Vehicle booking
        $data['total_vehicle_booking'] = RentLog::active()->where('user_id', \auth()->id())->count();
        $data['upcoming_vehicle_booking'] = RentLog::active()->where('user_id', \auth()->id())->upcoming()->count();
        $data['running_vehicle_booking'] = RentLog::active()->where('user_id', \auth()->id())->running()->count();
        $data['completed_vehicle_booking'] = RentLog::active()->where('user_id', \auth()->id())->completed()->count();

        //Plan booking
        $data['total_plan_booking'] = PlanLog::active()->where('user_id', \auth()->id())->count();
        $data['upcoming_plan_booking'] = PlanLog::active()->where('user_id', \auth()->id())->upcoming()->count();
        $data['running_plan_booking'] = PlanLog::active()->where('user_id', \auth()->id())->running()->count();
        $data['completed_plan_booking'] = PlanLog::active()->where('user_id', \auth()->id())->completed()->count();

        $logs = Auth::user()->deposits()->with(['gateway', 'rent', 'planlog'])->orderBy('id','desc')->take(10)->get();
        return view($this->activeTemplate . 'user.dashboard', compact('pageTitle', 'logs', 'data'));
    }

    public function profile()
    {
        $pageTitle = "Profile Setting";
        $user = Auth::user();
        return view($this->activeTemplate. 'user.profile_setting', compact('pageTitle','user'));
    }

    public function submitProfile(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'address' => 'sometimes|required|max:80',
            'state' => 'sometimes|required|max:80',
            'zip' => 'sometimes|required|max:40',
            'city' => 'sometimes|required|max:50',
            'image' => ['image',new FileTypeValidate(['jpg','jpeg','png'])]
        ],[
            'firstname.required'=>'First name field is required',
            'lastname.required'=>'Last name field is required'
        ]);

        $user = Auth::user();

        $in['firstname'] = $request->firstname;
        $in['lastname'] = $request->lastname;

        $in['address'] = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => @$user->address->country,
            'city' => $request->city,
        ];


        if ($request->hasFile('image')) {
            $location = imagePath()['profile']['user']['path'];
            $size = imagePath()['profile']['user']['size'];
            $filename = uploadImage($request->image, $location, $size, $user->image);
            $in['image'] = $filename;
        }
        $user->fill($in)->save();
        $notify[] = ['success', 'Profile updated successfully.'];
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
            $user = auth()->user();
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

    /*
     * Payment History
     */
    public function depositHistory()
    {
        $pageTitle = 'Payment History';
        $emptyMessage = 'No history found.';
        $logs = auth()->user()->deposits()->with(['gateway', 'rent', 'planlog'])->orderBy('id','desc')->paginate(getPaginate());
        return view($this->activeTemplate.'user.deposit_history', compact('pageTitle', 'emptyMessage', 'logs'));
    }

    public function show2faForm()
    {
        $general = GeneralSetting::first();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->sitename, $secret);
        $pageTitle = 'Two Factor';
        return view($this->activeTemplate.'user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user,$request->code,$request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->save();
            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_ENABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);
            $notify[] = ['success', 'Google authenticator enabled successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }


    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user,$request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = 0;
            $user->save();
            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_DISABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);
            $notify[] = ['success', 'Two factor authenticator disable successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    // Rating
    public function rating(Request $request, $vehicle_id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|in:1,2,3,4,5',
            'comment' => 'nullable|string'
        ]);

        $vehicle = Vehicle::active()->where('id', $vehicle_id)->first();
        if (!$vehicle) {
            $notify[] = ['error', 'Invalid request!'];
            return back()->withNotify($notify);
        }

        $exist = Rating::where('user_id', auth()->id())->where('vehicle_id', $vehicle_id)->exists();
        if ($exist) {
            $notify[] = ['error', 'Already exist your rating!'];
            return back()->withNotify($notify);
        }

        $rating = new Rating();
        $rating->user_id = auth()->id();
        $rating->vehicle_id = $vehicle_id;
        $rating->rating = $request->rating;
        $rating->comment = $request->comment;
        $rating->save();

        $notify[] = ['success', 'Thanks for your rating!'];
        return back()->withNotify($notify);
    }

    public function vehicleBookingLog()
    {
        $pageTitle = 'Vehicle Booking Log';
        $emptyMessage = 'No history found.';
        $booking_logs = RentLog::where('user_id', auth()->id())->with(['vehicle', 'user', 'pick_up_location', 'drop_up_location'])->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.vehicle_booking_log', compact('pageTitle', 'emptyMessage', 'booking_logs'));
    }

    public function planBookingLog()
    {
        $pageTitle = 'Plan Booking Log';
        $emptyMessage = 'No history found.';
        $booking_logs = PlanLog::active()->where('user_id', auth()->id())->with(['plan', 'user', 'pick_up_location'])->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.plan_booking_log', compact('pageTitle', 'emptyMessage', 'booking_logs'));
    }
}
