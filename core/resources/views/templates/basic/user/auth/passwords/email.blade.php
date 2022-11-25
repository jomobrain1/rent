@extends($activeTemplate.'layouts.auth')

@section('content')
    <!-- Account Section Starts Here -->
    <div class="account-section pt-120 pb-120">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-6 col-lg-8">
                    <div class="account__wrapper bg--section">
                        <div class="section__header">
                            <h3 class="section__title"><span class="text--base">@lang('Reset') </span>@lang('Password')</h3>
                        </div>
                        <form class="account-form row g-4" method="POST" action="{{ route('user.password.email') }}">
                            @csrf

                            <div class="col-md-12">
                                <label for="username" class="form--label">@lang('Select One')</label>
                                <select class="form--control" name="type">
                                    <option value="email">@lang('E-Mail Address')</option>
                                    <option value="username">@lang('Username')</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form--label my_value"></label>
                                <input type="text" class="form-control form--control @error('value') is-invalid @enderror" name="value" value="{{ old('value') }}" required autofocus="off">
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="cmn--btn btn--lg">@lang('Send Password Code')</button>
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

        myVal();
        $('select[name=type]').on('change',function(){
            myVal();
        });
        function myVal(){
            $('.my_value').text($('select[name=type] :selected').text());
        }
    })(jQuery)
</script>
@endpush
