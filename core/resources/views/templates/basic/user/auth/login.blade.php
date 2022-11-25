@extends($activeTemplate.'layouts.auth')

@section('content')
    <!-- Account Section Starts Here -->
    <div class="account-section pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-6 col-lg-8">
                    <div class="account__wrapper bg--section">
                        <div class="logo">
                            <a href="{{ route('home') }}" class="d-block"><img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo.png')}}" alt="logo"></a>
                        </div>
                        <form class="account-form row g-4" method="POST" action="{{ route('user.login')}}" onsubmit="return submitUserForm();">
                            @csrf

                            <div class="col-md-12">
                                <label for="username" class="form--label">@lang('Username or Email')</label>
                                <input type="text" name="username" value="{{ old('username') }}" placeholder="@lang('Username or Email')" class="form-control form--control" required>
                            </div>
                            <div class="col-md-12">
                                <label for="password" class="form--label">{{ __('Password') }}</label>
                                <input id="password" type="password" class="form-control form--control" placeholder="@lang('Password')" name="password" required>
                            </div>

                            <div class="col-md-12 d-flex flex-wrap justify-content-center">
                                @php echo loadReCaptcha() @endphp
                            </div>
                            @include($activeTemplate.'partials.custom_captcha')

                            <div class="col-md-12">
                                <div class="form-check form--check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        @lang('Remember Me')
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" id="recaptcha" class="cmn--btn btn--lg">@lang('Sign In')</button>
                            </div>
                            <div class="col-md-12">
                                <div class="d-flex flex-wrap justify-content-between">
                                    <a href="{{route('user.password.request')}}" class="text--base">@lang('Forgot Password?')</a>
                                    <div>
                                        @lang('New here?') <a href="{{ route('user.register') }}" class="text--base">@lang('Create Account')</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Account Section Ends Here -->
@endsection

@push('script')
    <script>
        "use strict";
        function submitUserForm() {
            var response = grecaptcha.getResponse();
            if (response.length == 0) {
                document.getElementById('g-recaptcha-error').innerHTML = '<span class="text-danger">@lang("Captcha field is required.")</span>';
                return false;
            }
            return true;
        }
    </script>
@endpush
