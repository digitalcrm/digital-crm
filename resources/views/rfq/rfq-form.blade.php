@extends('layouts.adminlte-boot-4.front-main')

@section('title', 'RFQ Forms')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <livewire:rfq.rfq-form />
        </div>
    </div>
</div>
@endsection
