@extends('layouts.adminlte-boot-4.admin')
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-10 float-left">
                <h1 class="pb-2">
                    Edit Service Subcategory
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
                        <form action="{{ route('service-subcategories.update', $service_subcategory->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <x-label for="name" class="control-label"
                                                :value="__('Service Subcategory')" />
                                        </div>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" name="name" id="name"
                                                placeholder="Service Category"
                                                value="{{ $service_subcategory->name }}">
                                            <x-error-message :value="__('name')" />
                                        </div>
                                    </div>
                                </div>
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
                                                    <option value="{{ $serviceCategory->id }}"
                                                        {{ old('servcategory_id', $service_subcategory->servcategory_id) === $serviceCategory->id  ? 'selected' : '' }}>
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
                                    class="btn btn-info float-right">{{ __('custom.save') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
