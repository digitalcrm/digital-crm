@extends('layouts.adminlte-boot-4.user')

@section('title', 'RFQ Forms')

@section('content')


<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1><i class="far fa-edit"></i> Tell supplier what you need</h1>
                </div>
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content mx-0">
        <div class="container-fluid">
            <livewire:rfq.rfq-form />
        </div>
    </section>
</div>
@endsection
