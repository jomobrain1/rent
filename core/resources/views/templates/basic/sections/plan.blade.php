@php
    $plan_content = getContent('plan.content', true);
    $plans = \App\Models\Plan::active()->take(3)->get();
@endphp

<!-- Pricing Section -->
<section class="pricing-section bg--section pt-120 pb-120 position-relative overflow-hidden">
    <div class="shape right-side">{{ __(@$plan_content->data_values->stylish_text_right) }}</div>
    <div class="shape">{{ __(@$plan_content->data_values->stylish_text_left) }}</div>
    <div class="container">
        <div class="section__header section__header__center">
            <span class="section__category">{{ __(@$plan_content->data_values->sub_heading) }}</span>
            <h2 class="section__title">{{ __(@$plan_content->data_values->heading) }}</h2>
        </div>
        <div class="row g-4 justify-content-center">

            @forelse($plans as $plan)
                <div class="col-sm-10 col-md-6 col-xl-3">
                    <div class="plan__item">
                        <div class="plan__header">
                            <h5 class="plan__title">{{ __(@$plan->name) }}</h5>
                            <div class="plan__header-price">
                                <div class="price">
                                    <span class="d-block title">{{ $general->cur_sym }}{{ getAmount($plan->price) }}</span>
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
                            <a href="#0" class="cmn--btn">@lang('book now')</a>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse

        </div>
    </div>
</section>
<!-- Pricing Section -->
