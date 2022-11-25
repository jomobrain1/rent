<!doctype html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> {{ $general->sitename(__($pageTitle)) }}</title>

    @include('partials.seo')

    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/line-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/magnific-popup.min.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/owl.min.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/main.css')}}">

    <link rel="stylesheet"
          href="{{asset($activeTemplateTrue.'css/color.php?color='.$general->base_color.'&secondColor='.$general->secondary_color)}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/bootstrap-fileinput.css')}}">
    <link rel="stylesheet" href="{{asset($activeTemplateTrue.'css/custom.css')}}">
    <!-- bootstrap toggle css -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/vendor/bootstrap-toggle.min.css')}}">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"/>
    <!-- Bootstrap-Iconpicker -->
    <link rel="stylesheet" href="dist/css/bootstrap-iconpicker.min.css"/>

    @stack('style-lib')

    @stack('style')
</head>
<body>

<div class="overlay"></div>
<a href="#" class="scrollToTop"><i class="las la-angle-up"></i></a>

<!-- Preloader -->
<div class="preloader">
    <figure class="loader">
        <div class="car">
            <span class="car-body"></span>
            <span class="wheels"></span>
        </div>
        <div class="strikes">
            <span></span>
            <span></span>
            <span></span>
            <span></span>
            <span></span>
        </div>
    </figure>
</div>
<!-- Preloader -->

<!-- Header Section -->
<div class="header-top py-2">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between mx--10">
            <div class="header-top-item meta-list">
                <a href="Mailto:{{ getContent('contact.content', true)->data_values->email }}"><i
                        class="lar la-envelope"></i>{{ getContent('contact.content', true)->data_values->email }}</a>
            </div>
            <div class="d-flex flex-wrap meta-list">
                @auth
                    <div class="header-top-item ml-sm-auto">
                        <a href="{{ route('host.register') }}"><i class="las la-tachometer-alt"></i>Become a Host</a>
                    </div>
                    <div class="header-top-item ml-sm-auto">
                        <a href="{{ route('user.home') }}"><i class="las la-tachometer-alt"></i>@lang('Dashboard')</a>
                    </div>
                    <div class="header-top-item">
                        <a href="{{ route('user.logout') }}"><i class="las la-sign-out-alt"></i>@lang('Logout')</a>
                    </div>
                @endauth
                @auth('host')
                    <div class="header-top-item ml-sm-auto">
                        <a href="{{ route('host.dashboard') }}"><i class="las la-tachometer-alt"></i>@lang('Dashboard')</a>
                    </div>
                    <div class="header-top-item">
                        <a href="{{ route('host.logout') }}"><i class="las la-sign-out-alt"></i>@lang('Logout')</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
