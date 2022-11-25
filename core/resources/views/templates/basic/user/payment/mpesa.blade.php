@extends($activeTemplate.'layouts.master')

@section('content')
    <div class="pb-60 pt-60">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="deposit-preview bg--body">
                    <div class="deposit-thumb">
                        <img src="{{$deposit->gatewayCurrency()->methodImage()}}" alt="payment">
                    </div>
                    <div class="deposit-content">
                        <ul>
                            <li>
                                <h4>@lang('Please Pay'):
                                    <span class="text--primary">{{showAmount($deposit->final_amo)}} {{__($deposit->method_currency)}}</span></h4>
                            </li>
                        </ul>
                        <form action="{{ route('ipn.MPESA') }}" method="post">
                            <input type="hidden" name="reference" value="8Z3P9U8VVNOZ" >
                            <input type="hidden" name="transaction_id" value="8Z3P9U8VVNOZ" >

                            <input type="submit" value="submit" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        (function ($) {
            "use strict";
            $('input[type="submit"]').addClass("cmn--btn");
        })(jQuery);
    </script>
@endpush
