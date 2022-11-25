@extends($activeTemplate.'layouts.frontend')
@section('content')
<!-- Blog Section -->
<section class="blog-section pt-120 pb-120 bg--section">
    <div class="container">
        <div class="row gy-5 justify-content-center">
            <div class="col-lg-8">
                <div class="post__details">
                    <div class="post__thumb">
                        <img src="{{ getImage('assets/images/frontend/blog/' . @$blog->data_values->image, '900x600') }}" alt="blog">
                    </div>
                    <div class="post__header">
                        <h3 class="post__title">
                            {{ __(@$blog->data_values->title) }}
                        </h3>

                        <div class="mt-2">
                            @php echo @$blog->data_values->description @endphp
                        </div>
                    </div>

                    <div class="row gy-4 justify-content-center">
                        <div class="fb-comments" data-href="{{ route('blog.details',[$blog->id,slug($blog->data_values->title)]) }}" data-numposts="5"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <aside class="blog-sidebar p-0 border-0 ps-xl-4">
                    <div class="widget widget__post__area">
                        <h5 class="widget__title">@lang('Recent Post')</h5>
                        <ul>

                            @forelse($recent_blogs as $item)
                                <li>
                                    <a href="{{ route('blog.details',[$item->id,slug($item->data_values->title)]) }}" class="widget__post">
                                        <div class="widget__post__thumb">
                                            <img src="{{ getImage('assets/images/frontend/blog/' . @$item->data_values->image, '80x80') }}" alt="blog">
                                        </div>
                                        <div class="widget__post__content">
                                            <h6 class="widget__post__title">
                                                {{ __(@$item->data_values->title) }}
                                            </h6>
                                            <span>{{ showDateTime($item->created_at) }}</span>
                                        </div>
                                    </a>
                                </li>
                            @empty
                            @endforelse

                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
<!-- Blog Section -->
@endsection
@push('fbComment')
	@php echo loadFbComment() @endphp
@endpush
