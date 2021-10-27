@extends('layouts.ecommerce')

@section('title')
    <title>Contact - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
    <!--================Home Banner Area =================-->
    <section class="banner_area">
        <div class="banner_inner d-flex align-items-center">
            <div class="container">
                <div class="banner_content text-center">
                    <h2>Contact</h2>
                    <div class="page_link">
                        <a href="{{ route('front.index') }}">Home</a>
                        <a href="{{ route('contact') }}">Contact</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--================End Home Banner Area =================-->

    <!--================Category Product Area =================-->
    <section class="cat_product_area section_gap">
        <div class="container">
            <div class="row">
                <div class="offset-lg-3 col-lg-7">
                    <h1>
                        PPMUPT SMART ASSET BUSINESS
                        UNIVERSITAS KOMPUTER INDONESIA
                    </h1>
                    <hr>
                    <br>
                    <h3>
                        <p>www.smartassetsbusiness.com</p>
                        <p>email : smartassetbusiness@gmail.com</p>
                        <p>Jl.Dipatiukur No.112-116 Kota Bandung, Jawa Barat</p>
                    </h3>
                </div>
            </div>
        </div>
    </section>
    <!--================End Category Product Area =================-->
@endsection
