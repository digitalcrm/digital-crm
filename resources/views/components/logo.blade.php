@if(config('custom_appdetail.logoEnable'))
    <img alt="company-logo" src="{{ config('custom_appdetail.logo') }}" width="150px" />
@else
    <h3>{{ config('app.name') }}</h3>
@endif
