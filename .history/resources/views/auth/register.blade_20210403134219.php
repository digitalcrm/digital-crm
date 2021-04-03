@extends('layouts.app')
@section('content')
    @push('styles')
        <style>
            body {
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Noto Sans", Ubuntu, Cantarell, "Helvetica Neue", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji" !important;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #ecf1f7 !important;
            }

            /* .navbar-laravel {
                                display: none !important;
                            } */

            .btn-primary {
                background-color: #024A81 !important;
                border-color: #024A81 !important;
                color: #fff !important;
            }

            .btn-primary:hover {
                background-color: #0d5e9c !important;
                border-color: #0d5e9c !important;
                color: #fff !important;

                .shadowTop {
                    box-shadow: 0 0 0px rgba(0, 0, 0, .125), 0 0px 0px rgba(0, 0, 0, .2) !important;
                    border: 1px solid #e5e5e5 !important;
                    border-top: 3px solid #60a5fa !important;
                }

                .form-control-lg {
                    padding: .6rem 1rem !important;
                    font-size: 14px !important;
                }

                .label-color {
                    color: #898989 !important;
                }

        </style>
    @endpush
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center mt-5 pb-4">
                    <img alt="company-logo" src="{{ asset('uploads/logo/digitalcrm-logo.png') }}" height="55px" />
                </div>
                <div class="card shadows no-border"
                    style="box-shadow:0 1px 3px 0 rgba(0,0,0,0.1),0 1px 2px 0 rgba(0,0,0,0.06);border: 0px solid rgba(0,0,0,.125)!important;width:350px;">
                    <div class="card-body py-3">
                        <h4 class="mb-4" style="color:#404040;">{{ __('Register Now') }}</h4>
                        <form method="POST" action="{{ route('register') }}" aria-label="{{ __('Register') }}">
                            @csrf

                            <div class="form-group mb-4">
                                <!-- <label for="name"
                                    class="text-uppercase small font-weight-bold label-color">{{ __('Name') }}</label> -->

                                <div class="">
                                    <input id="name" type="text"
                                        class="form-control form-control-lg{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                        placeholder="Your name" name="name" value="{{ old('name') }}" required autofocus>

                                    @if ($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <!-- <label for="email"
                                    class="text-uppercase small font-weight-bold label-color">{{ __('E-Mail Address') }}</label> -->

                                <div class="">
                                    <input id="email" type="email"
                                        class="form-control form-control-lg{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        placeholder="Email address" name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <!-- <label for="password"
                                    class="text-uppercase small font-weight-bold label-color">{{ __('Password') }}</label> -->

                                <div class="">
                                    <input id="password" type="password"
                                        class="form-control form-control-lg{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                        placeholder="EmPassword" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <!-- <label for="password-confirm"
                                    class="text-uppercase small font-weight-bold label-color">{{ __('Confirm Password') }}</label> -->

                                <div class="">
                                    <input id="password-confirm" type="password" class="form-control form-control-lg"
                                    placeholder="Confirm password" name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="">
                                    <x-captcha />
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-primary btn-block">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <a class="btn-text btn-block text-center py-3" href="{{ route('login') }}">
                    {{ __('Already member? Login') }}
                </a>
            </div>
        </div>
    </div>
@endsection
