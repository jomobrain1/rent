@php
    $subscribe_content = getContent('subscribe.content', true);
    $footer_content = getContent('footer.content', true);
    $contact = getContent('contact.content', true);
    $social_icons = getContent('social_icon.element', false, null, true);
    $policy_pages = getContent('policy_pages.element', false, null, true);
@endphp
<!-- Footer Section -->
<footer class="footer-section">
    <div class="container">
        <div class="newsletter-section">
            <div class="newsletter-wrapper">
                <div class="footer-logo">
                    <a href="{{ route('home') }}">
                        <img src="{{getImage(imagePath()['logoIcon']['path'] .'/logo4.png')}}" alt="logo">
                    </a>
                </div>
                <div class="newsletter-title">
                    <div class="section__header border-0">
                        <h4 class="section__title mb-0">@lang('Newsletter') <span class="text--base">@lang('Subscription')</span></h4>
                        <p>{{ __(@$subscribe_content->data_values->content) }}</p>
                    </div>
                </div>
                <div class="newsletter-form">
                    <form action="{{ route('subscribe') }}" id="subscribeForm" method="post">
                        @csrf

                        <div class="input-group">
                            <input name="email" type="email" class="form-control form--control subscribe_email" placeholder="@lang('Enter your email address')"/>
                            <button type="submit" class="input-group-text cmn--btn">@lang('Subscribe')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="footer__top">
            <div class="footer-wrapper">
                <div class="footer__widget widget__about">
                    <h4 class="widget__title">@lang('About') @lang($general->sitename)</h4>
                    <p>{{ __(@$footer_content->data_values->content) }}</p>
                    <ul class="social-icons">
                        @forelse($social_icons as $item)
                            <li>
                                <a href="{{ $item->data_values->url }}">
                                    @php echo @$item->data_values->social_icon @endphp
                                </a>
                            </li>
                        @empty
                        @endforelse
                    </ul>
                </div>
                <div class="footer__widget">
                    <h4 class="widget__title">@lang('General Links')</h4>
                    <ul class="widget__links">
                        <li><a href="{{ route('home') }}">@lang('Home')</a></li>

                        @foreach($pages as $k => $data)
                            <li><a href="{{route('pages',[$data->slug])}}">{{__($data->name)}}</a></li>
                        @endforeach
                        <li><a href="{{ route('vehicles') }}">@lang('Vehicles')</a></li>
                        <li><a href="{{ route('blogs') }}">@lang('Blog')</a></li>
                    </ul>
                </div>
                <div class="footer__widget">
                    <h4 class="widget__title">@lang('Policy Pages')</h4>
                    <ul class="widget__links">

                        @forelse($policy_pages as $policy)
                            <li><a href="{{ route('policy.pages', [$policy->id, slug($policy->data_values->title)]) }}">{{ __(@$policy->data_values->title) }}</a></li>
                        @empty
                        @endforelse

                    </ul>
                </div>
                <div class="footer__widget widget__contact">
                    <h4 class="widget__title">@lang('Get In Touch')</h4>
                    <ul class="footer__contact">
                        <li>
                            <div class="icon"><i class="las la-phone-volume"></i></div>
                            <div class="cont">
                                <span class="subtitle">@lang('For Support')</span>
                                <a href="Tel:{{ $contact->data_values->phone }}" class="info">{{ $contact->data_values->phone }}</a>
                            </div>
                        </li>
                        <li>
                            <div class="icon"><i class="las la-headset"></i></div>
                            <div class="cont">
                                <span class="subtitle">@lang('Send Us Email')</span>
                                <a href="mailto:{{ $contact->data_values->email }}" class="info">{{ $contact->data_values->email }}</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer__bottom bg--section py-3 text-center">{{ __(@$footer_content->data_values->copyright) }}</div>
</footer>
<!-- Footer Section -->
