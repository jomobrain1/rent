@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-4 col-md-6 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Requested Date')
                            <span class="font-weight-bold">{{ showDateTime($request->created_at) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Username')
                            <span class="font-weight-bold">
                                <a href="{{ route('admin.hosts.detail', $request->host_id) }}">{{ @$request->host->username }}</a>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Amount')
                            <span class="font-weight-bold">{{ showAmount($request->amount ) }} {{ __($general->cur_text) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @if($request->status == 1)
                                <span class="badge badge-pill bg--warning">@lang('Pending')</span>
                            @elseif($request->status == 2)
                                <span class="badge badge-pill bg--info">@lang('Approved')</span>
                            @elseif($request->status == 3)
                                <span class="badge badge-pill bg--danger">@lang('Rejected')</span>
                            @elseif($request->status == 4)
                                <span class="badge badge-pill bg--success">@lang('Successful')</span>
                            @endif
                        </li>
                        @if($request->admin_feedback)
                            <li class="list-group-item">
                                <strong>@lang('Admin Response')</strong>
                                <br>
                                <p>{{__($request->admin_feedback)}}</p>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-md-6 mb-30">
            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body">
                    <h5 class="card-title mb-50 border-bottom pb-2">@lang('User Wallet Information')</h5>
                    @if($details != null)
                        @foreach($details as $k => $val)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h6>{{inputTitle($k)}}</h6>
                                    <p>{{__($val)}}</p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    @if($request->status == 1)
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <button class="btn btn--info ml-1 approveBtn"
                                        data-id="{{ $request->id }}"
                                        data-info="{{$details->type}}"
                                        data-amount="{{ showAmount($request->amount)}} {{ __($general->cur_text) }}"
                                        data-username="{{ @$request->host->username }}"
                                        data-toggle="tooltip" data-original-title="@lang('Approve')"><i class="fas fa-check"></i>
                                    @lang('Approve')
                                </button>

                                <button class="btn btn--success ml-1 sendBtn"
                                        data-id="{{ $request->id }}"
                                        data-info="{{$details->type}}"
                                        data-amount="{{ showAmount($request->amount)}} {{ __($general->cur_text) }}"
                                        data-username="{{ @$request->host->username }}"
                                        data-toggle="tooltip" data-original-title="@lang('Approve and Send')"><i class="fas fa-check"></i>
                                    @lang('Approve and Send')
                                </button>

                                <button class="btn btn--danger ml-1 rejectBtn"
                                        data-id="{{ $request->id }}"
                                        data-info="{{$details->type}}"
                                        data-amount="{{ showAmount($request->amount)}} {{ __($general->cur_text) }}"
                                        data-username="{{ @$request->host->username }}"
                                        data-toggle="tooltip" data-original-title="@lang('Reject')"><i class="fas fa-ban"></i>
                                    @lang('Reject')
                                </button>
                            </div>
                        </div>
                    @endif
                    @if($request->status == 2)
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <button class="btn btn--success ml-1 sendBtn"
                                    data-id="{{ $request->id }}"
                                    data-info="{{$details->type}}"
                                    data-amount="{{ showAmount($request->amount)}} {{ __($general->cur_text) }}"
                                    data-username="{{ @$request->host->username }}"
                                    data-toggle="tooltip" data-original-title="@lang('Send')"><i class="fas fa-check"></i>
                                @lang('Send')
                            </button>

                            <button class="btn btn--danger ml-1 rejectBtn"
                                    data-id="{{ $request->id }}"
                                    data-info="{{$details->type}}"
                                    data-amount="{{ showAmount($request->amount)}} {{ __($general->cur_text) }}"
                                    data-username="{{ @$request->host->username }}"
                                    data-toggle="tooltip" data-original-title="@lang('Reject')"><i class="fas fa-ban"></i>
                                @lang('Reject')
                            </button>
                        </div>
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>


    {{-- APPROVE MODAL --}}
    <div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Approve Withdrawal Request')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.requests.approve')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('approve')</span> <span class="font-weight-bold withdraw-amount text-success"></span> @lang('withdrawal of') <span class="font-weight-bold withdraw-user"></span>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--success">@lang('Approve')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SEND MODAL --}}
    <div id="sendModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Send Withdrawal Request')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.requests.send')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('send')</span> <span class="font-weight-bold withdraw-amount text-success"></span> @lang('to') <span class="font-weight-bold withdraw-user"></span>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--success">@lang('Send')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- REJECT MODAL --}}
    <div id="rejectModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Reject Withdrawal Requests')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.requests.reject')}}" method="POST">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('reject')</span> <span class="font-weight-bold withdraw-amount text-success"></span> @lang('withdrawal of') <span class="font-weight-bold withdraw-user"></span>?</p>

                        <div class="form-group">
                            <label class="font-weight-bold mt-2">@lang('Reason for Rejection')</label>
                            <textarea name="message" id="message" placeholder="@lang('Reason for Rejection')" class="form-control" rows="5"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--danger">@lang('Reject')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";

            $('.approveBtn').on('click', function () {
                var modal = $('#approveModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('.withdraw-amount').text($(this).data('amount'));
                modal.find('.withdraw-user').text($(this).data('username'));
                modal.modal('show');
            });

            $('.sendBtn').on('click', function () {
                var modal = $('#sendModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('.withdraw-amount').text($(this).data('amount'));
                modal.find('.withdraw-user').text($(this).data('username'));
                modal.modal('show');
            });

            $('.rejectBtn').on('click', function () {
                var modal = $('#rejectModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find('.withdraw-amount').text($(this).data('amount'));
                modal.find('.withdraw-user').text($(this).data('username'));
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
