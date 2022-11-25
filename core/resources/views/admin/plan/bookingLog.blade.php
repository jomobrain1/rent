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
                                <th scope="col">@lang('Plan')</th>
                                <th scope="col">@lang('Price - TRX')</th>
                                <th scope="col">@lang('Pick Location')</th>
                                <th scope="col">@lang('Pick - Drop Time')</th>
                                <th scope="col">@lang('Status')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($plan_logs as $log)
                                <tr>
                                    <td data-label="@lang('User')">
                                        <span class="font-weight-bold">{{ $log->user->fullname }}</span>
                                        <br>
                                        <span class="small"> <a href="{{ route('admin.users.detail', $log->user_id) }}"><span>@</span>{{ $log->user->username }}</a> </span>
                                    </td>

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
                    {{ $plan_logs->links('admin.partials.paginate') }}
                </div>
            </div><!-- card end -->
        </div>
    </div>
@endsection
