@extends($activeTemplate.'layouts.master')
@section('content')
    <div class="pb-60 pt-60">
        <div class="table-responsive">
            <table class="table cmn--table">
                <thead>
                <tr>
                    <th>@lang('Transaction ID')</th>
                    <th>@lang('Gateway')</th>
                    <th>@lang('Vehicle')</th>
                    <th>@lang('Amount')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Time')</th>
                    <th> @lang('MORE')</th>
                </tr>
                </thead>
                <tbody>
                @if(count($logs) >0)
                    @foreach($logs as $k=>$data)
                        <tr>
                            <td data-label="#@lang('Trx')">
                                <div>{{$data->trx}}</div>
                            </td>
                            <td data-label="@lang('Gateway')">
                                <div>{{ __(@$data->gateway->name)  }}</div>
                            </td>
                            <td data-label="@lang('Vehicle')">
                                <div>{{ __(@$data->rent->vehicle->name) ?? '-'  }}</div>
                            </td>
                            <td data-label="@lang('Amount')">
                                <div><strong>{{showAmount($data->amount)}} {{__($general->cur_text)}}</strong></div>
                            </td>
                            <td>
                                <div>

                                    @if($data->status == 1)
                                        <span class="badge badge--success">@lang('Complete')</span>
                                    @elseif($data->status == 2)
                                        <span class="badge badge--warning">@lang('Pending')</span>
                                    @elseif($data->status == 3)
                                        <span class="badge badge--danger">@lang('Cancel')</span>
                                    @endif

                                    @if($data->admin_feedback != null)
                                        <button class="btn--info btn-rounded badge detailBtn" data-admin_feedback="{{$data->admin_feedback}}"><i class="la la-info"></i></button>
                                    @endif

                                </div>
                            </td>
                            <td data-label="@lang('Time')">
                                <div>
                                    <i class="la la-calendar"></i> {{showDateTime($data->created_at)}}
                                </div>
                            </td>

                            @php
                                $details = ($data->detail != null) ? json_encode($data->detail) : null;
                            @endphp

                            <td data-label="@lang('Details')">
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

        {{$logs->links()}}
    </div>

    {{-- APPROVE MODAL --}}
    <div id="approveModal" class="modal fade custom--modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        <li class="list-group-item bg-transparent">@lang('Amount') : <span class="withdraw-amount "></span></li>
                        <li class="list-group-item bg-transparent">@lang('Charge') : <span class="withdraw-charge "></span></li>
                        <li class="list-group-item bg-transparent">@lang('After Charge') : <span class="withdraw-after_charge"></span></li>
                        <li class="list-group-item bg-transparent">@lang('Conversion Rate') : <span class="withdraw-rate"></span></li>
                        <li class="list-group-item bg-transparent">@lang('Payable Amount') : <span class="withdraw-payable"></span></li>
                    </ul>
                    <ul class="list-group withdraw-detail mt-1">
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail MODAL --}}
    <div id="detailModal" class="modal fade custom--modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Details')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <div class="withdraw-detail"></div>
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
            $('.approveBtn').on('click', function() {
                var modal = $('#approveModal');
                modal.find('.withdraw-amount').text($(this).data('amount'));
                modal.find('.withdraw-charge').text($(this).data('charge'));
                modal.find('.withdraw-after_charge').text($(this).data('after_charge'));
                modal.find('.withdraw-rate').text($(this).data('rate'));
                modal.find('.withdraw-payable').text($(this).data('payable'));
                var list = [];
                var details =  Object.entries($(this).data('info'));

                var ImgPath = "{{asset(imagePath()['verify']['deposit']['path'])}}/";
                var singleInfo = '';
                for (var i = 0; i < details.length; i++) {
                    if (details[i][1].type == 'file') {
                        singleInfo += `<li class="list-group-item">
                                            <span class="font-weight-bold "> ${details[i][0].replaceAll('_', " ")} </span> : <img src="${ImgPath}/${details[i][1].field_name}" alt="@lang('Image')" class="w-100">
                                        </li>`;
                    }else{
                        singleInfo += `<li class="list-group-item">
                                            <span class="font-weight-bold "> ${details[i][0].replaceAll('_', " ")} </span> : <span class="font-weight-bold ml-3">${details[i][1].field_name}</span>
                                        </li>`;
                    }
                }

                if (singleInfo)
                {
                    modal.find('.withdraw-detail').html(`<br><strong class="my-3">@lang('Payment Information')</strong>  ${singleInfo}`);
                }else{
                    modal.find('.withdraw-detail').html(`${singleInfo}`);
                }
                modal.modal('show');
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
