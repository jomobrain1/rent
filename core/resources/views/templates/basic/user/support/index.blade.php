@extends($activeTemplate.'layouts.master')

@section('content')
    <!-- Dashboard Table -->
    <div class="pb-60 pt-60">
        <div class="table-responsive">
            <table class="table cmn--table">
                <thead>
                <tr>
                    <th>@lang('Subject')</th>
                    <th>@lang('Status')</th>
                    <th>@lang('Priority')</th>
                    <th>@lang('Last Reply')</th>
                    <th>@lang('Action')</th>
                </tr>
                </thead>
                <tbody>
                @foreach($supports as $key => $support)
                    <tr>
                        <td data-label="@lang('Subject')">
                            <div class="ticket-name-id">
                                <a href="{{ route('ticket.view', $support->ticket) }}" class="text--title"> [@lang('Ticket')#{{ $support->ticket }}] {{ __($support->subject) }} </a>
                            </div>
                        </td>
                        <td data-label="@lang('Status')">
                            @if($support->status == 0)
                                <span class="badge badge--success">@lang('Open')</span>
                            @elseif($support->status == 1)
                                <span class="badge badge--primary">@lang('Answered')</span>
                            @elseif($support->status == 2)
                                <span class="badge badge--warning">@lang('Customer Reply')</span>
                            @elseif($support->status == 3)
                                <span class="badge badge--dark">@lang('Closed')</span>
                            @endif
                        </td>
                        <td data-label="@lang('Priority')">
                            @if($support->priority == 1)
                                <span class="badge badge--dark">@lang('Low')</span>
                            @elseif($support->priority == 2)
                                <span class="badge badge--success">@lang('Medium')</span>
                            @elseif($support->priority == 3)
                                <span class="badge badge--primary">@lang('High')</span>
                            @endif
                        </td>
                        <td data-label="@lang('Last Reply')">{{ \Carbon\Carbon::parse($support->last_reply)->diffForHumans() }} </td>

                        <td data-label="@lang('Action')">
                            <a href="{{ route('ticket.view', $support->ticket) }}" class="btn btn--primary btn--sm">
                                <i class="la la-desktop"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{$supports->links()}}
    </div>
    <!-- Dashboard Table -->
@endsection
