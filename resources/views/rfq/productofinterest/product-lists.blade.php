@extends('layouts.adminlte-boot-4.front-main')

@section('title', 'Products')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3>
                @if(request('category'))
                    {{ request('category') }}
                @elseif(request('subcategory'))
                    {{ request('subcategory') }}
                @else
                    {{ request('productname') }}
                @endif
            </h3>
        </div>
        @forelse($product_lists_based_on_requests as $product)
        <div class="col-lg-3 col-md-4 mb-3">
            <div class="card border h-100">
                @if(!empty($product->picture))
                <a href="{{ url('shop/product/'.$product->slug) }}" target="_blank">
                    <img src="{{ $product->productImageUrl() }}" class="card-img-top card-img-top-custom" alt="">
                </a>
                @endif
                <div class="card-body">
                    <h5 class="">
                        <a href="{{ url('shop/product/'.$product->slug) }}" target="_blank">
                            {{ $product->name }}
                        </a>
                    </h5>
                    <span class="d-block m-0">
                        {!! $currency->html_code !!} {{ $product->price }}
                    </span>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <h3 class="text-center">No Products Found!</h3>
        </div>
        @endforelse
        <div class="col-12 d-flex justify-content-center">
            {{ $product_lists_based_on_requests->links() }}
        </div>
    </div>
</div>

@endsection
