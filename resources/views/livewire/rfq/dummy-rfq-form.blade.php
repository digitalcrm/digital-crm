<div wire:ignore.self>
    <div>
        @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <strong></strong>
            </div>
        @endif
    </div>
    <div class="card">
        <div class="card-header">Product Basic Information</div>
        <form action="{{ route('rfq-forms.store') }}" method="post" enctype="multipart/form-data">
            @csrf
        {{-- <form wire:submit.prevent="store"> --}}
            <div class="card-body">
                <div class="form-group">
                    <label for="product_name" class="col-form-label">Product Name:</label>
                    <input type="text"
                        wire:model="product_name" 
                        class="form-control @error('product_name') is-invalid @enderror"
                        id="product_name" 
                        name="product_name" 
                        placeholder="eg: shoes" 
                        value="{{ old('product_name') }}">
                    @error('product_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="product_category_id">Category</label>
                    <select wire:model="product_category_id" class="form-control @error('product_category_id') is-invalid @enderror"
                            name="product_category_id" id="product_category_id">
                        <option value="0">Select Category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->procat_id }}">{{ $cat->category }}</option>
                        @endforeach
                    </select>
                    @error('product_category_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="sub_category_id">SubCategory</label>
                    <select wire:model="sub_category_id" class="form-control @error('sub_category_id') is-invalid @enderror" name="sub_category_id"
                        id="sub_category_id">
                        <option value="">select subcategory</option>
                        @foreach($subcategories as $subcat)
                            <option value="{{ $subcat->prosubcat_id }}">{{ $subcat->category }}</option>
                        @endforeach
                    </select>
                    @error('sub_category_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <div class="row">
                        <div class="col-6">
                            <input type="number" 
                                wire:model="product_quantity"    
                                name="product_quantity"
                                class="form-control @error('product_quantity') is-invalid @enderror" 
                                value="{{ old('product_quantity') }}">
                            @error('product_quantity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-6">
                            <select 
                                wire:model="unit_id"
                                class="form-control @error('unit_id') is-invalid @enderror" name="unit_id"
                                id="unit_id">
                                <option value="0">select units</option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->unit_id }}" {{ old('unit_id') == $unit->unit_id  ? 'selected' : '' }}>{{ $unit->name }}</option>
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
                        <input 
                            wire:model="number"
                            type="number" 
                            name="purchase_price"
                            class="form-control @error('purchase_price') is-invalid @enderror" value="{{ old('purchase_price') }}">
                    </div>
                    @error('purchase_price')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="city">Destination</label>
                    <input 
                        type="text" 
                        wire:model="city"
                        id="city" 
                        name="city" 
                        class="form-control @error('city') is-invalid @enderror"
                        placeholder="enter city name" 
                        value="{{ old('city') }}">
                    @error('city')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="details" class="col-form-label">Message:</label>
                    <textarea wire:model="details" class="form-control" id="details" name="details">{{ old('details') }}</textarea>
                </div>

                <div class="form-check">
                    <input 
                        wire:model="isChecked" 
                        id="isChecked" 
                        class="form-check-input" 
                        type="checkbox" 
                        name="isChecked">
                    <label for="isChecked" class="form-check-label">Accept Terms & Condition</label>
                    @error('isChecked')
                        <small class="text-danger">{{ $message }}</small>
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
