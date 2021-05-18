@extends('layouts.adminlte-boot-4.admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-10 float-left">
                <h1 class="pb-2">
                    New Service Subcategory
                </h1>
            </div>
            <div class="col-md-2 float-right">

            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            @include('includes.partials.validation-message')
            <div class="row">
                <div class="col-lg-4">
                    <div class="card shadow card-primary card-outline">
                        <form action="{{ route('service-subcategories.store') }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                @if(request('import') === 'csv')
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <x-label for="fileImport" class="control-label"
                                                    :value="__('fileImport')" />
                                            </div>
                                            <div class="col-md-12">
                                                <input type="file" class="btn btn-default" name="fileImport"
                                                    id="fileImport" />
                                                <x-error-message :value="__('image')" />
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <x-label for="name" class="control-label"
                                                    :value="__('Service Subcategory')" />
                                            </div>
                                            <div class="col-md-12">
                                                <input type="text" class="form-control" name="name" id="name"
                                                    placeholder="Service Category">
                                                <x-error-message :value="__('name')" />
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <x-label for="servcategory_id" class="control-label"
                                                :value="__('Service Category')" />
                                        </div>
                                        <div class="col-md-12">
                                            <select class="form-control" name="servcategory_id" id="servcategory_id"
                                                required>
                                                <option value="0">{{ __('Select Category') }}
                                                </option>
                                                @forelse($categories as $serviceCategory)
                                                    <option value="{{ $serviceCategory->id }}">
                                                        {{ $serviceCategory->name }}</option>
                                                @empty
                                                    <option value="0">{{ __('Not Found') }}</option>
                                                @endforelse
                                            </select>
                                            <x-error-message :value="__('servcategory_id')" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top">
                                <button type="submit"
                                    class="btn btn-info float-right">{{ __('custom.create') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
