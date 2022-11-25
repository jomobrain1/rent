@extends($activeTemplate.'layouts.frontend')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="widget border--dashed">
                <form action="{{ route('host.vehicles.update', $vehicle->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">@lang('Name')</label>
                                    <input type="text" id="name" name="name" class="form-control form--control"
                                           value="{{ $vehicle->name }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">@lang('Brand')</label>
                                    <select class="form-control form--control" id="category" name="brand" required="">
                                        <option value="">-- @lang('Select One') --</option>
                                        @forelse($brands as $item)
                                            <option
                                                value="{{ $item->id }}" {{ $vehicle->brand_id == $item->id ? 'selected' : '' }}>{{ __(@$item->name) }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="seater">@lang('Seat Number')</label>
                                    <select class="form-control form--control" id="seater" name="seater" required="">
                                        <option value="">-- @lang('Select One') --</option>
                                        @forelse($seaters as $item)
                                            <option
                                                value="{{ $item->id }}" {{ $vehicle->seater_id == $item->id ? 'selected' : '' }}>{{ __(@$item->number) }} @lang('Seater')</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">@lang('Vehicle Type')</label>
                                    <select class="form-control form--control" id="category" name="type" required="">
                                        <option value="">-- @lang('Select One') --</option>
                                        @forelse($types as $item)
                                            <option
                                                value="{{ $item->id }}" {{ $vehicle->type_id == $item->id ? 'selected' : '' }}>{{ __(@$item->name) }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">@lang('Price Per Day')</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form--control" id="price" name="price"
                                               value="{{ getAmount($vehicle->price) }}" required>
                                        <div class="input-group-append">
                                            <div class="input-group-text" style="height:60px">{{ $general->cur_text }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- added --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Minimum Distance Per Day(km)')</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form--control"  name="minDistance"
                                               value="{{$vehicle->minDistance }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Minimum Rental Days')</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form--control"  name="minDays"
                                               value="{{ $vehicle->minDays }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Engine Capacity (cc)')</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form--control"  name="cc"
                                               value="{{$vehicle->cc }}" placeholder="e.g 1000cc" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="nicEditor0">@lang('Details')</label>
                                    <textarea rows="10" name="details" class="form-control form--control nicEdit"
                                              id="nicEditor0">{{ $vehicle->details }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="container border--dark m-4">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="text-white">@lang('Images')</h5>
                                        <button type="button" class="btn btn-sm btn-outline-light addBtn"><i
                                                class="fa fa-fw fa-plus"></i>@lang('Add New')
                                        </button>
                                    </div>
                                    <div class="">
                                        <p><small class="text-facebook">@lang('Images will be resize into')
                                                {{ imagePath()['vehicles']['size'] }}px</small></p>
                                        <div class="row element">

                                            @forelse($vehicle->images as $image)
                                                <div class="col-md-2 imageItem" id="imageItem{{ $loop->iteration }}">
                                                    <div class="payment-method-item">
                                                        <div class="payment-method-header d-flex flex-wrap">
                                                            <div class="thumb" style="position: relative;">
                                                                <div class="avatar-preview">
                                                                    <div class="profilePicPreview"
                                                                         style="background-image: url('{{ getImage(imagePath()["vehicles"]["path"] . "/" . $image) }}')">
                                                                    </div>
                                                                </div>
                                                                <div class="avatar-remove">
                                                                    <button class="bg-danger deleteOldImage"
                                                                            onclick="return false"
                                                                            data-removeindex="imageItem{{ $loop->iteration }}"
                                                                            data-deletelink="{{ route('host.vehicles.image.delete', [$vehicle->id, $image]) }}">
                                                                        <i class="la la-close"></i></button>
                                                                </div>
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

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="model">@lang('Model')</label>
                                    <input type="text" id="model" class="form-control form--control" value="{{ $vehicle->model }}"
                                           autocomplete="off" name="model" required>
                                </div>
                            </div>
                             <div class="col-md-3">
                                <div class="form-group">
                                    <label for="model">@lang('Navigation System')</label>
                                    <input type="text"  class="form-control form--control " value="{{ $vehicle->navigation }}"
                                           autocomplete="off" name="navigation" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="doors">@lang('Doors')</label>
                                    <input type="text" id="doors" class="form-control form--control" value="{{ $vehicle->doors }}"
                                           autocomplete="off" name="doors" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="transmission">@lang('Transmission')</label>
                                    <input type="text" class="form-control form--control" id="transmission"
                                           value="{{ $vehicle->transmission }}"
                                           autocomplete="off" name="transmission" required="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label >@lang('Fuel Type')</label>
                                    <select class="form-control form--control" name="fuel_type" required="">
                                        <option value="{{$vehicle->fuel_type}}">
                                           {{ $vehicle->fuel_type}}
                                        </option>
                                        <option value="petrol">petrol</option>
                                        <option value="diesel">diesel</option>
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label >@lang('Year of  manufacture')</label>
                                    <input type="text" class="form-control form--control" id="fuel"
                                     value="{{$vehicle->year}}"
                                           autocomplete="off" name="year" required="">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fuel">@lang('Proposed pick up')</label>
                                    <input type="text" class="form-control form--control" id="fuel"
                                     value="{{ $vehicle->pickup}}"
                                           autocomplete="off" name="pickup" required="">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fuel">@lang('Proposed pick off points')</label>
                                    <input type="text" class="form-control form--control" id="fuel" value="{{ $vehicle->pickoff }}"
                                           autocomplete="off" name="pickoff" required="">
                                </div>
                            </div>
                             {{-- edit areas --}}
                             <div class="col-lg-12">
                                <div class="border--dark">
                                    <h5 class="m-5">@lang('Areas the vehicles can be delivered with charges')
                                        <button type="button"
                                        class="btn btn-sm btn-outline-light float-right ml-2" data-toggle="modal" data-target="#exampleModal2">
                                    <i class="la la-fw la-plus"></i>@lang('Add areas')
                                    </button>

                                    <div class="">
                                        <div class="row addedArea">
                                            @forelse($vehicle->areas as $key => $spec)
                                                <div class="col-md-12 other-info-data">
                                                    <div class="form-group">
                                                        <div class="input-group mb-md-0 mb-4">
                                                            
                                                            <div class="col-md-3">
                                                                <input name="area[]" class="form-control form--control" type="text"
                                                                       value="{{ $spec[0] }}" required
                                                                       placeholder="@lang('Label')">
                                                            </div>
                                                            <div class="col-md-3 mt-md-0 mt-2">
                                                                <input name="added_price[]" class="form-control form--control"
                                                                       value="{{ $spec[1] }}" type="text" required
                                                                       placeholder="@lang('Value')">
                                                            </div>
                                                            <div class="col-md-1 mt-md-0 mt-2 text-right">
                                                                <span class="input-group-btn">
                                                                    <button class="btn--danger btn-lg removeInfoBtn w-100" style="height:60px" type="button">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </span>
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
                              {{-- ---------------- --}}
                            <div class="col-lg-12">
                                <div class="border--dark">
                                    <h5 class="m-4">@lang('More Specifications')
                                        <button type="button"
                                                class="btn btn-sm btn-outline-light float-right" data-toggle="modal"
                                                data-target="#exampleModal">
                                            <i class="la la-fw la-plus"></i>@lang('Add New')
                                        </button>
                                    </h5>

                                    <div class="">
                                        <div class="row addedField">
                                            @forelse($vehicle->specifications as $key => $spec)
                                                <div class="col-md-12 other-info-data">
                                                    <div class="form-group">
                                                        <div class="input-group mb-md-0 mb-4">
                                                            <div class="col-md-4">
                                                                <div class="input-group has_append">
                                                                    <input name="icon[]" type="text" class="form-control icon form--control" value='{{ $spec[0] }}' required>
                                                                    <div class="input-group-append">
                                                                        <button class="btn btn-outline-secondary iconPicker" style="height:60px" data-icon="{{ explode('"',$spec[0])[1] }}" role="iconpicker"></button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <input name="label[]" class="form-control form--control" type="text"
                                                                       value="{{ $spec[1] }}" required
                                                                       placeholder="@lang('Label')">
                                                            </div>
                                                            <div class="col-md-3 mt-md-0 mt-2">
                                                                <input name="value[]" class="form-control form--control"
                                                                       value="{{ $spec[2] }}" type="text" required
                                                                       placeholder="@lang('Value')">
                                                            </div>
                                                            <div class="col-md-1 mt-md-0 mt-2 text-right">
                                                                <span class="input-group-btn">
                                                                    <button class="btn--danger btn-lg removeInfoBtn w-100" style="height:60px" type="button">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                </span>
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
                    </div>
                    <div class="card-footer">
                        <button class="btn btn--primary w-100">@lang('Update')</button>
                    </div>
                </form>
            </div><!-- card end -->
        </div>
    </div>
 {{-- add areas  information --}}
 <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary" id="exampleModalLabel">@lang('Add Area and price')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
               
                <div class="form-group">
                    <label for="label" class="font-weight-bold">@lang('area')</label>
                    <input class="form-control" id="area"  type="text" placeholder="@lang('area')">
                </div>
                <div class="form-group">
                    <label for="label" class="font-weight-bold">@lang('price')</label>
                    <input class="form-control" id="price" type="text" placeholder="@lang('price')">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                <button type="button" class="btn btn--primary addAreaInformation">@lang('Add')</button>
            </div>
        </div>
    </div>
</div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Add New Specification')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body specification">
                    <div class="form-group">
                        <label for="icon" class="font-weight-bold">@lang('Select Icon')</label>
                        <div class="input-group has_append">
                            <input type="text" class="form-control icon" id="icon" required>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary iconPicker" data-icon="las la-home" role="iconpicker"></button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="label" class="font-weight-bold">@lang('Label')</label>
                        <input class="form-control" id="label" type="text" required placeholder="@lang('Label')">
                    </div>
                    <div class="form-group">
                        <label for="label" class="font-weight-bold">@lang('Value')</label>
                        <input class="form-control" id="value" type="text" required placeholder="@lang('Value')">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--secondary" data-dismiss="modal">@lang('Close')</button>
                    <button type="button" class="btn btn--primary addNewInformation">@lang('Add')</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('host.vehicles.index') }}" class="btn btn-sm btn--primary box--shadow1 text-white text--small"><i
            class="fa fa-fw fa-backward"></i>@lang('Go Back')</a>
@endpush
@push('style')
    <style>
        .avatar-remove {
            position: absolute;
            bottom: 180px;
            right: 0;
        }

        .avatar-remove label {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            text-align: center;
            line-height: 30px;
            font-size: 15px;
            cursor: pointer;
        }

        .avatar-remove button {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            text-align: center;
            line-height: 15px;
            font-size: 15px;
            cursor: pointer;
            padding-left: 6px;
        }
    </style>
@endpush

@push('style-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-iconpicker.min.css') }}">
@endpush
@push('script-lib')
    <script src="{{ asset('assets/admin/js/bootstrap-iconpicker.bundle.min.js') }}"></script>
