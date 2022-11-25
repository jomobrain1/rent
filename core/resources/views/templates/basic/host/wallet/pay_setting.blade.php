@extends($activeTemplate.'layouts.master')
@section('content')
    @auth('host')
        <!-- Profile Section -->
        <div class="pt-60 pb-60">
            <div class="profile-wrapper bg--body">
                <div class="profile-form-area">
                    <form class="profile-edit-form row g-4" action="" method="post" enctype="multipart/form-data">
                        @csrf

                        <label class="form--label" for="type">  Type of Account </label>
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
                                <label class="form--label" for="firstname">@lang('First Name')</label>
                                <input type="text" class="form-control form--control" id="firstname" name="first_name" placeholder="@lang('First Name')" value="{{ isset($user->firstName) ? $user->firstName  : '' }}">
                            </div>
                            <div class="form--group col-md-6">
                                <label class="form--label" for="lastname">@lang('Last Name')</label>
                                <input type="text" class="form-control form--control" id="lastname" name="last_name" placeholder="@lang('Last Name')" value="{{ isset($user->lastName) ? $user->lastName  : '' }}">
                            </div>
                            <div class="form--group col-md-6">
                                <label class="form--label" for="email">@lang('E-mail Address')</label>
                                <input class="form-control form--control" id="email" name="email" placeholder="@lang('E-mail Address')" value="{{ isset($user->email) ? $user->email  : '' }}" >
                            </div>
                            <div class="form--group col-md-6">
                                <label class="form--label" for="phone">@lang('Phone Number')</label>
                                <input class="form-control form--control" id="phone" name="phone_number" placeholder="254712121212" value="{{ isset($user->phoneNumber) ? $user->phoneNumber  : '' }}">
                            </div>
                            <div class="form--group col-md-6">
                                <label class="form--label" for="network">@lang('Network')</label>
                                <select name="network" id="network" class="form--control">
                                    <option value=""> -- Select Mobile Network --</option>
                                    @foreach($networks as $key => $network)
                                        <option value="{{ $network }}" data-code="{{ $key }}"  {{ isset($wallet->network)?  $wallet->network == $key ? 'selected' : '' : ''}}>{{ __($network) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form--group w-100 col-md-6 mb-0 text-end">
                            <button type="submit" class="cmn--btn">@lang('Update Profile')</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <!-- Profile Section -->
    @endauth('host')
    @auth
        <!-- Profile Section -->
        <div class="pt-60 pb-60">
            <div class="profile-wrapper bg--body">
                <div class="profile-user mb-lg-0">
                    <div class="thumb">
                        <img src="{{ getImage(imagePath()['profile']['user']['path'].'/'. $user->image,imagePath()['profile']['user']['size']) }}" alt="user">
                    </div>
                    <div class="content">
                        <h6 class="title">@lang('Name'): {{ $user->name }}</h6>
                        <span class="subtitle">@lang('Username'): {{ $user->username }}</span>
                    </div>
                </div>
                <div class="profile-form-area">
                    <form class="profile-edit-form row g-4" action="" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form--group col-md-6">
                            <label class="form--label" for="name">@lang('Name')</label>
                            <input type="text" class="form-control form--control" id="name" name="name" placeholder="@lang('Name')" value="{{$user->name}}" required>
                        </div>
                        <div class="form--group col-md-6">
                            <label class="form--label" for="email">@lang('E-mail Address')</label>
                            <input class="form-control form--control" id="email" placeholder="@lang('E-mail Address')" value="{{$user->email}}" readonly>
                        </div>
                        <div class="form--group col-md-6">
                            <label class="form--label" for="mobile">@lang('Mobile Number')</label>
                            <input class="form-control form--control" id="phone" value="{{$user->mobile}}" readonly>
                        </div>
                        <div class="form--group col-md-6">
                            <label class="form--label" for="country">@lang('Address')</label>
                            <input type="text" class="form-control form--control" id="address" name="address" placeholder="@lang('Address')" value="{{@$user->address->address}}" required="">
                        </div>
                        <div class="form--group col-md-6">
                            <label class="form--label" for="city">@lang('State')</label>
                            <input type="text" class="form-control form--control" id="state" name="state" placeholder="@lang('state')" value="{{@$user->address->state}}" required="">
                        </div>
                        <div class="form--group col-md-6">
                            <label class="form--label" for="address">@lang('Zip Code')</label>
                            <input type="text" class="form-control form--control" id="zip" name="zip" placeholder="@lang('Zip Code')" value="{{@$user->address->zip}}" required="">
                        </div>
                        <div class="form--group col-md-6">
                            <label class="form--label" for="state">@lang('City')</label>
                            <input type="text" class="form-control form--control" id="city" name="city" placeholder="@lang('City')" value="{{@$user->address->city}}" required="">
                        </div>
                        <div class="form--group col-md-6">
                            <label class="form--label" for="zip">@lang('Country')</label>
                            <input class="form-control form--control" value="{{@$user->address->country}}" disabled>
                        </div>
                        <div class="form--group col-md-6">
                            <label class="form--label" for="profile-image">@lang('Change Profile Picture')</label>
                            <input type="file" name="image" class="form-control form--control" accept="image/*">
                            <code>@lang('Image size') {{imagePath()['profile']['user']['size']}}</code>
                        </div>
                        <div class="form--group w-100 col-md-6 mb-0 text-end">
                            <button type="submit" class="cmn--btn">@lang('Update Profile')</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <!-- Profile Section -->
    @endauth
@endsection

@push('style-lib')
    <link href="{{ asset($activeTemplateTrue.'css/bootstrap-fileinput.css') }}" rel="stylesheet">
@endpush
@push('style')
    <link rel="stylesheet" href="{{asset('assets/admin/build/css/intlTelInput.css')}}">
    <style>
        .intl-tel-input {
            position: relative;
            display: inline-block;
            width: 100%;!important;
        }
    </style>
@endpush
