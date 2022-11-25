@extends('admin.layouts.app')

@section('panel')
<div class="row justify-content-center">
    @if(request()->routeIs('admin.requests.list') || request()->routeIs('admin.hosts.wallet') )
        <div class="col-md-4 col-sm-6 mb-30">
            <div class="widget-two box--shadow2 b-radius--5 bg--success">
            <div class="widget-two__content">
                <h2 class="text-white">{{ __($general->cur_sym) }}{{ showAmount($successful) }}</h2>
                <p class="text-white">@lang('Successful Withdrawals')</p>
            </div>
            </div><!-- widget-two end -->
        </div>
        <div class="col-md-4 col-sm-6 mb-30">
            <div class="widget-two box--shadow2 b-radius--5 bg--6">
                <div class="widget-two__content">
                    <h2 class="text-white">{{ __($general->cur_sym) }}{{ showAmount($pending) }}</h2>
                    <p class="text-white">@lang('Pending Requests')</p>
                </div>
            </div><!-- widget-two end -->
        </div>
        <div class="col-md-4 col-sm-6 mb-30">
            <div class="widget-two box--shadow2 b-radius--5 bg--pink">
            <div class="widget-two__content">
                <h2 class="text-white">{{ __($general->cur_sym) }}{{ showAmount($rejected) }}</h2>
                <p class="text-white">@lang('Rejected Requests')</p>
            </div>
            </div><!-- widget-two end -->
        </div>
    @endif

    <div class="col-md-12">
        <div class="card b-radius--10">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                            <th>@lang('Requested Date')</th>
                            <th>@lang('Host')</th>
                            <th>@lang('Amount')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Action')</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($requests as $request)
                            @php
                                $details = $request->detail ? json_encode($request->detail) : null;
                            @endphp
                            <tr>
                                <td data-label="@lang('Requested Date')">
                                    {{ showDateTime($request->created_at) }}<br>{{ diffForHumans($request->created_at) }}
                                </td>
                                <td data-label="@lang('Host')">
                                    <span class="font-weight-bold">{{ $request->host->name }}</span>
                                    <br>
                                    <span class="small">
                                    <a href="{{ route('admin.hosts.detail', $request->host_id) }}"><span>@</span>{{ $request->host->username }}</a>
                                    </span>
                                </td>
                                <td data-label="@lang('Amount')">
                                   {{ __($general->cur_sym) }}{{ showAmount($request->amount ) }} + <span class="text-danger" data-toggle="tooltip" data-original-title="@lang('charge')">{{ showAmount($request->charge)}} </span>
                                    <br>
                                    <strong data-toggle="tooltip" data-original-title="@lang('Amount with charge')">
                                    {{ showAmount($request->amount+$request->charge) }} {{ __($general->cur_text) }}
                                    </strong>
                                </td>
                                <td data-label="@lang('Status')">
                                    @if($request->status == 1)
                                        <span class="badge badge--warning">@lang('Pending')</span>
                                    @elseif($request->status == 2)
                                        <span class="badge badge--info">@lang('Approved')</span>
                                         <br>{{ diffForHumans($request->updated_at) }}
                                    @elseif($request->status == 3)
                                        <span class="badge badge--danger">@lang('Rejected')</span>
                                        <br>{{ diffForHumans($request->updated_at) }}
                                        @elseif($request->status == 4)
                                        <span class="badge badge--success">@lang('Successfully Withdrawn')</span>
                                        <br>{{ diffForHumans($request->updated_at) }}
                                    @endif
                                </td>
                                <td data-label="@lang('Action')">
                                    <a href="{{ route('admin.requests.details', $request->id) }}"
                                       class="icon-btn ml-1 " data-toggle="tooltip" title="" data-original-title="@lang('Detail')">
                                        <i class="la la-desktop"></i>
                                    </a>
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
                {{ paginateLinks($requests) }}
            </div>
        </div><!-- card end -->
    </div>
</div>


@endsection


@push('breadcrumb-plugins')
    @if(!request()->routeIs('admin.requests.one'))
        <form action="{{route('admin.requests.search', $scope ?? str_replace('admin.requests.', '', request()->route()->getName()))}}" method="GET" class="form-inline float-sm-right bg--white mb-2 ml-0 ml-xl-2 ml-lg-0">
            <div class="input-group has_append  ">
                <input type="text" name="search" class="form-control" placeholder="@lang('Trx number/Username')" value="{{ $search ?? '' }}">
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <form action="{{route('admin.requests.dateSearch',$scope ?? str_replace('admin.requests.', '', request()->route()->getName()))}}" method="GET" class="form-inline float-sm-right bg--white">
            <div class="input-group has_append ">
                <input name="date" type="text" data-range="true" data-multiple-dates-separator=" - " data-language="en" class="datepicker-here form-control" data-position='bottom right' placeholder="@lang('Min date - Max date')" autocomplete="off" value="{{ @$dateSearch }}">
                <input type="hidden" name="method" value="{{ @$methodAlias }}">
                <div class="input-group-append">
                    <button class="btn btn--primary" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

    @endif
@endpush


@push('script-lib')
  <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush
@push('script')
  <script>
    (function($){
        "use strict";
        if(!$('.datepicker-here').val()){
            $('.datepicker-here').datepicker();
        }
    })(jQuery)
  </script>
@endpush
