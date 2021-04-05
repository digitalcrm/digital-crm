@extends('layouts.app')
@section('content')
    @push('styles')
        <link href="{{ asset('css/login-register.css') }}" rel="stylesheet">
    @endpush
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center mt-5 pb-4">
                    @if (session('error'))
                        <div class='text-danger pb-3' style="width:350px;">
                            {{ session('error') }}
                        </div>
                    @endif
                    <x-logo />
                </div>
                <div class="card shadows border"
                    style="box-shadow:0 1px 3px 0 rgba(0,0,0,0.1),0 1px 2px 0 rgba(0,0,0,0.06);border: 1px solid rgba(0,0,0,.125)!important;width:350px;">
                    <div class="card-body py-3">
                        <h4 class="mb-4" style="color:#404040;">{{ __('Sign in now') }}</h4>
                        <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                            @csrf

                            <div class="form-group mb-4">
                                <!-- <label for="email"
                                        class="text-uppercase small font-weight-bold label-color">{{ __('E-Mail Address') }}</label> -->
                                <div class="">
                                    <input id="email" type="email"
                                        class="form-control form-control-lg {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        placeholder="Email address" name="email" value="{{ old('email') }}" required
                                        autofocus>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <!-- <label for="password"
                                        class="text-uppercase small font-weight-bold label-color">{{ __('Password') }}</label> -->

                                <div class="">
                                    <input id="password" type="password"
                                        class="form-control form-control-lg{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                        placeholder="password" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="">
                                    <x-captcha />
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check custom-control custom-checkbox float-left">
                                    <input class="form-check-input custom-control-input" type="checkbox" name="remember"
                                        id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="remember">
                                        {{ __('Remember me') }}
                                    </label>
                                </div>
                                <a class="btn-text btn-block text-right" href="{{ route('password.request') }}">
                                    {{ __('Forgot password?') }}
                                </a>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-primary btn-block">
                                    {{ __('Login') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <a class="btn-text btn-block text-center mt-3"
                    href="{{ route('register') }}">{{ __('New user, Register') }}</a>


            </div>
        </div>
    @endsection