@endpush

@push('script')
    <script>
        (function ($) {
            "use strict";

            $(document).ready(function () {
                $(window).keydown(function (event) {
                    if (event.keyCode == 13) {
                        event.preventDefault();
                        return false;
                    }
                });
            });

            //Delete Old Image
            $('.deleteOldImage').on('click', function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var url = $(this).data('deletelink');
                var removeindex = $(this).data('removeindex');

                $.ajax({
                    type: "POST",
                    url: url,
                    success: function (data) {
                        if (data.success) {
                            $('#' + removeindex).remove();
                            notify('success', data.message);
                        } else {
                            notify('error', 'Failed to delete the image!')
                        }
                    }
                });
            });

            var counter = 0;
            $('.addBtn').click(function () {
                counter++;
                $('.element').append(`<div class="col-md-2 imageItem"><div class="payment-method-item"><div class="payment-method-header d-flex flex-wrap"><div class="thumb" style="position: relative;"><div class="avatar-preview"><div class="profilePicPreview" style="background-image: url('{{asset('assets/images/default.png')}}')"></div></div><div class="avatar-edit"><input type="file" name="images[]" class="profilePicUpload" required id="image${counter}" accept=".png, .jpg, .jpeg" /><label for="image${counter}" class="cmn--btn"><i class="la la-pencil"></i></label></div>
                <div class="avatar-remove">
                    <label class="bg-danger removeBtn">
                        <i class="la la-close"></i>
                    </label>
                </div>
                </div></div></div></div>`);
                remove()
                upload()
            });

            function remove() {
                $('.removeBtn').on('click', function () {
                    $(this).parents('.imageItem').remove();
                });
            }

            function upload() {
                function proPicURL(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            var preview = $(input).parents('.thumb').find('.profilePicPreview');
                            $(preview).css('background-image', 'url(' + e.target.result + ')');
                            $(preview).addClass('has-image');
                            $(preview).hide();
                            $(preview).fadeIn(65);
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                }

                $(".profilePicUpload").on('change', function () {
                    proPicURL(this);
                });
            }

            //-----------Add areas------------------///
            $('.addAreaInformation').on('click', function () {
                
              
                var area = $('#area').val();
                var price = $('#price').val();

                var html = `
                <div class="col-md-12 other-areas-data">
                    <div class="form-group">
                        <div class="input-group mb-md-0 mb-4">
                        
                            <div class="col-md-4">
                                <input name="area[]" class="form-control" type="text" value="${area}" required placeholder="@lang('Label')" readonly>
                            </div>
                            <div class="col-md-3 mt-md-0 mt-2">
                                <input name="added_price[]" class="form-control" value="${price}" type="text" required placeholder="@lang('Value')" readonly>
                            </div>
                            <div class="col-md-1 mt-md-0 mt-2 text-right">
                                <span class="input-group-btn">
                                    <button class="btn btn--danger btn-lg removeAreaBtn w-100" type="button">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>`;

                if ( area && price){
                    $('.addedArea').append(html);                    
                    $('#area').val('');
                    $('#price').val('');
                }
            });
            //----- Add Information fields-------//
            $('.addNewInformation').on('click', function () {
                var icon = $('#icon').val();
                var label = $('#label').val();
                var value = $('#value').val();

                var html = `
                <div class="col-md-12 other-info-data">
                    <div class="form-group">
                        <div class="input-group mb-md-0 mb-4">
                            <div class="col-md-4">
                                <div class="input-group has_append">
                                    <input type="text" name="icon[]" class="form-control icon" value='${icon}' required readonly>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary iconPicker" data-icon="las la-home" role="iconpicker">${icon}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <input name="label[]" class="form-control" type="text" value="${label}" required placeholder="@lang('Label')" readonly>
                            </div>
                            <div class="col-md-3 mt-md-0 mt-2">
                                <input name="value[]" class="form-control" value="${value}" type="text" required placeholder="@lang('Value')" readonly>
                            </div>
                            <div class="col-md-1 mt-md-0 mt-2 text-right">
                                <span class="input-group-btn">
                                    <button class="btn btn--danger btn-lg removeInfoBtn w-100" type="button">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>`;

                if (icon && label && value){
                    $('.addedField').append(html);

                    $('#icon').val('');
                    $('#label').val('');
                    $('#value').val('');
                }
            });

            $(document).on('click', '.removeInfoBtn', function () {
                $(this).closest('.other-info-data').remove();
            });
            $(document).on('click', '.removeAreaBtn', function () {
                $(this).closest('.other-areas-data').remove();
            });

            function scrol() {
                var bottom = $(document).height() - $(window).height();
                $('html, body').animate({
                    scrollTop: bottom
                }, 200);
            }

            // Icon picker
            $('.iconPicker').iconpicker({
                align: 'center', // Only in div tag
                arrowClass: 'btn-danger',
                arrowPrevIconClass: 'fas fa-angle-left',
                arrowNextIconClass: 'fas fa-angle-right',
                cols: 10,
                footer: true,
                header: true,
                icon: 'fas fa-bomb',
                iconset: 'fontawesome5',
                labelHeader: '{0} of {1} pages',
                labelFooter: '{0} - {1} of {2} icons',
                placement: 'bottom', // Only in button tag
                rows: 5,
                search: false,
                searchText: 'Search icon',
                selectedClass: 'btn-success',
                unselectedClass: ''
            }).on('change', function (e) {
                $(this).parent().siblings('.icon').val(`<i class="${e.icon}"></i>`);
            });
        })(jQuery);
    </script>
@endpush
