<?php

namespace App\Http\Controllers\Host\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminNotification;
use App\Models\GeneralSetting;
use App\Models\Host;
use App\Models\User;
use App\Models\HostLogin;
use App\Models\Wallet;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function __construct() {
        $this->middleware('regStatus')->except('registrationNotAllowed');
        $this->middleware('guest:host');
        $this->activeTemplate = activeTemplate();
    }

    public function showRegistrationForm() {
        $pageTitle = "Sign Up As Host";
        $info = json_decode(json_encode(getIpInfo()), true);
        $mobile_code = @implode(',', $info['code']);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view($this->activeTemplate . 'host.auth.register', compact('pageTitle','mobile_code','countries'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $general = GeneralSetting::first();
        $password_validation = Password::min(6);
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }
        $agree = 'nullable';
        if ($general->agree) {
            $agree = 'required';
        }
        $countryData = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes = implode(',',array_column($countryData, 'dial_code'));
        $countries = implode(',',array_column($countryData, 'country'));
        $validate = Validator::make($data, [
            'firstname' => 'sometimes|required|string|max:50',
            'lastname' => 'sometimes|required|string|max:50',
            'email' => 'required|string|email|max:90|unique:hosts',
            'mobile' => 'required|string|max:50|unique:hosts',
            'password' => ['required','confirmed',$password_validation],
            'username' => 'required|alpha_num|unique:hosts|min:6',
            'captcha' => 'sometimes|required',
            'mobile_code' => 'required|in:'.$mobileCodes,
            'country_code' => 'required|in:'.$countryCodes,
            'country' => 'required|in:'.$countries,
            'agree' => $agree
        ]);
        return $validate;
    }

    public function register(Request $request) {
        $this->validator($request->all())->validate();
        $exist = Host::where('mobile',$request->mobile_code.$request->mobile)->first();
        if ($exist) {
            $notify[] = ['error', 'The mobile number already exists'];
            return back()->withNotify($notify)->withInput();
        }

        if (isset($request->captcha)) {
            if (!captchaVerify($request->captcha, $request->captcha_secret)) {
                $notify[] = ['error', "Invalid captcha"];
                return back()->withNotify($notify)->withInput();
            }
        }

        event(new Registered($host = $this->create($request->all())));

        Auth::guard('host')->attempt($request->only('email', 'password'));

        return $this->registered($request, $host)
            ?: redirect($this->redirectPath());
    }


    /**
     * Create a new host instance after a valid registration.
     *
     * @param  array $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $general = GeneralSetting::first();

        //Host Create
        $host = new Host();
        $host->name = (isset($data['firstname']) ? $data['firstname'] : " ") . " ". (isset($data['lastname']) ? $data['lastname'] : " ");
        $host->email = strtolower(trim($data['email']));
        $host->password = Hash::make($data['password']);
        $host->username = trim($data['username']);
        $host->country_code = $data['country_code'];
        $host->mobile = $data['mobile_code'].$data['mobile'];
        $host->address = [
            'address' => '',
            'state' => '',
            'zip' => '',
            'country' => isset($data['country']) ? $data['country'] : null,
            'city' => ''
        ];
        $host->status = 1;
        $host->ev = $general->ev ? 0 : 1;
        $host->sv = $general->sv ? 0 : 1;
        $host->save();


        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $host->id;
        $adminNotification->title = 'New Host registered';
        $adminNotification->click_url = urlPath('admin.users.detail',$host->id);
        $adminNotification->save();

        $wallet = new Wallet();
        $wallet->host_id = $host->id;
        $wallet->pay_details = [
            'type' => '',
        ];
        $wallet->save();


        //Login Log Create
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = HostLogin::where('host_ip',$ip)->first();
        $hostLogin = new HostLogin();

        //Check exist or not
        if ($exist) {
            $hostLogin->longitude =  $exist->longitude;
            $hostLogin->latitude =  $exist->latitude;
            $hostLogin->city =  $exist->city;
            $hostLogin->country_code = $exist->country_code;
            $hostLogin->country =  $exist->country;
        }else{
            $info = json_decode(json_encode(getIpInfo()), true);
            $hostLogin->longitude =  @implode(',',$info['long']);
            $hostLogin->latitude =  @implode(',',$info['lat']);
            $hostLogin->city =  @implode(',',$info['city']);
            $hostLogin->country_code = @implode(',',$info['code']);
            $hostLogin->country =  @implode(',', $info['country']);
        }

        $userAgent = osBrowser();
        $hostLogin->host_id = $host->id;
        $hostLogin->host_ip =  $ip;

        $hostLogin->browser = @$userAgent['browser'];
        $hostLogin->os = @$userAgent['os_platform'];
        $hostLogin->save();

        return $host;
    }

    public function checkHost(Request $request){
        $exist['data'] = null;
        $exist['type'] = null;
        if ($request->email) {
            $exist['data'] = Host::where('email',$request->email)->first();
            $exist['type'] = 'email';
        }
        if ($request->mobile) {
            $exist['data'] = Host::where('mobile',$request->mobile)->first();
            $exist['type'] = 'mobile';
        }
        if ($request->username) {
            $exist['data'] = Host::where('username',$request->username)->first();
            $exist['type'] = 'username';
        }
        return response($exist);
    }

    public function registered() {
        return redirect()->route('host.dashboard');
    }
}
