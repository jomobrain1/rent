@extends($activeTemplate.'layouts.frontend')
@section('content')
    @php
        $banners = getContent('banner.element');

        $brands = \App\Models\Brand::active()->orderBy('name')->get();
        $seats = \App\Models\Seater::active()->orderBy('number')->get();
    @endphp
    <!-- Banner Section -->
    <div class="banner-slider owl-carousel owl-theme">

        @forelse($banners as $banner)
            <div class="banner-section">
                <div class="container">
                    <div class="banner__wrapper">
                        <div class="banner__content">
                            <span class="banner__category">{{ __(@$banner->data_values->subtitle) }}</span>
                            <h1 class="banner__title">
                                <span>{{ __(@$banner->data_values->title) }}</span>
                            </h1>
                            <p class="banner__txt">{{ __(@$banner->data_values->content) }}</p>
                            <div class="btn__grp">
                                <a href="{{ @$banner->data_values->button_1_url }}"
                                   class="cmn--btn active">{{ __(@$banner->data_values->button_1_name) }}</a>
                                <a href="{{ @$banner->data_values->button_2_url }}"
                                   class="cmn--btn">{{ __(@$banner->data_values->button_2_name) }}</a>
                            </div>
                        </div>
                        <div class="banner__thumb">
                            <img
                                src="{{ getImage('assets/images/frontend/banner/' . @$banner->data_values->background_image, '1000x586') }}"
                                alt="banner">
                        </div>
                    </div>
                </div>
            </div>
        @empty
        @endforelse

    </div>
    <!-- Banner Section -->

    <!-- Browse by Type Section -->

    <div class="pb-60 pt-60 position-relative overflow-hidden">
        <div class="shape">@lang('Type')</div>
        <div class="container">
            <div class="section__header section__header__center">
                <h2 class="section__title">@lang('Browse by Vehicle Type')</h2>
            </div>
            <div class="row g-4 types-slider owl-carousel owl-theme cards">
                @foreach($types as $data)
                    <div class="">
                        <div class="card ">
                            <div class="">
                                <div class="card__img" style="background-image: url('{{ getImage(imagePath()['types']['path'].'/'. $data->image,imagePath()['types']['size']) }}')"></div>
                                <a href="{{ route('vehicle.type', [$data->id, slug($data->name)]) }}" class="card_link">
                                    <div class="card__img--hover"></div>
                                </a>
                                <div class="card__info">
                                    <h5 class="card__title">{{__($data->name)}}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Browse By Type Section -->

    <!-- Book Section -->
    {{-- <section class="book-section pb-120 bg--section">
        <div class="container">
            <div class="book__wrapper mt--120 bg--section">
                <h4 class="book__title">@lang('Book a Car')</h4>
                <form class="book--form row gx-3 gy-4 g-md-4" action="{{ route('vehicle.search') }}" method="get">
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="car-type" class="form--label">
                                <i class="las la-car-side"></i> @lang('Select Brand')
                            </label>
                            <select name="brand" id="car-type" class="form-control form--control">
                                <option value="">@lang('Select Option')</option>
                                @forelse($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ __(@$brand->name) }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="pick-point" class="form--label">
                                <i class="las la-chair"></i> @lang('Number Of Seats')
                            </label>
                            <select name="seats" id="pick-point" class="form-control form--control">
                                <option value="">@lang('Select Option')</option>
                                @forelse($seats as $seat)
                                    <option value="{{ $seat->id }}">{{ __(@$seat->number) }} {{ __('Seater') }}</option>
                                @empty
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="drop-point" class="form--label">
                                <i class="las la-street-view"></i> @lang('Model')
                            </label>
                            <input type="text" name="model" class="form-control form--control"
                                   placeholder="@lang('Sedan, SUV ...')">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="start-datse" class="form--label">
                                <i class="las la-dollar-sign"></i> @lang('Min Price')
                            </label>
                            <input type="text" placeholder="@lang('Min Price')" name="min_price" id="start-datse"
                                   autocomplete="off" class="form-control form--control">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="end-date" class="form--label">
                                <i class="las la-dollar-sign"></i> @lang('Max Price')
                            </label>
                            <input type="text" placeholder="@lang('Max Price')" name="max_price" autocomplete="off"
                                   class="form-control form--control">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group">
                            <label class="form--label d-none d-sm-block">&nbsp;</label>
                            <button class="cmn--btn form--control bg--base w-100 justify-content-center"
                                    type="submit">@lang('Search')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section> --}}
    <!-- Book Section -->

    @if($sections->secs != null)
        @foreach(json_decode($sections->secs) as $sec)
            @include($activeTemplate.'sections.'.$sec)
        @endforeach
    @endif
@endsection
