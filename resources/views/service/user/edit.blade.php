@extends('layouts.adminlte-boot-4.user')
@section('content')

<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-lg-12 pb-2">
                <h1>
                    <ion-icon name="shirt-outline"></ion-icon> {{ __('custom.services.edit') }}
                </h1>

            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6">
                   <livewire:service.service-form :service="$service" />
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
