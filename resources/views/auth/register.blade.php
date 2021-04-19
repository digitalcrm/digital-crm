<x-layout>
    <x-slot name="title">
        {{ __('custom.register') }}
    </x-slot>
    @push('styles')
        <link href="{{ asset('css/login-register.css') }}" rel="stylesheet">
    @endpush
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center mt-5 pb-4">
                    <x-logo />
                </div>
                <div class="card shadows no-border"
                    style="box-shadow:0 1px 3px 0 rgba(0,0,0,0.1),0 1px 2px 0 rgba(0,0,0,0.06);border: 1px solid rgba(0,0,0,.125)!important;width:350px;">
                    <div class="card-body py-3">
                        <h4 class="mb-4" style="color:#404040;">{{ __('Register Now') }}</h4>
                        <form method="POST" action="{{ route('register') }}"
                            aria-label="{{ __('Register') }}">
                            @csrf

                            <div class="form-group mb-2">

                                <div class="">
                                    <input id="name" type="text"
                                        class="form-control form-control-lg{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                        placeholder="Your name" name="name" value="{{ old('name') }}"
                                        required autofocus>

                                    @if($errors->has('name'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group mb-2">

                                <div class="">
                                    <input id="email" type="email"
                                        class="form-control form-control-lg{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        placeholder="Email address" name="email"
                                        value="{{ old('email') }}" required>

                                    @if($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <div class="">
                                    <input id="mobile" type="tel"
                                        class="form-control form-control-lg{{ $errors->has('mobile') ? ' is-invalid' : '' }}"
                                        placeholder="Your mobile" name="mobile"
                                        value="{{ old('mobile') }}" required autofocus>

                                    @if($errors->has('mobile'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('mobile') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <div class="">
                                    <input id="company" type="text"
                                        class="form-control form-control-lg{{ $errors->has('company') ? ' is-invalid' : '' }}"
                                        placeholder="Your Company" name="company"
                                        value="{{ old('company') }}" required autofocus>

                                    @if($errors->has('company'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('company') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group mb-2">

                                <div class="">
                                    <input id="password" type="password"
                                        class="form-control form-control-lg{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                        placeholder="Password" name="password" required>

                                    @if($errors->has('password'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group mb-2">

                                <div class="">
                                    <input id="password-confirm" type="password" class="form-control form-control-lg"
                                        placeholder="Confirm password" name="password_confirmation" required>
                                </div>
                            </div>

                            <!--<div class="form-group">-->
                            <!--    <div class="">-->
                            <!--        <x-captcha />-->
                            <!--    </div>-->
                            <!--</div>-->
                            <div class="small text-muted pb-2">
                                By continuing, you acknowledge that you accept our
                                <a href="https://www.bigindia.com/terms" rel="noopener noreferrer" target="_blank">
                                    Terms & Conditions 
                                </a> 
                                and 
                                <a href="https://www.bigindia.com/privacy" rel="noopener noreferrer" target="_blank">
                                    Privacy Policy
                                </a>
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
</x-layout>
