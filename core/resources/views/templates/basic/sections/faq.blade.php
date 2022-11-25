@php
    $faq_content = getContent('faq.content', true);
    $faq_elements = getContent('faq.element', false, null, true);
@endphp

<!-- Faqs Section -->
<section class="faq-section pt-120 pb-120 position-relative overflow-hidden">
    <div class="shape right-side">{{ __(@$faq_content->data_values->stylish_text_right) }}</div>
    <div class="shape">{{ __(@$faq_content->data_values->stylish_text_left) }}</div>
    <div class="container">
        <div class="section__header section__header__center">
            <span class="section__category">{{ __(@$faq_content->data_values->sub_heading) }}</span>
            <h2 class="section__title">{{ __(@$faq_content->data_values->heading) }}</h2>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="faq__wrapper">

                    @forelse($faq_elements as $item)
                        @continue($loop->even)
                        <div class="faq__item">
                            <div class="faq__title">
                                <h6 class="title">{{ __(@$item->data_values->question) }}</h6>
                                <span class="right__icon"></span>
                            </div>
                            <div class="faq__content">
                                <p>{{ __(@$item->data_values->answer) }}</p>
                            </div>
                        </div>
                    @empty
                    @endforelse

                </div>
            </div>
            <div class="col-lg-6">
                <div class="faq__wrapper">

                    @forelse($faq_elements as $item)
                        @continue($loop->odd)
                        <div class="faq__item">
                            <div class="faq__title">
                                <h6 class="title">{{ __(@$item->data_values->question) }}</h6>
                                <span class="right__icon"></span>
                            </div>
                            <div class="faq__content">
                                <p>{{ __(@$item->data_values->answer) }}</p>
                            </div>
                        </div>
                    @empty
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</section>
<!-- Faqs Section -->
