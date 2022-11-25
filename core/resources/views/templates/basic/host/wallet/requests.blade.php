@extends($activeTemplate.'layouts.master')

@section('content')
    <div class="search-section pt-12 pb-120 bg--section position-relative overflow-hidden">
        <div class="shape right-side">@lang('Rent')</div>
        <div class="shape">@lang('Wallet')</div>
        <div class="container">
           <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 mb-5">
                    <table class="table table-responsive cmn--table">
                        <tbody>
                            <tr>
                                <td>
                                    Current Total Amount
                                </td>
                                <td>
                                    {{showAmount($wallet->total_amount)}} {{__($general->cur_text)}}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Total Amount Withdrawn
                                </td>
                                <td>
                                    {{showAmount($wallet->total_withdrawn)}} {{__($general->cur_text)}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
           </div>
            <div class="row justify-content-center g-4">
                <div class="col-md-4 col-sm-6 mb-3">
                    <a class="cmn--btn form--control bg--base w-100 justify-content-center detailBtn" href="#">Request Withdrawal</a>
                </div>
            </div>
            <div class="pb-60">
                <div class="table-responsive">
                    <table class="table cmn--table">
                        <thead>
                        <tr>
                            <th>@lang('Amount')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Request Date')</th>
                            {{-- <th> @lang('MORE')</th> --}}
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($requests) >0)
                            @foreach($requests as $k=>$data)
                                <tr>
                                    <td data-label="@lang('Amount')">
                                        <strong>{{showAmount($data->amount)}} {{__($general->cur_text)}}</strong>
                                    </td>

                                    <td>
                                        @if($data->status == 1)
                                        <span class="badge badge--warning">@lang('Pending')</span>

                                        @elseif($data->status == 2)
                                            <span class="badge badge--info">@lang('Approved')</span>
                                        @elseif($data->status == 3)
                                            <span class="badge badge--danger">@lang('Rejected')</span>
                                        @elseif($data->status == 4)
                                            <span class="badge badge--success">@lang('Completed')</span>
                                        @endif


                                        @if($data->admin_feedback != null)
                                            <button class="btn--info btn-rounded badge detailBtn" data-admin_feedback="{{$data->admin_feedback}}"><i class="la la-info"></i></button>
                                        @endif

                                    </td>
                                    <td data-label="@lang('Request Date')">
                                        <i class="la la-calendar"></i> {{showDateTime($data->created_at)}}
                                    </td>

                                    @php
                                        $details = ($data->detail != null) ? json_encode($data->detail) : null;
                                    @endphp

                                    {{-- <td data-label="@lang('Details')">
                                        <a href="javascript:void(0)" class="btn btn--primary btn--sm approveBtn"
                                        data-info="{{ $details }}"
                                        data-id="{{ $data->id }}"
                                        data-amount="{{ showAmount($data->amount)}} {{ __($general->cur_text) }}"
                                        data-charge="{{ showAmount($data->charge)}} {{ __($general->cur_text) }}"
                                        data-after_charge="{{ showAmount($data->amount + $data->charge)}} {{ __($general->cur_text) }}"
                                        data-rate="{{ showAmount($data->rate)}} {{ __($data->method_currency) }}"
                                        data-payable="{{ showAmount($data->final_amo)}} {{ __($data->method_currency) }}">
                                            <i class="la la-desktop"></i>
                                        </a>
                                    </td> --}}
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="100%">@lang('Data not found')</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="detailModal" class="modal fade custom--modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Withdrawal Request')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <div class="account__wrapper mw-100 bg--body">
                        <form class="account-form row g-4" method="post" action="{{ route('host.requests') }}">
                            @csrf
                            <div class="col-md-6">
                                <label for="amount" class="form--label">@lang('Amount')</label>
                                <input name="amount" type="number" placeholder="@lang('Amount')" class="form-control form--control" value="{{ old('amount') }}" required>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="cmn--btn btn--lg">@lang('Submit')</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";

            $('.min_price').keypress(function (e) {
                if (e.which == 13) {
                    $('.priceForm').submit();
                    return false;
                }
            });

            $('.max_price').keypress(function (e) {
                if (e.which == 13) {
                    $('.priceForm').submit();
                    return false;
                }
            });

            $('.detailBtn').on('click', function() {
                var modal = $('#detailModal');
                var feedback = $(this).data('admin_feedback');
                modal.find('.withdraw-detail').html(`<p> ${feedback} </p>`);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
