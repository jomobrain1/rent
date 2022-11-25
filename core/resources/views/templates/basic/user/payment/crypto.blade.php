@extends($activeTemplate.'layouts.master')

@section('content')
    <div class="pb-60 pt-60">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="deposit-preview bg--body">
                    <div class="deposit-thumb">
                        <img src="{{$data->img}}" alt="payment">
                    </div>
                    <div class="deposit-content">
                        <ul>
                            <li>
                                @lang('PLEASE SEND EXACTLY')
                                <span class="text--success">{{ $data->amount }}{{__($data->currency)}}</span>
                            </li>
                            <li>
                                @lang('TO')
                                <span class="text--danger">{{ $data->sendto }}</span>
                            </li>
                            <li>
                                <strong>@lang('SCAN TO SEND')</strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
