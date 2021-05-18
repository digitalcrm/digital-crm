@extends('layouts.adminlte-boot-4.admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-10 float-left">
                <h1 class="pb-2">
                    New Service Category
                </h1>
            </div>
            <div class="col-md-2 float-right">

            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card shadow card-primary card-outline">
                        <form action="{{ route('service-categories.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <x-label for="name" class="control-label" :value="__('Service Category')" />
                                    </div>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" name="name" id="name"
                                            placeholder="Service Category">
                                            <x-error-message :value="__('name')" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <x-label for="picture" class="control-label" :value="__('picture')" />
                                    </div>
                                    <div class="col-md-12">
                                        <input type="file" class="btn btn-default" name="image" id="picture" />
                                        <x-error-message :value="__('image')" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top">
                            <button type="submit" class="btn btn-info float-right">{{ __('custom.create') }}</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
