@extends($activeTemplate.'layouts.master')
@section('content')
    <!-- New Ticket -->
    <div class="pb-60 pt-60">
        <div class="message__chatbox bg--body">
            <div class="message__chatbox__header">
                <h5 class="title">@lang('Create New Ticket')</h5>
                <a href="{{route('ticket') }}" class="cmn--btn btn--sm">@lang('All Tickets')</a>
            </div>
            <div class="message__chatbox__body">
                <form class="message__chatbox__form row g-4" action="{{route('ticket.store')}}"  method="post" enctype="multipart/form-data" onsubmit="return submitUserForm();">
                    @csrf

                    <div class="form--group col-sm-6">
                        <label for="fname" class="form--label">@lang('Name')</label>
                        <input type="text" name="name" value="{{@$user->firstname . ' '.@$user->lastname}}" class="form-control form--control" placeholder="@lang('Enter your name')" readonly>
                    </div>
                    <div class="form--group col-sm-6">
                        <label for="username" class="form--label">@lang('Email address')</label>
                        <input type="email"  name="email" value="{{@$user->email}}" class="form-control form--control" placeholder="@lang('Enter your email')" readonly>
                    </div>
                    <div class="form--group col-sm-6">
                        <label for="subject" class="form--label">@lang('Subject')</label>
                        <input type="text" name="subject" value="{{old('subject')}}" class="form-control form--control" placeholder="@lang('Subject')" >
                    </div>
                    <div class="form--group col-sm-6">
                        <label for="priority" class="form--label">@lang('Priority')</label>
                        <select name="priority" class="form-control form--control">
                            <option value="3">@lang('High')</option>
                            <option value="2">@lang('Medium')</option>
                            <option value="1">@lang('Low')</option>
                        </select>
                    </div>
                    <div class="form--group col-sm-12">
                        <label for="priority" class="form--label">@lang('Message')</label>
                        <textarea name="message" id="inputMessage" rows="6" class="form-control form--control">{{old('message')}}</textarea>
                    </div>
                    <div class="form--group col-sm-12">
                        <div class="d-flex">
                            <div class="left-group col p-0">
                                <label for="file2" class="form--label">@lang('Attachments')</label>
                                <input type="file" name="attachments[]" id="inputAttachments" class="form-control form--control mb-2" />
                                <div id="fileUploadsContainer"></div>
                                <span class="info fs--14">@lang('Allowed File Extensions'): .@lang('jpg'), .@lang('jpeg'), .@lang('png'), .@lang('pdf'), .@lang('doc'), .@lang('docx')</span>
                            </div>
                            <div class="add-area">
                                <label class="form--label d-block">&nbsp;</label>
                                <button class="cmn--btn bg--primary ms-2 ms-md-4 py-3 addFile" type="button"><i class="las la-plus"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="form--group col-sm-12 mb-0">
                        <button type="submit" class="cmn--btn">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- New Ticket -->
@endsection


@push('script')
    <script>
        (function ($) {
            "use strict";
            $('.addFile').on('click',function(){
                $("#fileUploadsContainer").append(`
                       <input type="file" name="attachments[]" class="form-control form--control my-3" required />
                `)
            });
        })(jQuery);
    </script>
@endpush
