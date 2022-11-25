@extends($activeTemplate.'layouts.frontend')
@section('content')
    <section class="blog-section pt-120 pb-120 bg--section">
        <div class="container">
            <div class="row gy-5 justify-content-center">
                <div class="col-lg-12">
                    <div class="post__details">

                        @php echo @$policy->data_values->details @endphp

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
