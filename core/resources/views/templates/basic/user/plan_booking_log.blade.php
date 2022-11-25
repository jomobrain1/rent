@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="pb-60 pt-60">
        <div class="table-responsive">
            <table class="table cmn--table">
                <thead>
                <tr>
                    <th scope="col">@lang('Plan')</th>
                    <th scope="col">@lang('Price - TRX')</th>
                    <th scope="col">@lang('Pick Location')</th>
                    <th scope="col">@lang('Pick - Drop Time')</th>
                    <th scope="col">@lang('Status')</th>
                </tr>
                </thead>
                <tbody>
                @if(count($booking_logs) > 0)
                    @foreach($booking_logs as $log)
                        <tr>
                            <td data-label="@lang('Plan')">{{ __($log->plan->name) }}</td>
                            <td data-label="@lang('Price - TRX')">{{ showAmount($log->price) }} {{ $general->cur_text }}<br>
                                <strong>{{ @$log->trx }}</strong>
                            </td>
                            <td data-label="@lang('Pick Location')">{{ __($log->pick_up_location->name) }}</td>
                            <td data-label="@lang('Pick - Drop Time')">
                                {{ showDateTime($log->pick_time) }}<br>{{ showDateTime($log->drop_time) }}
                            </td>

                            <td data-label="@lang('Status')">
                                @if($log->pick_time > now())
                                    <span class="text--small badge font-weight-normal badge--primary">@lang('Upcoming')</span>
                                @elseif($log->pick_time < now() && $log->drop_time > now())
                                    <span class="text--small badge font-weight-normal badge--warning">@lang('Running')</span>
                                @elseif($log->drop_time < now())
                                    <span class="text--small badge font-weight-normal badge--success">@lang('Completed')</span>
                                @endif
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
