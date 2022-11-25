@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="pb-60 pt-60">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="deposit-preview bg--body">
                    <div class="deposit-thumb">
                        <img src="{{ $data->gatewayCurrency()->methodImage() }}" alt="payment">
                    </div>
                    <div class="deposit-content">
                        <ul>
                             
                            <li>
                                @lang('Amount'):
                                <span class="text--success">{{showAmount($data->amount)}} {{__($general->cur_text)}}</span>
                            </li>
                            <li>
                                @lang('Charge'):
                                <span class="text--danger">{{showAmount($data->charge)}} {{__($general->cur_text)}}</span>
                            </li>
                            <li>
                                @lang('Payable'): <span class="text--warning"> {{showAmount($data->amount + $data->charge)}} {{__($general->cur_text)}}</span> 
                            </li>
                            <li>
                                @lang('Pickup charges'): 
                                <span class="text--warning" id="pick">
                                     
                                </span> 
                            </li>
                            <li>
                                @lang('In') {{$data->baseCurrency()}}:
                                <span class="text--primary">{{showAmount($data->final_amo)}}</span>
                            </li>

                            @if($data->gateway->crypto==1)
                                <li>
                                    @lang('Conversion with')
                                    <b> {{ __($data->method_currency) }}</b> @lang('and final value will Show on next step')
                                </li>
                            @endif
                        </ul>

                        <div class="text-center w-100">
                            @if( 1000 >$data->method_code)
                                <a href="{{route('user.deposit.confirm')}}" class="cmn--btn">@lang('Pay Now')</a>
                            @else
                                <a href="{{route('user.deposit.manual.confirm')}}" class="cmn--btn">@lang('Pay Now')</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
<script>
    let bk= localStorage.getItem('bk')
    let pick=document.querySelector('#pick')
    pick.innerText=bk + ".00 KES"
    // alert(bk)
    
</script>
    
@endpush