@extends('layouts.adminlte-boot-4.cart')

@section('content')
<div class="col-lg-12">

<div class="col-lg-12">
    <div class="text-right">
        <a href="#">filters</a>
    </div>

    <div class="row">

        <input type="hidden" value="0" name="skipRec" id="skipRec">
        <div id="productsData" class="">
            @include('cart.products.list')
        </div>

    </div>
</div>
@endsection