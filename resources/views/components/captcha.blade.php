<div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.sitekey') }}"
    data-error-callback="Fill the recaptcha"
    data-expired-callback="Your Recaptcha has expired, please verify it again !"></div>
@if ($errors->has('g-recaptcha-response'))
    <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
@endif
