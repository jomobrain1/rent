@extends($activeTemplate.'layouts.auth')
@section('content')
    <!-- Account Section Starts Here -->
    <div class="account-section pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-6 col-lg-8">
                    <div class="account__wrapper bg--section">
                        <div class="section__header">
                            <h3 class="section__title"><span class="text--base">@lang('Verification') </span>@lang('Code')</h3>
                        </div>
                        <form class="account-form row g-4" method="POST" action="{{ route('user.password.verify.code') }}">
                            @csrf

                            <input type="hidden" name="email" value="{{ $email }}">

                            <div class="col-md-12">
                                <input type="text" name="code" id="code" class="form-control form--control" placeholder="@lang('Enter Verification Code')">
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="cmn--btn btn--lg">@lang('Verify Code')</button>
                            </div>

                            <div class="col-md-12">
                                @lang('Please check including your Junk/Spam Folder. if not found, you can')
                                <a href="{{ route('user.password.request') }}" class="text--base">@lang('Try to send again')</a>
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
