<x-layout>
    <x-slot name="title">
        {{ __('Reset Password')}}
    </x-slot>
    @push('styles')
        <link href="{{ asset('css/login-register.css') }}" rel="stylesheet">
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
                        <h4 class="mb-4" style="color:#404040;">{{ __('Reset Password') }}</h4>
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}"
                            aria-label="{{ __('Reset Password') }}">
                            @csrf

                            <div class="form-group">
                                <label for="email"
                                    class="text-uppercase small font-weight-bold label-color">{{ __('E-Mail Address') }}</label>

                                <div class="">
                                    <input id="email" type="email"
                                        class="form-control form-control-lg{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                        name="email" value="{{ old('email') }}" required>

                                    @if ($errors->has('email'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        {{ __('Send Password Reset Link') }}
                                    </button>
                                    <a class="btn-text btn-block text-center" href="{{ route('login') }}">
                                        {{ __('Login') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
