@extends('layouts.adminlte-boot-4.user')

@section('content')
<div class="content-wrapper">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row">
            <div class="col-sm-4">
               <h1 class="m-0 text-dark">Companies <small class="badge badge-secondary"></small></h1>
            </div>
            <div class="col-sm-8">
               <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item">
                     <a class="btn btn-sm btn-secondary mr-1 px-3" href="{{ url('/products/create') }}"><i class="far fa-plus-square mr-1"></i>{{ __('New Product') }}
                     </a>
                     <a class="btn btn-sm btn-primary mr-1 px-3" href="{{ route('companies.create') }}"><i class="far fa-plus-square mr-1"></i>{{ __('custom.new') }}
                     </a>
                     <a class="btn btn-sm btn-outline-secondary" href="{{ url('reports/companies') }}"><i class="fa fa-chart-pie"></i></a>
                  </li>
               </ol>
            </div>
         </div>
      </div>
   </div>
   <livewire:company.all-lists />

</div>
@endsection
