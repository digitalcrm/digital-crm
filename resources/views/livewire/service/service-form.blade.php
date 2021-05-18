<div class="card shadow card-primary card-outline">

    @if($service)
        <form wire:submit.prevent="update" enctype="multipart/form-data">
        @else
        <form wire:submit.prevent="store" enctype="multipart/form-data">
    @endif
    <div class="card-body">
        <div class="form-group row">
            <x-label class="col-md-3 col-form-label text-right" for="name" :value="__('custom.services.name')" />
            <div class="col-md-9">
                <input wire:model="title" type="text" class="form-control required" name="title" id="title">
                <x-error-message :value="__('title')" />
            </div>
        </div>
        <div class="form-group row">
            <x-label class="col-md-3 col-form-label text-right" for="price" :value="__('custom.services.price')" />
            <div class="col-md-9">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <strong>{!! auth()->user()->currency->html_code ?? '$' !!}</strong>
                        </span>
                    </div>
                    <input type="number" wire:model="price" name="price" id="price" class="form-control required">
                </div>
                <x-error-message :value="__('price')" />
            </div>
        </div>

        <div class="form-group row">
            <x-label class="col-md-3 col-form-label text-right" for="servcategory_id"
                :value="__('custom.services.category')" />
            <div class="col-md-9">
                <select wire:model="servcategory_id" class="form-control required" id='servcategory_id'
                    name="servcategory_id">
                    <option value="0">Select Category</option>
                    @forelse($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @empty
                        <option>Not Found Category</option>
                    @endforelse
                </select>
                <x-error-message :value="__('servcategory_id')" />
            </div>
        </div>

        <div class="form-group row" wire:ignore.self>
            <x-label class="col-md-3 col-form-label text-right" for="subcategory"
                :value="__('custom.services.subcategory')" />
            <div class="col-md-9">
                {{-- <input type="text" wire:model="serv_subcategory_id" class="form-control required"
                        name="serv_subcategory_id" id="subcategory"> --}}
                <select wire:model="serv_subcategory_id" class="form-control required" id='serv_subcategory_id'
                    name="serv_subcategory_id" multiple>
                    <option value="0">Select SubCategory</option>
                    @forelse($subcategories as $subcat)
                        <option value="{{ $subcat->id }}">{{ $subcat->name }}</option>
                    @empty
                        <option>Not Found Select Category First</option>
                    @endforelse
                </select>
                <x-error-message :value="__('serv_subcategory_id')" />
            </div>
        </div>

        <div class="form-group row">
            <x-label class="col-md-3 col-form-label text-right" for="company" :value="__('custom.services.company')" />
            <div class="col-md-9">
                <select class="form-control required" wire:model="company_id" id='company' name="company">
                    <option value="0">Select Company</option>
                    @forelse($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->c_name }}</option>
                    @empty
                        <option>No Company Found</option>
                    @endforelse
                </select>
                <span class="small float-right block"><a href="{{ route('companies.create') }}"
                        target="_blank">+
                        Add new
                        company</a></span>
                <x-error-message :value="__('company_id')" />
            </div>
        </div>

        <div class="form-group row">
            <x-label class="col-md-3 col-form-label text-right" for="picture" :value="__('custom.services.image')" />
            <div class="col-md-9">
                <input type="file" wire:model="image" class="btn btn-default required" name="image" id="picture" />
                <x-error-message :value="__('image')" />
            </div>
        </div>

        <div class="form-group row">
            <x-label class="col-md-3 col-form-label text-right" for="description"
                :value="__('custom.services.description')" />
            <div class="col-md-9">
                <div class="">
                    <textarea wire:model="description" class="form-control required" name="description" id="description"
                        rows="5" tabindex="3"></textarea>
                    <x-error-message :value="__('description')" />
                </div>
            </div>
        </div>

        <div class="form-group row">
            <x-label class="col-md-3 col-form-label text-right" for="brand" :value="__('custom.services.brand')" />
            <div class="col-md-9">
                <input type="text" wire:model="brand" class="form-control" name="brand" id="brand">
                <x-error-message :value="__('brand')" />
            </div>
        </div>

        <div class="form-group row">
            <x-label class="col-md-3 col-form-label text-right" for="tags" :value="__('custom.services.tags')" />
            <div class="col-md-9">
                <input type="text" wire:model="tags" class="form-control" name="tags" id="tags">
                <x-error-message :value="__('tags')" />
            </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer bg-white border-top text-right pull-right">
            <a href="{{ url('/products') }}"
                class="btn btn-outline-secondary">{{ __('custom.cancel') }}</a>
            <button type="submit" class="btn btn-primary">{{ ($company) ? __('custom.submit') : __('custom.save') }}</button>
        </div>
        </form>

        <script>
            $(document).ready(function () {
                $('.js-example-basic-multiple').select2();
            });

        </script>
    </div>
