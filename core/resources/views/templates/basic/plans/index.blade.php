@extends($activeTemplate.'layouts.frontend')

@section('content')
    <div class="faq-section pt-120 pb-120 bg--section position-relative overflow-hidden">
        <div class="shape">@lang('pricing')</div>
        <div class="shape right-side">@lang('plan')</div>
        <div class="container">
            <div class="row g-4 justify-content-center">

                @forelse($plans as $plan)
                    <div class="col-sm-10 col-md-6 col-xl-3">
                        <div class="plan__item">
                            <div class="plan__header">
                                <h5 class="plan__title">{{ __(@$plan->name) }}</h5>
                                <div class="plan__header-price">
                                    <div class="price">
                                        <span
                                            class="d-block title">{{ $general->cur_sym }}{{ getAmount($plan->price) }}</span>
                                        <span class="info d-block">/ @lang('per ride')</span>
                                    </div>
                                </div>
                            </div>
                            <div class="plan__body">
                                <ul>

                                    @forelse($plan->included as $item)
                                        <li><i class="las la-check"></i> {{ __(@$item) }}</li>
                                    @empty
                                    @endforelse

                                    @forelse($plan->excluded as $item)
                                        <li><i class="las la-times"></i> {{ __(@$item) }}</li>
                                    @empty
                                    @endforelse

                                </ul>
                                @auth
                                    <button type="button" class="cmn--btn plan_modal" data-bs-toggle="modal"
                                            data-bs-target="#planModal" data-url="{{ route('plan.booking', $plan->id) }}">@lang('Book Now')</button>
                                @else
                                    <a href="{{ route('user.login') }}" class="cmn--btn">@lang('book now')</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade custom--modal" id="planModal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Fill the form')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
                </div>
                <form action="" method="post" class="planForm">
                    @csrf

                    <div class="modal-body">
                        <div class="form--group">
                            <label for="priority" class="form--label">@lang('Pick Up Point')</label>
                            <select name="pick_location" class="form-control form--control">
                                @forelse($locations as $location)
                                    <option value="{{ $location->id }}">@lang($location->name)</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                        <div class="form--group">
                            <label for="priority" class="form--label">@lang('Pick Up Date & Time')</label>
                            <input type="text" name="pick_time" placeholder="@lang('Pick Up Date & Time')" id="planDateAndTimePicker" autocomplete="off" data-position='top right' class="form-control form--control"  required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn--primary">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/datepicker.min.css')}}">
    <style>
        .datepicker{
            z-index: 9999999999999999;
        }
    </style>
@endpush

@push('script')
    <script src="{{asset($activeTemplateTrue.'js/datepicker.min.js')}}"></script>
    <script src="{{asset($activeTemplateTrue.'js/datepicker.en.js')}}"></script>
    <script>
        // date and time picker
        $('#planDateAndTimePicker').datepicker({
            timepicker: true,
            language: 'en'
        })

        $(document).on('click', '.plan_modal', function () {
            var url = $(this).data('url');
            $('.planForm').attr('action', url);
        });
    </script>
@endpush
