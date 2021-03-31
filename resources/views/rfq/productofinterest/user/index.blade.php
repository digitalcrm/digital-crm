@extends('layouts.adminlte-boot-4.user')
@section('title', "RFQ's")
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-4 mt-0">
                    <h1 class="m-0 text-dark">Product Of Interests <small class="badge badge-secondary">{{ count($product_of_interests) }}</small>
                    </h1>

                </div>
                <div class="col-sm-8">

                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-8">
                                </div>
                                <div class="col-md-4 text-right">
                                    <a href="{{ route('pois.create') }}" 
                                        class="btn btn-primary" 
                                        data-toggle="modal" 
                                        data-target="#poiModal">
                                        Add New
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="card-body p-0 table-responsive"> --}}
                          <livewire:poi.poi />
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    // This closeModal trigged when savebutton is clicked
     window.addEventListener('closeModal', event => {
            $("#poiModal").modal('hide');      
            $(function () {
                toastr.options = {
                    "positionClass": "toast-top-left",
                    "progressBar": true,
                    "debug": false,
                }
                toastr.success(event.detail.message)
            });          
    });

    // This listener is not used yet
    window.addEventListener('openModal', event => {
        $("#poiModal").modal('show');
    });

    //This is used for alert message
    window.addEventListener('alert', event => {
        $(function () {
            toastr.options = {
                "positionClass": "toast-top-left",
                "progressBar": true,
                "debug": false,
            }
            toastr.success(event.detail.message)
        });  
    });
</script>

@include('ticketing.message')


@endsection
