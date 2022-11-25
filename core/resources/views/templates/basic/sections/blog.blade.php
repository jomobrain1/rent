@php
    $blog_content = getContent('blog.content', true);
    $blog_elements = getContent('blog.element', false, 3);
@endphp

<!-- Latest News Section -->
<section class="blog-section pt-120 pb-120 position-relative overflow-hidden">
    <div class="shape right-side">{{ __(@$blog_content->data_values->stylish_text_right) }}</div>
    <div class="shape">{{ __(@$blog_content->data_values->stylish_text_left) }}</div>
    <div class="container">
        <div class="section__header section__header__center">
            <span class="section__category">{{ __(@$blog_content->data_values->sub_heading) }}</span>
            <h2 class="section__title">{{ __(@$blog_content->data_values->heading) }}</h2>
        </div>
        <div class="row justify-content-center g-4">

            @forelse($blog_elements as $item)
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="post__item">
                        <div class="post__thumb">
                            <a href="{{ route('blog.details', [$item->id, slug($item->data_values->title)]) }}">
                                <img src="{{ getImage('assets/images/frontend/blog/' . @$item->data_values->image, '900x600') }}" alt="blog">
                            </a>
                        </div>
                        <div class="post__content">
                            <h6 class="post__title">
                                <a href="{{ route('blog.details', [$item->id, slug($item->data_values->title)]) }}">{{ __(@$item->data_values->title) }}</a>
                            </h6>
                            <div class="meta__date">
                                <div class="meta__item">
                                    <i class="las la-calendar"></i>
                                    {{ showDateTime($item->created_at) }}
                                </div>
                                <div class="meta__item">
                                    <i class="las la-eye"></i>
                                    {{ __(@$item->views) }}
                                </div>
                            </div>
                            <a href="{{ route('blog.details', [$item->id, slug($item->data_values->title)]) }}" class="post__read">@lang('Read More') <i class="las la-long-arrow-alt-right"></i></a>
                        </div>
                    </div>
                </div>
            @empty
            @endforelse

        </div>
    </div>
</section>
<!-- Latest News Section -->
