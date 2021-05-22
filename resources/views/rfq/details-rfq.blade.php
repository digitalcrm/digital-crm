@extends('layouts.adminlte-boot-4.front-main')

@section('title', 'RFQ Details')

@section('content')

<div class="container">
    <div class="row">
        {{-- Left Portion --}}
        <div class="col-md-7">
            <section class="m-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('lists.rfq') }}">All RFQ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('lists.rfq',['category'=>$details->tbl_category->category]) }}">
                            {{ $details->tbl_category->category }}</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('lists.rfq',['subcategory' =>$details->tbl_sub_category->category]) }}">
                            {{ $details->tbl_sub_category->category }}</a>
                        </li>
                    </ol>
                </nav>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $details->product_name }}</h5>
                        <p class="card-text">
                            <span class="mx-2 small text-muted">Quantity Required:
                                <strong>{{ $details->product_quantity }}</strong></span>
                            <span class="mx-2 small text-muted">Date Posted:
                                {{ $details->created_at->isoFormat('D-MM-Y') }}</span>
                        </p>
                        <p class="card-text">
                            <img src="{{ $details->user->profile_img }}" class="img-fluid rounded-circle"
                                alt="{{ $details->user->name }}" style="width: 2rem" srcset="">
                            {{ $details->user->name ?? '' }}
                        </p>
                    </div>
                    <div class="card-footer">
                        <a href="#" class="btn btn-sm btn-primary float-right mx-1">Quote Now</a>
                    </div>
                </div>
            </section>
            <section class="m-4">
                <div class="card">
                    <div class="card-header">
                        <h3>
                            RFQ Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <h5>Product Basic Information</h5>
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-4 col-form-label">Product Name</label>
                            <div class="col-sm-8 form-control-plaintext">
                                {{ $details->product_name }}
                            </div>
                            <label for="staticEmail" class="col-sm-4 col-form-label">Category</label>
                            <div class="col-sm-8 form-control-plaintext">
                                {{ $details->tbl_category->category }}
                            </div>
                            <label for="staticEmail" class="col-sm-4 col-form-label">Quantity</label>
                            <div class="col-sm-8 form-control-plaintext">
                                {{ $details->product_quantity }}
                            </div>
                            <label for="staticEmail" class="col-sm-4 col-form-label">Details</label>
                            <div class="col-sm-8 form-control-plaintext">
                                {{ $details->details ?? 'None' }}
                            </div>
                            <label for="staticEmail" class="col-sm-4 col-form-label">Documents</label>
                            <div class="col-sm-8 form-control-plaintext">
                                @foreach($details->images as $img)
                                    @if($img->fileType())
                                        <img src="{{ $img->imageUrl() }}" style="width: 3rem;"
                                            alt="{{ $img->file_name }}" srcset="">
                                    @else
                                        {{ $img->file_name }}
                                    @endif                                
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        {{-- Right Portion --}}
        <div class="col-md-5">
            <div class="card mt-4">
                <div class="card-header border-bottom">
                    <h5 class="float-left">
                        Quote Now
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('rfq-leads.store') }}" method="post">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                              <label for="c_name">Contact Name</label>
                              <input type="hidden" name="rfq_id" value="{{ $details->id }}">
                              <input type="text" 
                                    name="contact_name" 
                                    value="{{ old('contact_name') }}"
                                    id="c_name" class="form-control required" placeholder="eg: John Doe" required>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="email">Email</label>
                              <input type="email" name="email" 
                                    value="{{ old('email') }}"
                                    id="email" class="form-control required" placeholder="eg: john@example.com" required>
                            </div>
                            <div class="form-group col-md-6">
                              <label for="m_number">Mobile Number</label>
                              <input type="tel" 
                                    name="mobile_number" 
                                    id="m_number" 
                                    class="form-control required" 
                                    maxlength="10"
                                    mainlength="10"
                                    placeholder="eg: 1234567890" required>
                                @error('mobile_number')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>
                            <div class="form-group col-md-6">
                              <label for="address">Address</label>
                              <input type="text" name="address" id="address" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                              <label for="country_id">Country</label>
                              <select name="country_id" id="country_id" class="form-control">
                                <option value="">Select country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                              </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="city">City</label>
                                <input type="text" name="city" id="city" class="form-control">
                            </div>
                            <div class="form-group col-md-12">
                              <label for="message">Message</label>
                              <textarea name="message" id="message" class="form-control" cols="30" rows="5"></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-block btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
