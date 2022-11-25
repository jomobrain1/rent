@php
	$captcha = loadCustomCaptcha();
@endphp
@if($captcha)
    <div class="col-md-{{ request()->routeIs('user.login') ? 12 : 6 }}">
        @php echo $captcha @endphp
    </div>
    <div class="col-md-{{ request()->routeIs('user.login') ? 12 : 6 }}">
        <input type="text" name="captcha" placeholder="@lang('Enter Code')" class="form-control form--control">
    </div>
@endif
