@extends($activeTemplate.'layouts.frontend')

@section('content')
    <div class="search-section pt-120 pb-120 bg--section position-relative overflow-hidden">
        <div class="shape right-side">@lang('Rent')</div>
        <div class="shape">@lang('Vehicles')</div>
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-6 mb-3">
                    <a class="cmn--btn form--control bg--base w-100 justify-content-center" href="{{ route('host.vehicles.add') }}">Add Vehicle Details</a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <aside class="category-sidebar">
                        <div class="widget d-lg-none border--dashed">
                            <div class="d-flex justify-content-between">
                                <h5 class="title border-0 pb-0 mb-0">@lang('Filter Vehicles')</h5>
                                <div class="close-sidebar"><i class="las la-times"></i></div>
                            </div>
                        </div>
                        <div class="widget border--dashed">
                            <h5 class="title">
                                <label for="search">@lang('Search Name')</label>
                            </h5>
                            <div class="widget-body">
                                <form action="{{ route('host.vehicle.search') }}" method="get">
                                    <div class="input-group">
                                        <input type="text" name="name" value="{{ @request()->name }}" class="form-control form--control" placeholder="@lang('Vehicle Name')" id="search">
                                        <button class="input-group-text cmn--btn" type="submit"><i class="las la-search"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </aside>
                </div>
                <div class="col-lg-12">
                    <div class="row g-4">

                        @forelse($vehicles as $vehicle)
                            <div class="col-md-6">
                                <div class="rent__item">
                                    <div class="rent__thumb">
                                        <a href="{{ route('vehicle.details', [$vehicle->id, slug($vehicle->name)]) }}">
                                            <img src="{{ getImage(imagePath()['vehicles']['path']. '/'. @$vehicle->images[0], imagePath()['vehicles']['size']) }}" class="first-look" alt="rent-vehicle">
                                            <img src="{{ getImage(imagePath()['vehicles']['path']. '/'. @$vehicle->images[0], imagePath()['vehicles']['size']) }}" class="hover-look" alt="rent-vehicle">
                                        </a>
                                    </div>
                                    <div class="rent__content">
                                        <h6 class="rent__title">
                                            <a href="{{ route('vehicle.details', [$vehicle->id, slug($vehicle->name)]) }}">{{ __(@$vehicle->name) }}</a>
                                        </h6>
                                        <div class="price-area">
                                            <h5 class="item">{{ $general->cur_sym }}{{ showAmount($vehicle->price) }} <sub>/@lang('day')</sub></h5>
                                        </div>
                                        <ul class="d-flex car-info">
                                            <li class="pr-3"><i class="las la-car"></i><span class="font-mini">{{ __(@$vehicle->model) }}</span></li>
                                            <li class="pr-3"><i class="las la-tachometer-alt"></i><span class="font-mini">{{ __(@$vehicle->transmission) }}</span></li>
                                            <li class="pr-3"><i class="las la-gas-pump"></i><span class="font-mini">{{ __(@$vehicle->fuel_type) }}</span></li>
                                        </ul>
                                        <div class="rent-btn mt-4 text-center">
                                            <a href="{{ route('vehicle.details', [$vehicle->id, slug($vehicle->name)]) }}" class="cmn--btn w-100 justify-content-center">
                                                @auth @guest
                                                    @if($vehicle->booked())
                                                        @lang('Booked')
                                                    @else
                                                        @lang('Book Now')
                                                    @endif
                                                @endauth @endguest
                                                @auth('host')
                                                    @lang('See More')
                                                @endauth
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";

            $('.min_price').keypress(function (e) {
                if (e.which == 13) {
                    $('.priceForm').submit();
                    return false;
                }
            });

            $('.max_price').keypress(function (e) {
                if (e.which == 13) {
                    $('.priceForm').submit();
                    return false;
                }
            });
        })(jQuery);
    </script>
@endpush
