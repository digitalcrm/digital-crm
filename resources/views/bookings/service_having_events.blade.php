@extends('layouts.adminlte-boot-4.booking')

@section('content')

<div class="container">
    <div class="row featured-post-heading">
        <div class="col-md-12 mt-5 mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item">
                            <a class="float-right"
                                href="{{ route('service.events',['bookservice'=>$bookservice->name]) }}"
                                data-toggle="tooltip" data-placement="left" title="Gallery View">
                                <i class="fas fa-th"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a class="float-right"
                                href="{{ route('service.events',['bookservice'=>$bookservice->name,'lists'=> 'true']) }}"
                                data-toggle="tooltip" data-placement="left" title="List view">
                                <i class="fas fa-list"></i>
                            </a>
                        </li>
                    </ol>
                </nav>


        </div>
     </div>
    <div class="row">
        @if(request('lists') == 'true')
            @include('bookings.includes.lists')
        @else
            @include('bookings.includes.grid')
        @endif
    </div>
</div>


@endsection


