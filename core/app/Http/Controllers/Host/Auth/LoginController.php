<?php

namespace App\Http\Controllers\Host\Auth;

use App\Models\Extension;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\HostLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $username;

    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        $this->middleware('guest:host')->except('logout');

        $this->username = $this->findUsername();
    }

    public function showLoginForm(){
        $pageTitle = "Sign In";
        return view(activeTemplate() . 'host.auth.login', compact('pageTitle'));
    }

    protected function guard()
    {
        return Auth::guard('host');
    }


    public function login(Request $request){
        $this->validateLogin($request);

        if(isset($request->captcha)){
            if(!captchaVerify($request->captcha, $request->captcha_secret)){
                $notify[] = ['error',"Invalid captcha"];
                return back()->withNotify($notify)->withInput();
            }
        }

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }


        if (Auth::guard('host')->attempt($request->only('email', 'password'))) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);


        return $this->sendFailedLoginResponse($request);
    }

    public function findUsername()
    {
        $login = request()->input('username');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $login]);
        return $fieldType;
    }

    public function username()
    {
        return $this->username;
    }

    protected function validateLogin(Request $request)
    {
        $customRecaptcha = Extension::where('act', 'custom-captcha')->where('status', 1)->first();
        $validation_rule = [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ];

        if ($customRecaptcha) {
            $validation_rule['captcha'] = 'required';
        }

        $request->validate($validation_rule);

    }

    public function logout()
    {
        $this->guard()->logout();

        request()->session()->invalidate();

        $notify[] = ['success', 'You have been logged out.'];
        return redirect()->route('host.login')->withNotify($notify);
    }


    public function authenticated(Request $request, $user)
    {
        if ($user->status == 0) {
            $this->guard()->logout();
            $notify[] = ['error','Your account has been deactivated.'];
            return redirect()->route('host.login')->withNotify($notify);
        }


        $user = auth()->guard('host')->user();
        $ip = $_SERVER["REMOTE_ADDR"];
        $exist = HostLogin::where('host_ip',$ip)->first();
        $HostLogin = new HostLogin();
        if ($exist) {
            $HostLogin->longitude =  $exist->longitude;
            $HostLogin->latitude =  $exist->latitude;
            $HostLogin->city =  $exist->city;
            $HostLogin->country_code = $exist->country_code;
            $HostLogin->country =  $exist->country;
        }else{
            $info = json_decode(json_encode(getIpInfo()), true);
            $HostLogin->longitude =  @implode(',',$info['long']);
            $HostLogin->latitude =  @implode(',',$info['lat']);
            $HostLogin->city =  @implode(',',$info['city']);
            $HostLogin->country_code = @implode(',',$info['code']);
            $HostLogin->country =  @implode(',', $info['country']);
        }

        $userAgent = osBrowser();
        $HostLogin->host_id = $user->id;
        $HostLogin->host_ip =  $ip;

        $HostLogin->browser = @$userAgent['browser'];
        $HostLogin->os = @$userAgent['os_platform'];
        $HostLogin->save();

        return redirect()->route('host.dashboard');
    }


}

