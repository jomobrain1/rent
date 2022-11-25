@php
    $testimonial_content = getContent('testimonial.content', true);
    $testimonial_elements = getContent('testimonial.element');
@endphp

<!-- Clients Say Section -->
<section class="clients-section pt-120 pb-120 bg--section position-relative overflow-hidden">
    <div class="shape right-side">{{ __(@$testimonial_content->data_values->stylish_text_right) }}</div>
    <div class="shape">{{ __(@$testimonial_content->data_values->stylish_text_left) }}</div>
    <div class="container">
        <div class="section__header section__header__center">
            <span class="section__category">{{ __(@$testimonial_content->data_values->sub_heading) }}</span>
            <h2 class="section__title">{{ __(@$testimonial_content->data_values->heading) }}</h2>
        </div>
        <div class="client-slider owl-theme owl-carousel">

            @forelse($testimonial_elements as $item)
                <div class="client__item">
                    <div class="client__header">
                        <div class="thumb">
                            <img
                                src="{{ getImage('assets/images/frontend/testimonial/' . @$item->data_values->image, '120x120') }}"
                                alt="client">
                        </div>
                        <div class="name__area">
                            <h6 class="name text--base">{{ __(@$item->data_values->name) }}</h6>
                            <span class="designation">{{ __(@$item->data_values->designation) }}</span>
                        </div>
                    </div>
                    <div class="client__content">
                        <p>{{ __(@$item->data_values->review) }}</p>
                        <div class="ratings">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $item->data_values->rating)
                                    <span><i class="las la-star"></i></span>
                                @else
                                    <span><i class="lar la-star"></i></span>
                                @endif
                            @endfor
                        </div>
                    </div>
                </div>
            @empty
            @endforelse

        </div>
    </div>
</section>
<!-- Clients Say Section -->
