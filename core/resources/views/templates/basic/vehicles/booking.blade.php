@extends($activeTemplate.'layouts.frontend')

@section('content')
    <div class="single-section pt-120 pb-120 bg--section">
        <div class="container">
            <h4 class="mb-4">@lang('You have selected this car')</h4>
            <div class="row gy-5">
                <div class="col-lg-5">
                    <div class="slider-top owl-theme owl-carousel border--dashed">
                        @forelse($vehicle->images as $image)
                            <div class="car__rental-thumb w-100 bg--body p-0">
                                <img src="{{ getImage(imagePath()['vehicles']['path'].'/'. $image, imagePath()['vehicles']['size']) }}" alt="rent-vehicle">
                            </div>
                        @empty
                        @endforelse
                    </div>
                    <div class="slider-bottom owl-theme owl-carousel mt-4">
                        @forelse($vehicle->images as $image)
                            <div class="rental__thumbnails bg--body">
                                <img src="{{ getImage(imagePath()['vehicles']['path'].'/'. $image, imagePath()['vehicles']['size']) }}" alt="rent-vehicle">
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="book__wrapper bg--body border--dashed mb-4">
                        <form class="book--form row gx-3 gy-4 g-md-4" method="post" action="{{ route('vehicle.booking.confirm', $vehicle->id) }}">
                            @csrf

                           
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="pick-point" class="form--label">
                                     @lang('Choose pick up location')
                                    </label>
                                </div>
                                <input type="hidden" value="" name="hidden" id="hidden">
                                @foreach ($vehicle->areas as $area)
                                  {{-- <div class="col-md-12">
                                    <label  class="form--label">
                                        @lang('Select area')
                                        <select name="area" id="area" >
                                            <option value="{{$area[1]}}">{{$area[0]}}</option>
                                        </select>
                                       </label>
                                  </div> --}}
                                  <div class="col-md-4">
                                    <input type="radio"  name="picks" id="place" value="{{$area[1]}}">
                                    <input type="hidden" name="hiddenpick" id="place"  value="{{$area[1]}}" >
                                  </div>
                                  <div class="col-md-4">
                                    <span>{{$area[0]}}</span>

                                  </div>
                                  <div class="col-md-4">
                                    <span>{{$area[1]}}</span>

                                  </div>
                               
                                    
                                @endforeach
                            </div>
                            
                                ===============================================================
                               
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="pick-point" class="form--label">
                                        <i class="las la-street-view"></i> @lang('Proposed Pick Up Point')
                                    </label>
                                    <input type="text" class="form-control form--control" value="{{$vehicle->pickup}}">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="pick-point" class="form--label">
                                        <i class="las la-street-view"></i> @lang('Proposed Pick off Point')
                                    </label>
                                    <input type="text" class="form-control form--control" 
                                    value="{{$vehicle->pickoff}}"">
                                </div>
                            </div>

                             {{-- commented pickup locations --}}
                            {{-- <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="pick-point" class="form--label">
                                        <i class="las la-street-view"></i> @lang('Pick Up Point')
                                    </label>
                                    <select name="pick_location" id="pick-point" class="form-control form--control" required>
                                        <option value="">@lang('Pick up point')</option>
                                        @forelse($locations as $location)
                                            <option value="{{ $location->id }}">{{ @$location->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div> --}}

                            {{-- <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="drop-point" class="form--label">
                                        <i class="las la-street-view"></i> @lang('Drop of Point')
                                    </label>
                                    <select name="drop_location" id="drop-point" class="form-control form--control" required>
                                        <option value="">@lang('Drop of Point')</option>
                                        @forelse($locations as $location)
                                            <option value="{{ $location->id }}">{{ @$location->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </div>
                            </div> --}}
                            {{-- Endddddddddd --}}
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="start-date" class="form--label">
                                        <i class="las la-calendar-alt"></i> @lang('Pick Up Date & Time')
                                    </label>
                                    <input type="text" name="pick_time" placeholder="@lang('Pick Up Date & Time')" id='dateAndTimePicker' autocomplete="off" data-position='top left' class="form-control form--control pick_time" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group">
                                    <label for="end-date" class="form--label">
                                        <i class="las la-calendar-alt"></i> @lang('Drop of Date & Time')
                                    </label>
                                    <input type="text" name="drop_time" placeholder="@lang('Drop of Date & Time')" id="dateAndTimePicker2" autocomplete="off" data-position='top left' class="form-control form--control" disabled required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="booking-costs mb-4">
                                    @lang('You will be charged') <span class="text--danger"><span class="total_amount">{{ showAmount($vehicle->price) }} </span> {{ $general->cur_text }} + 
                                    <b id="add">
                                       
                                    </b></span> @lang('for book this')
                                    {{ $vehicle->name }} @lang('for') <span class="total_days">1</span> @lang('days. Please confirm to book.')
                                </div>
                                <div class="form-group">
                                    <button class="cmn--btn form--control bg--base w-100 justify-content-center" type="submit" id="bk">@lang('Book Now')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/datepicker.min.css')}}">
@endpush

@push('script')
    <script src="{{asset($activeTemplateTrue.'js/datepicker.min.js')}}"></script>
    <script src="{{asset($activeTemplateTrue.'js/datepicker.en.js')}}"></script>
    <script>
       
        let place=document.querySelectorAll('#place')
        let checked=0;
        let hidden=document.querySelector('#hidden')
        let add=document.querySelector('#add')
        let bk=document.querySelector('#bk')
         
        
        for (var i = 0; i < place.length; i++) {             
            
            place[i].addEventListener('change', (event) => {
                console.log(add.innerText);
            if (event.currentTarget.checked) {
                // alert('te')
                checked=event.currentTarget.value
                hidden.value=checked
                console.log(hidden.value,"hidden");
                localStorage.setItem('bk',hidden.value)
                add.innerText=checked + " KES pickup_fee"
                 
                //  console.log(checked);                 

    
             } 
            })
         
          }
       
        $('#dateAndTimePicker').datepicker({
            
            timepicker: true,
            language: 'en',
            onSelect: function (fd, d, picker) {
                var pick_time = fd;
                var price = parseFloat("{{ $vehicle->price }}");

                if (pick_time){
                    $('#dateAndTimePicker2').removeAttr('disabled');
                }else{
                    $('#dateAndTimePicker2').attr('disabled', 'disabled');

                    $('.total_amount').text(price);
                    $('.total_days').text(1);
                }

                $('#dateAndTimePicker2').datepicker({
                    timepicker: true,
                    language: 'en',
                    onSelect: function (fd, d, picker) {
                        var drop_time = fd;
                        
                        const date1 = new Date(pick_time);
                        const date2 = new Date(drop_time);
                        const diffTime = Math.abs(date2 - date1);
                        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));


                        $('.total_amount').text(price*diffDays);
                     
                        // console.log(price*diffDays+parseFloat(checked));
                        $('.total_days').text(diffDays);
                    }
                })
            }
        })
    </script>
@endpush
