@extends('admin.layouts.app')

@section('panel')
    <div class="row mb-none-30">
        <div class="col-xl-3 col-lg-5 col-md-5 mb-30">

            <div class="card b-radius--10 overflow-hidden box--shadow1">
                <div class="card-body p-0">
                    <div class="p-3 bg--white">
                        <div class="">
                            <img src="{{ getImage(imagePath()['profile']['host']['path'].'/'.$host->image,imagePath()['profile']['host']['size'])}}" alt="@lang('Profile Image')" class="b-radius--10 w-100">
                        </div>
                        <div class="mt-15">
                            <h4 class="">{{$host->fullname}}</h4>
                            <span class="text--small">@lang('Joined At') <strong>{{showDateTime($host->created_at,'d M, Y h:i A')}}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('Host information')</h5>
                    <ul class="list-group">

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('hostname')
                            <span class="font-weight-bold">{{$host->username}}</span>
                        </li>


                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Status')
                            @if($host->status == 1)
                                <span class="badge badge-pill bg--success">@lang('Active')</span>
                            @elseif($host->status == 0)
                                <span class="badge badge-pill bg--danger">@lang('Banned')</span>
                            @endif
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Earnings')
                            <span class="font-weight-bold"><a href="{{route('admin.hosts.deposits',$host->id)}}">{{__($general->cur_sym)}}{{showAmount($totalDeposit)}}</a></span>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Wallet')
                            <span class="font-weight-bold"><a href="{{route('admin.hosts.wallet',$host->wallet->id)}}">{{__($general->cur_sym)}}{{showAmount($host->wallet->total_amount)}}</a></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            @lang('Vehicles')
                            <span class="font-weight-bold"><a href="{{route('admin.vehicles.hosts',$host->id)}}">{{ @$data['total_vehicles'] }}</a></span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card b-radius--10 overflow-hidden mt-30 box--shadow1">
                <div class="card-body">
                    <h5 class="mb-20 text-muted">@lang('host action')</h5>
                    <a href="{{ route('admin.hosts.login.history.single', $host->id) }}"
                       class="btn btn--primary btn--shadow btn-block btn-lg">
                        @lang('Login Logs')
                    </a>
                    <a href="{{route('admin.hosts.email.single',$host->id)}}"
                       class="btn btn--info btn--shadow btn-block btn-lg">
                        @lang('Send Email')
                    </a>
                    <a href="{{route('admin.hosts.login',$host->id)}}" target="_blank" class="btn btn--dark btn--shadow btn-block btn-lg">
                        @lang('Login as host')
                    </a>
                    <a href="{{route('admin.hosts.email.log',$host->id)}}" class="btn btn--warning btn--shadow btn-block btn-lg">
                        @lang('Email Log')
                    </a>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-7 col-md-7 mb-30">

            <div class="row mb-none-30">
                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--indigo b-radius--10 box-shadow has--link">
                        <a href="{{ route('admin.host.vehicles.booking.log', $host->id) }}" class="item--link"></a>
                        <div class="icon">
                            <i class="las la-car-side"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{ @$data['total_vehicle_booking'] }}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Total Vehicle Booking')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->

                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--13 b-radius--10 box-shadow has--link">
                        <a href="{{ route('admin.host.vehicles.booking.log.upcoming', $host->id) }}" class="item--link"></a>
                        <div class="icon">
                            <i class="las la-hourglass-half"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{ @$data['upcoming_vehicle_booking'] }}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Upcoming Vehicle Booking')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->

                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--14 b-radius--10 box-shadow has--link">
                        <a href="{{ route('admin.host.vehicles.booking.log.running', $host->id) }}" class="item--link"></a>
                        <div class="icon">
                            <i class="las la-spinner"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{ @$data['running_vehicle_booking'] }}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Running Vehicle Booking')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->

                <div class="col-xl-3 col-lg-6 col-sm-6 mb-30">
                    <div class="dashboard-w1 bg--success b-radius--10 box-shadow has--link">
                        <a href="{{ route('admin.host.vehicles.booking.log.completed', $host->id) }}" class="item--link"></a>
                        <div class="icon">
                            <i class="las la-check-circle"></i>
                        </div>
                        <div class="details">
                            <div class="numbers">
                                <span class="amount">{{ @$data['completed_vehicle_booking'] }}</span>
                            </div>
                            <div class="desciption">
                                <span>@lang('Completed Vehicle Booking')</span>
                            </div>
                        </div>
                    </div>
                </div><!-- dashboard-w1 end -->
            </div>


            <div class="card mt-50">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">@lang('Information of') {{$host->name}}</h5>

                    <form action="{{route('admin.hosts.update',[$host->id])}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Name')<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="name" value="{{$host->name}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Username')<span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="username" value="{{$host->username}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Email') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="email" name="email" value="{{$host->email}}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label  font-weight-bold">@lang('Mobile Number') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" name="mobile" value="{{$host->mobile}}">
                                </div>
                            </div>
                        </div>


                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Address') </label>
                                    <input class="form-control" type="text" name="address" value="{{@$host->address->address}}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label font-weight-bold">@lang('City') </label>
                                    <input class="form-control" type="text" name="city" value="{{@$host->address->city}}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('State') </label>
                                    <input class="form-control" type="text" name="state" value="{{@$host->address->state}}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Zip/Postal') </label>
                                    <input class="form-control" type="text" name="zip" value="{{@$host->address->zip}}">
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label font-weight-bold">@lang('Country') </label>
                                    <select name="country" class="form-control">
                                        @foreach($countries as $key => $country)
                                            <option value="{{ $key }}" @if($country->country == @$host->address->country ) selected @endif>{{ __($country->country) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="form-group col-xl-4 col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('Status') </label>
                                <input type="checkbox" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Banned')" data-width="100%"
                                       name="status"
                                       @if($host->status) checked @endif>
                            </div>

                            <div class="form-group  col-xl-4 col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('Email Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="ev"
                                       @if($host->ev) checked @endif>

                            </div>

                            <div class="form-group  col-xl-4 col-md-6  col-sm-3 col-12">
                                <label class="form-control-label font-weight-bold">@lang('SMS Verification') </label>
                                <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger"
                                       data-toggle="toggle" data-on="@lang('Verified')" data-off="@lang('Unverified')" name="sv"
                                       @if($host->sv) checked @endif>

                            </div>
                        </div>


                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Save Changes')
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-50">
                <div class="card-body">
                    <h5 class="card-title border-bottom pb-2">@lang('PAY DETAILS') </h5>

                    <form action="{{route('admin.hosts.pay_update',[$host->wallet->id])}}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <label class="form-control-label font-weight-bold" for="type">  Type of Account </label>
                        @foreach($type as $key => $value)
                            <div class="form-radio form--radio col-3">
                                <input type="radio" class="form-radio-input" id="type" name="type" value="{{ $key }}" checked>
                                <label class="form-radio-label form--label" for="type">
                                    {{ $value }}
                                </label>
                            </div>
                        @endforeach
                        <div class="row" id="mobile">
                            <div class="form--group col-md-6">
                                <label class="form-control-label font-weight-bold" for="firstname">@lang('First Name')</label>
                                <input type="text" class="form-control form--control" id="firstname" name="first_name" placeholder="@lang('First Name')" value="{{ isset($wallet->firstName) ? $wallet->firstName  : '' }}">
                            </div>
                            <div class="form--group col-md-6">
                                <label class="form-control-label font-weight-bold" for="lastname">@lang('Last Name')</label>
                                <input type="text" class="form-control form--control" id="lastname" name="last_name" placeholder="@lang('Last Name')" value="{{ isset($wallet->lastName) ? $wallet->lastName  : '' }}">
                            </div>
                            <div class="form--group col-md-6">
                                <label class="form-control-label font-weight-bold" for="email">@lang('E-mail Address')</label>
                                <input class="form-control form--control" id="email" name="email" placeholder="@lang('E-mail Address')" value="{{ isset($wallet->email) ? $wallet->email  : '' }}" >
                            </div>
                            <div class="form--group col-md-6">
                                <label class="form-control-label font-weight-bold" for="phone">@lang('Phone Number')</label>
                                <input class="form-control form--control" id="phone" name="phone_number" placeholder="254712121212" value="{{ isset($wallet->phoneNumber) ? $wallet->phoneNumber : '' }}">
                            </div>
                            <div class="form--group col-md-6">
                                <label class="form-control-label font-weight-bold" for="network">@lang('Network')</label>
                                <select name="network" id="network" class="form-control">
                                    <option value=""> -- Select Mobile Network --</option>
                                    @foreach($networks as $key => $network)
                                        <option value="{{ $network }}" data-code="{{ $key }}"  {{ isset($wallet->network)?  $wallet->network == $key ? 'selected' : '' : ''}}>{{ __($network) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn--primary btn-block btn-lg">@lang('Update')
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
