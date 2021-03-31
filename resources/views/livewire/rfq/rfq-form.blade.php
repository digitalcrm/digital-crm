<div wire:ignore.self class="row">
    <div class="col-md-6">
        <div>
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5>
                            Product Basic Information
                        </h5>
                    </div>
                </div>
            </div>

            <form wire:submit.prevent="store" method="post" enctype="multipart/form-data">
                {{-- <form action="{{ route('rfq-forms.store') }}" method="post" enctype="multipart/form-data">
                @csrf --}}
                <div class="card-body">
                    <div class="form-group">
                        <label for="product_name" class="col-form-label">Product Name:</label>
                        <input wire:model="product_name" type="text" class="form-control required" id="product_name"
                            name="product_name" placeholder="eg: shoes" required>
                        @error('product_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="product_category_id">Category</label>
                        <select class="form-control required" wire:model="product_category_id"
                            name="product_category_id" id="product_category_id" required>
                            <option value="0">Select Category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->procat_id }}">{{ $cat->category }}</option>
                            @endforeach
                        </select>
                        @error('product_category_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="sub_category_id">SubCategory</label>
                        <select class="form-control required" wire:model="sub_category_id" name="sub_category_id"
                            id="sub_category_id" required>
                            <option value="">select subcategory</option>
                            @foreach ($subcategories as $subcat)
                                <option value="{{ $subcat->prosubcat_id }}">{{ $subcat->category }}</option>
                            @endforeach
                        </select>
                        @error('sub_category_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="company_id">Company</label>
                        <select class="form-control required" wire:model="company_id" name="company_id"
                            id="company_id" required>
                            <option value="">select company</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->c_name }}</option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <div class="row">
                            <div class="col-6">
                                <input wire:model="product_quantity" type="number" min="1" name="product_quantity"
                                    class="form-control required" required>
                                @error('product_quantity')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="col-6">
                                <select class="form-control required" wire:model="unit_id" name="unit_id" id="unit_id"
                                    required>
                                    <option value="0">select units</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->unit_id }}">{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Price</label>
                        {{-- <label class="sr-only" for="inlineFormInputGroup">Username</label> --}}
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text">{!! $currencies->html_code !!}</div>
                            </div>
                            <input wire:model="purchase_price" type="number" min="1" name="purchase_price"
                                class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Destination</label>
                        <input wire:model="city" type="text" name="city" class="form-control required"
                            placeholder="enter city name" required>
                        @error('city')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="details" class="col-form-label">Message:</label>
                        <textarea wire:model="details" class="form-control required" id="details" name="details"
                            required></textarea>
                        @error('details')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="images" class="col-form-label">
                            <i class="fas fa-exclamation-circle" data-toggle="tooltip" data-placement="top"
                                title="Upload multiple documents"></i> Documents:
                        </label>
                        <input type="file" wire:model="images" id="images{{ $iteration }}" name="images" multiple>
                        <span class="d-block text-muted ml-2">only jpeg/jpg/png/docx/pdf file are required</span>
                        @error('images.*') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-check">
                        <input wire:model="isChecked" id="isChecked" class="form-check-input" type="checkbox"
                            name="isChecked">
                        <label for="isChecked" class="form-check-label">Accept Terms & Condition</label>
                        @error('isChecked')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="card-footer">
                        <a class="btn btn-info" href="{{ url()->previous() }}">Back</a>
                        <button type="submit" class="btn btn-primary float-right">Submit</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</div>
