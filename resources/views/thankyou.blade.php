@extends('layouts.ecommerce')

@section('title')
<title>Daftar Mitra - {{ env('APP_NAME') }}</title>
@endsection

@section('content')
<!--================Home Banner Area =================-->
<section class="banner_area">
    <div class="banner_inner d-flex align-items-center">
        <div class="container">
            <div class="banner_content text-center">
                <h2>Daftar Mitra</h2>
                <div class="page_link">
                    <a href="{{ url('/') }}">Home</a>
                    <a href="">Daftar Mitra</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Home Banner Area =================-->

<!--================Order Details Area =================-->
<section class="order_details p_120">
    <div class="container">
        <h3 class="">Selamat Anda berhasil menjadi mitra kami.</h3>
        <p>Silahkan cek email anda untuk melakukan verifikasi.</p>

    </div>
</section>
<!--================End Order Details Area =================-->

@endsection
