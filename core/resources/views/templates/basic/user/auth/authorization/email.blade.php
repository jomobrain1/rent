@extends($activeTemplate .'layouts.auth')
@section('content')
    <!-- Account Section Starts Here -->
    <div class="account-section pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-6 col-lg-8">
                    <div class="account__wrapper bg--section">
                        <div class="section__header">
                            <h4 class="section__title"><span class="text--base">@lang('Please Verify Your Email to') </span>@lang('Get Access')</h4>
                        </div>
                        <form class="account-form row g-4" method="POST" action="{{route('user.verify.email')}}">
                            @csrf

                            <div class="col-md-12">
                                <p class="text-center">@lang('Your Email'):  <strong>{{auth()->user()->email}}</strong></p>
                            </div>
                            <div class="col-md-12">
                                <label class="form--label">@lang('Verification Code')</label>
                                <input type="text" name="email_verified_code" class="form-control form--control" maxlength="7" id="code">
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="cmn--btn btn--lg">@lang('Submit')</button>
                            </div>
                            <div class="col-md-12">
                                <p>@lang('Please check including your Junk/Spam Folder. if not found, you can') <a href="{{route('user.send.verify.code')}}?type=email" class="forget-pass text--base"> @lang('Resend code')</a></p>
                                @if ($errors->has('resend'))
                                    <br/>
                                    <small class="text-danger">{{ $errors->first('resend') }}</small>
                                @endif
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
    (function($){
        "use strict";
        $('#code').on('input change', function () {
          var xx = document.getElementById('code').value;

              $(this).val(function (index, value) {
                 value = value.substr(0,7);
                  return value.replace(/\W/gi, '').replace(/(.{3})/g, '$1 ');
              });

      });
    })(jQuery)
</script>
@endpush
