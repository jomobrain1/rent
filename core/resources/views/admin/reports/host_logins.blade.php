@extends('admin.layouts.app')

@section('panel')
    <div class="row">

        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">

                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                            <tr>
                                <th>@lang('Host')</th>
                                <th>@lang('Login at')</th>
                                <th>@lang('IP')</th>
                                <th>@lang('Location')</th>
                                <th>@lang('Browser | OS')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($login_logs as $log)
                                <tr>

                                <td data-label="@lang('Host')">
                                    <span class="font-weight-bold">{{ @$log->host->name }}</span>
                                    <br>
                                    <span class="small"> <a href="{{ route('admin.hosts.detail', $log->host_id) }}"><span>@</span>{{ @$log->host->username }}</a> </span>
                                </td>


                                    <td data-label="@lang('Login at')">
                                        {{showDateTime($log->created_at) }} <br> {{diffForHumans($log->created_at) }}
                                    </td>



                                    <td data-label="@lang('IP')">
                                        <span class="font-weight-bold">
                                        <a href="{{route('admin.report.host.login.ipHistory',[$log->host_ip])}}">{{ $log->host_ip }}</a>
                                        </span>
                                    </td>

                                    <td data-label="@lang('Location')">{{ __($log->city) }} <br> {{ __($log->country) }}</td>
                                    <td data-label="@lang('Browser | OS')">
                                        {{ __($log->browser) }} <br> {{ __($log->os) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                <div class="card-footer py-4">
                    {{ paginateLinks($login_logs) }}
                </div>
            </div><!-- card end -->
        </div>


    </div>
@endsection



@push('breadcrumb-plugins')
    @if(request()->routeIs('admin.report.host.login.history'))
    <form action="{{ route('admin.report.host.login.history') }}" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Search Username')" value="{{ $search ?? '' }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
    @endif
@endpush
@if(request()->routeIs('admin.report.host.login.ipHistory'))
    @push('breadcrumb-plugins')
    <a href="https://www.ip2location.com/{{ $ip }}" target="_blank" class="btn btn--primary">@lang('Lookup IP') {{ $ip }}</a>
    @endpush
@endif
