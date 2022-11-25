@extends($activeTemplate.'layouts.frontend')
@section('content')
    <section class="faq-section pt-120 pb-120 position-relative bg--section overflow-hidden">
        <div class="shape right-side">@lang('Posts')</div>
        <div class="shape">@lang('Blog')</div>
        <div class="container">
            <div class="row justify-content-center g-4">

                @forelse($blogs as $item)
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

            {{ $blogs->links() }}
        </div>
    </section>
@endsection