<div class="header-bottom">
    <div class="container">
        <div class="header-wrapper">
            <div class="logo">
                <a href="{{ route('home') }}"><img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo4.png')}}"
                                                   alt="logo"></a>
            </div>
            <ul class="menu">
                @auth
                    <li><a href="{{ route('user.home') }}">@lang('Dashboard')</a></li>
                    <li>
                        <a href="#0">@lang('Vehicle')</a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('vehicles') }}">@lang('All Vehicles')</a>
                            </li>
                            <li>
                                <a href="{{ route('user.vehicle.booking.log') }}">@lang('Booking Logs')</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#0">@lang('Support')</a>
                        <ul class="submenu">
                            <li>
                                <a href="{{route('ticket.open')}}">@lang('Create New')</a>
                            </li>
                            <li>
                                <a href="{{route('ticket')}}">@lang('My Ticket')</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#0">@lang('Account')</a>
                        <ul class="submenu">
                            <li>
                                <a href="{{route('user.deposit.history')}}">@lang('Payment History')</a>
                            </li>
                            <li>
                                <a href="{{ route('user.change.password') }}">@lang('Change Password')</a>
                            </li>
                            <li>
                                <a href="{{ route('user.profile.setting') }}">@lang('Profile Setting')</a>
                            </li>
                            <li>
                                <a href="{{ route('user.twofactor') }}">@lang('2FA Security')</a>
                            </li>
                            <li>
                                <a href="{{ route('user.logout') }}">@lang('Logout')</a>
                            </li>
                        </ul>
                    </li>
                @endauth
                @auth('host')
                    <li><a href="{{ route('host.dashboard') }}">@lang('Dashboard')</a></li>
                    <li>
                        <a href="#0">@lang('Vehicle')</a>
                        <ul class="submenu">
                            <li>
                                <a href="{{ route('host.vehicles.index') }}">@lang('All Vehicles')</a>
                            </li>
                            <li>
                                <a href="{{ route('host.vehicles.booking.log') }}">@lang('Booking Logs')</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#0">@lang('Account')</a>
                        <ul class="submenu">
                            <li>
                                <a href="{{route('host.requests')}}">@lang('Withdrawal Requests History')</a>
                            </li>
                            <li>
                                <a href="{{ route('host.change.password') }}">@lang('Change Password')</a>
                            </li>
                            <li>
                                <a href="{{ route('host.profile.setting') }}">@lang('Profile Setting')</a>
                            </li>
                            <li>
                                <a href="{{ route('host.pay.setting') }}">@lang('PAY Details ')</a>
                            </li>
                            <li>
                                <a href="{{ route('host.logout') }}">@lang('Logout')</a>
                            </li>
                        </ul>
                    </li>
                @endauth

                <li class="py-3">
                    <select class="langSel language-select ms-3">
                        @foreach($language as $item)
                            <option value="{{$item->code}}"
                                    @if(session('lang') == $item->code) selected @endif>{{ __($item->name) }}</option>
                        @endforeach
                    </select>
                </li>
            </ul>
            <div class="header-bar">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </div>
</div>
<!-- Header Section -->

<!-- Breadcrumb section start -->
@include($activeTemplate.'partials.breadcrumb')
<!-- Breadcrumb section end -->

<!-- Dashboard Section Starts Here -->
<main class="dashboard-section bg--section pt-60 pb-60">
    <div class="container">

        @yield('content')

    </div>
</main>
<!-- Dashboard Section Ends Here -->

<!-- footer section start -->
@include($activeTemplate.'partials.footer')
<!-- footer section end -->

<script src="{{asset($activeTemplateTrue.'js/jquery-3.6.0.min.js')}}"></script>
<script src="{{asset($activeTemplateTrue.'js/jquery-ui.js')}}"></script>
<script src="{{asset($activeTemplateTrue.'js/bootstrap.min.js')}}"></script>
<!-- bootstrap js -->
<script src="{{asset('assets/admin/js/vendor/bootstrap.bundle.min.js')}}"></script>
<!-- Bootstrap-Iconpicker Bundle -->
<script type="text/javascript" src="dist/js/bootstrap-iconpicker.bundle.min.js"></script>
<!-- bootstrap-toggle js -->
<script src="{{asset('assets/admin/js/vendor/bootstrap-toggle.min.js')}}"></script>
<script src="{{asset($activeTemplateTrue.'js/rafcounter.min.js')}}"></script>
<script src="{{asset($activeTemplateTrue.'js/magnific-popup.min.js')}}"></script>
<script src="{{asset($activeTemplateTrue.'js/owl.min.js')}}"></script>
<script src="{{asset($activeTemplateTrue.'js/main.js')}}"></script>

<script src="{{asset($activeTemplateTrue.'js/bootstrap-fileinput.js')}}"></script>

<script src="{{ asset($activeTemplateTrue.'js/jquery.validate.js') }}"></script>

@stack('script-lib')



@include('partials.notify')

@include('partials.plugins')

@stack('script')

<script>

    (function ($) {
        "use strict";
        $(".langSel").on("change", function () {
            window.location.href = "{{route('home')}}/change/" + $(this).val();
        });

    })(jQuery);

</script>


<script>
    (function ($) {
        "use strict";

        $("form").validate();
        $('form').on('submit', function () {
            if ($(this).valid()) {
                $(':submit', this).attr('disabled', 'disabled');
            }
        });

    })(jQuery);

</script>

</body>
</html>
