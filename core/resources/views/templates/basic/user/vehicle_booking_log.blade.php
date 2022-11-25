@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="pb-60 pt-60">
        <div class="table-responsive">
            <table class="table cmn--table">
                <thead>
                <tr>
                    <th scope="col">@lang('Vehicle')</th>
                    <th scope="col">@lang('Pick - Drop Location')</th>
                    <th scope="col">@lang('Pick - Drop Time')</th>
                    <th scope="col">@lang('Price - TRX')</th>
                    <th scope="col">@lang('Status')</th>
                </tr>
                </thead>
                <tbody>
                @if(count($booking_logs) > 0)
                    @foreach($booking_logs as $log)
                        <tr>
                            <td data-label="@lang('Vehicle')">
                                <div>{{ __($log->vehicle->name) }}</div>
                            </td>
                            <td data-label="@lang('Pick - Drop Location')">
                                <div>
                                    {{ __(@$log->pick_up_location->name) }} <br>
                                    <strong>{{ __(@$log->drop_up_location->name) }}</strong>
                                </div>
                            </td>
                            <td data-label="@lang('Pick - Drop Time')">
                                <div>
                                    {{ showDateTime($log->pick_time) }}<br><strong>{{ showDateTime($log->drop_time) }}</strong>
                                </div>
                            </td>
                            <td data-label="@lang('Price - TRX')">
                                <div>
                                    {{ showAmount($log->price) }} {{ $general->cur_text }}<br> <strong>{{ @$log->trx }}</strong>
                                </div>
                            </td>

                            <td data-label="@lang('Status')">
                                <div>
                                    @if($log->status == 0)
                                    <a href="{{ route('user.deposit.pending', $log->id)}}"><span class="text--small badge font-weight-normal badge--danger">@lang('Pending Payment')</span></a>
                                    @elseif($log->pick_time > now())
                                        <span class="text--small badge font-weight-normal badge--primary">@lang('Upcoming')</span>
                                    @elseif($log->pick_time < now() && $log->drop_time > now())
                                        <span class="text--small badge font-weight-normal badge--warning">@lang('Running')</span>
                                    @elseif($log->drop_time < now())
                                        <span class="text--small badge font-weight-normal badge--success">@lang('Completed')</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="100%">{{ __($emptyMessage) }}</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>

        {{$booking_logs->links()}}
    </div>

    
@endsection
