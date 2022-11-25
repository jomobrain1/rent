@extends($activeTemplate.'layouts.master')

@section('content')
    <!-- Reply Chat -->
    <div class="pt-60">
        <div class="message__chatbox bg--body">
            <div class="message__chatbox__header">
                <h5 class="title">
                    @if($my_ticket->status == 0)
                        <span class="badge badge--success">@lang('Open')</span>
                    @elseif($my_ticket->status == 1)
                        <span class="badge badge--primary">@lang('Answered')</span>
                    @elseif($my_ticket->status == 2)
                        <span class="badge badge--warning">@lang('Replied')</span>
                    @elseif($my_ticket->status == 3)
                        <span class="badge badge--dark">@lang('Closed')</span>
                    @endif
                    <span
                        class="text--base">[@lang('Ticket')#{{ $my_ticket->ticket }}] {{ $my_ticket->subject }}</span>
                </h5>
                <button class="cmn--btn btn--sm close-button" type="button" title="@lang('Close Ticket')"
                        data-bs-toggle="modal" data-bs-target="#DelModal"><i
                        class="la la-lg la-times-circle"></i>
                </button>
            </div>
            <div class="message__chatbox__body">
                @if($my_ticket->status != 4)
                    <form class="message__chatbox__form row g-4" method="post"
                          action="{{ route('ticket.reply', $my_ticket->id) }}" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="replayTicket" value="1">

                        <div class="form--group col-sm-12">
                            <textarea name="message" class="form-control form--control" id="inputMessage"
                                      placeholder="@lang('Your Reply')" rows="4" cols="10"></textarea>
                        </div>
                        <div class="form--group col-sm-12">
                            <div class="d-flex">
                                <div class="left-group col p-0">
                                    <label for="file" class="form--label">@lang('Attachments')</label>
                                    <input type="file" class="overflow-hidden form-control form--control mb-2"
                                           name="attachments[]" id="file">
                                    <div id="fileUploadsContainer"></div>
                                    <span class="info fs--14">@lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')</span>
                                </div>
                                <div class="add-area">
                                    <label class="form--label d-block">&nbsp;</label>
                                    <button
                                        class="cmn--btn bg--primary justify-content-center py-3 ms-2 ms-md-4 addFile"
                                        type="button"><i class="las la-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form--group col-sm-12">
                            <button type="submit" class="cmn--btn"><i class="la la-reply"></i> @lang('Reply')
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <!-- Reply Chat -->

    <!-- Chat Messages -->
    <div class="pb-60 pt-60">
        <div class="message__chatbox bg--body">
            <div class="message__chatbox__body">
                <ul class="reply-message-area">
                    @foreach($messages as $message)
                        @if($message->admin_id == 0)
                            <li>
                                <div class="reply-item">
                                    <div class="name-area">
                                        <h5 class="title">{{ $message->ticket->name }}</h5>
                                    </div>
                                    <div class="content-area">
                                        <p class="meta-date">
                                            @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                        <p>{{$message->message}}</p>
                                        @if($message->attachments()->count() > 0)
                                            <div class="mt-2">
                                                @foreach($message->attachments as $k=> $image)
                                                    <a href="{{route('ticket.download',encrypt($image->id))}}"
                                                    class="text--base"><i
                                                            class="la la-file"></i> @lang('Attachment') {{++$k}} </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @else
                            <li>
                                <ul>
                                    <li>
                                        <div class="reply-item">
                                        <div class="name-area">
                                            <h5 class="title mb-3">{{ $message->admin->name }}</h5>
                                            <p class="lead text-muted">@lang('Staff')</p>
                                        </div>
                                        <div class="content-area">
                                            <p class="meta-date">
                                                @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                            <p>{{$message->message}}</p>
                                            @if($message->attachments()->count() > 0)
                                                <div class="mt-2">
                                                    @foreach($message->attachments as $k=> $image)
                                                        <a href="{{route('ticket.download',encrypt($image->id))}}"
                                                        class="text--base"><i
                                                                class="la la-file"></i> @lang('Attachment') {{++$k}} </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <!-- Chat Messages -->


    <div class="modal fade custom--modal" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('ticket.reply', $my_ticket->id) }}">
                    @csrf
                    <input type="hidden" name="replayTicket" value="2">
                    <div class="modal-header">
                        <h5 class="modal-title text--base"> @lang('Confirmation')!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                    </div>
                    <div class="modal-body">
                        <strong>@lang('Are you sure you want to close this support ticket')?</strong>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn--sm" data-bs-dismiss="modal"><i
                                class="la la-times"></i>
                            @lang('Close')
                        </button>
                        <button type="submit" class="btn btn--primary btn--sm"><i
                                class="la la-check"></i> @lang("Confirm")
                        </button>
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
            $('.delete-message').on('click', function (e) {
                $('.message_id').val($(this).data('id'));
            });
            $('.addFile').on('click', function () {
                $("#fileUploadsContainer").append(
                    `<input type="file" name="attachments[]" class="form-control form--control my-3" required />`
                )
            });
        })(jQuery);

    </script>
@endpush
