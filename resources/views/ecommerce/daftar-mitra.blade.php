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
                <h2>Daftar Mitra/Register</h2>
                <div class="page_link">
                    <a href="{{ url('/') }}">Home</a>
                    <a href="{{ route('customer.login') }}">Daftar Mitra</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Home Banner Area =================-->

<!--================Daftar Mitra Box Area =================-->
<section class="login_box_area p_120">
    <div class="container">
        <div class="row">
            <div class="offset-md-3 col-lg-6">
                @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="login_form_inner">
                    <h3>Form Registrasi</h3>
                    <form class="row login_form" action="{{ route('register') }}" method="post" id="contactForm"
                        novalidate="novalidate">
                        @csrf
                        <div class="col-md-12 form-group">
                            <input type="name" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" placeholder="Name" value="{{ old('name') }}">
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-12 form-group">
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" placeholder="Email Address" value="{{ old('email') }}">
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-12 form-group">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Password">
                            @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-12 form-group">
                            <input type="password" class="form-control" id="password" name="password_confirmation"
                                placeholder="Confirm Password">
                        </div>

                        <div class="col-md-12 form-group">
                            <button type="submit" value="submit" class="btn submit_btn">DAFTAR</button>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('customer.login') }}">Back</a>
                                <a href="{{ route('login') }}">Login mitra?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
