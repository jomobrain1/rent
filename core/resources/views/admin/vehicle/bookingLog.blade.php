@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light tabstyle--two">
                            <thead>
                            <tr>
                                <th scope="col">@lang('User')</th>
                                <th scope="col">@lang('Vehicle')</th>
                                <th scope="col">@lang('Pick - Drop Location')</th>
                                <th scope="col">@lang('Pick - Drop Time')</th>
                                <th scope="col">@lang('Price - TRX')</th>
                                <th scope="col">@lang('Status')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($booking_logs as $log)
                                <tr>
                                    <td data-label="@lang('User')">
                                        <span class="font-weight-bold">{{ $log->user->fullname }}</span>
                                        <br>
                                        <span class="small"> <a href="{{ route('admin.users.detail', $log->user_id) }}"><span>@</span>{{ $log->user->username }}</a> </span>
                                    </td>

                                    <td data-label="@lang('Vehicle')">{{ __($log->vehicle->name) }}</td>
                                    <td data-label="@lang('Pick - Drop Location')">{{ __(@$log->pick_up_location->name) }} <br>
                                        <strong>{{ __(@$log->drop_up_location->name) }}</strong>
                                    </td>
                                    <td data-label="@lang('Pick - Drop Time')">
                                        {{ showDateTime($log->pick_time) }}<br><strong>{{ showDateTime($log->drop_time) }}</strong>
                                    </td>
                                    <td data-label="@lang('Price - TRX')">
                                        {{ showAmount($log->price) }} {{ $general->cur_text }}<br> <strong>{{ @$log->trx }}</strong>
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
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($empty_message) }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer">
                    {{ $booking_logs->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>
@endsection
