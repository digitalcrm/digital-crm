@extends('layouts.adminlte-boot-4.admin')

@section('content')

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-10 mt-0">
                    <h1 class="m-0 text-dark">{{ __('custom.services.heading') }}</h1>
                </div>
                <div class="col-sm-2" id="filtertable">
                    {{-- Here datatable select field appears --}}
                </div>
            </div>
        </div>
    </div>
    <x-alert />
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <livewire:service.admin.all-services />
            </div>
        </div>
    </section>
</div>

@endsection
