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

                        <form action="{{ route('ipn.'.$deposit->gateway->alias) }}" method="POST">
                            @csrf

                            <button type="button" class="cmn--btn custom-success" id="btn-confirm">@lang('Pay Now')</button>
                            <script
                                src="//js.paystack.co/v1/inline.js"
                                data-key="{{ $data->key }}"
                                data-email="{{ $data->email }}"
                                data-amount="{{$data->amount}}"
                                data-currency="{{$data->currency}}"
                                data-ref="{{ $data->ref }}"
                                data-custom-button="btn-confirm"
                            >
                            </script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
