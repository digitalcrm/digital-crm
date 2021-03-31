@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1><i class="far fa-edit"></i>New Lead</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <style id="compiled-css" type="text/css">
        .my-group {
            width: 100%;
        }

        .my-group #salutation {
            width: 15%;
        }

        .my-group #first_name {
            width: 85%;
        }
    </style>
    <!-- Main content -->
    <section class="content mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <div class="col-lg-6">
                    @if(session('success'))
                    <div class='alert alert-success'>
                        {{session('success')}}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class='alert alert-danger'>
                        {{session('error')}}
                    </div>
                    @endif
                    <!-- general form elements -->
                    <div class="card shadow card-primary card-outline">
                        <form action="{{url('leads')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="first_name">First Name</label>
                                    <div class="col-md-9">
                                        <div class="input-group-prepend">
                                            <select id="salutation" name="salutation" class="selectpicker form-control ">
                                                {!!$data['salutationoptions']!!}
                                            </select>
                                            <input type="text" class="form-control required" name="first_name" id="first_name" placeholder="" value="{{old('first_name')}}" required tabindex="1">
                                        </div>

                                        <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="last_name">Last Name</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control required" name="last_name" id="last_name" placeholder="" value="{{old('last_name')}}" required tabindex="2">
                                        <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="usermail">Email</label>
                                    <div class="col-md-9">
                                        <input type="email" class="form-control required" name="email" id="email" placeholder="" value="{{old('email')}}" required tabindex="3">
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="product">Product</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="product" id="product">
                                            {!!$data['productoptions']!!}
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="mobile">Mobile</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="mobile" id="mobile" placeholder="" value="{{old('mobile')}}">
                                        <span class="text-danger">{{ $errors->first('mobile') }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="phone">Phone</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="phone" id="phone" placeholder="" value="{{old('phone')}}">
                                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="notes">Notes</label>
                                    <div class="col-md-9">
                                        <textarea name="notes" id="notes" class="form-control" rows="5"></textarea>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-9">
                                        <h3 class="badge badge-info">Address Information</h3>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="street">Address</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="street" id="street" placeholder="" rows="5">{{old('street')}}</textarea>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="country">Country</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="country" id="country">
                                            {!!$data['countryoptions']!!}
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="state">State</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="state" id="state">
                                            <option value="0">Select State</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="city">City</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="city" id="city" placeholder="" value="{{old('city')}}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="zip">Zip</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="zip" id="zip" placeholder="" value="{{old('zip')}}">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="account">Account</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="account" id="account">
                                            {!!$data['accountoptions']!!}
                                        </select>
                                        <!--<br>-->
                                        <input placeholder="Enter new account" class="form-control" type="text" name="addAccount" id="addAccount" style="display: none;margin-top: 5px;" />
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="industrytype">Industry Type</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="industrytype" id="industrytype">
                                            {!!$data['industryoptions']!!}
                                        </select>
                                    </div>
                                </div>



                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="leadsource">Lead Source</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="leadsource" id="leadsource">
                                            {!!$data['leadsourceoptions']!!}
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="company">Company</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="company" id="company" placeholder="Company" value="{{old('company')}}">
                                        <span class="text-danger">{{ $errors->first('company') }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="userpicture">Profile Picture</label>
                                    <div class="col-md-9">
                                        <input type="file" class="btn btn-default" name="userpicture" id="userpicture" />
                                        <span class="text-danger">{{ $errors->first('userpicture') }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="designation">Designation</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="designation" id="designation" placeholder="" value="{{old('designation')}}">
                                        <span class="text-danger">{{ $errors->first('designation') }}</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="leadstatus">Lead Status</label>
                                    <div class="col-md-9">
                                        <select class="form-control" name="leadstatus" id="leadstatus">
                                            {!!$data['leadstatusoptions']!!}
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-md-3 col-form-label text-right" for="website">Website</label>
                                    <div class="col-md-9">
                                        <input type="url" class="form-control" name="website" id="website" placeholder="" value="{{old('website')}}">
                                        <span class="text-danger">{{ $errors->first('website') }}</span>
                                    </div>
                                </div>



                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer bg-white pull-right text-right">
                                <a href="{{url('/leads')}}" class="btn btn-outline-secondary mr-1">Cancel</a>
                                {{Form::submit('Create',['class'=>"btn btn-primary"])}}
                            </div>
                            <!-- </form> -->
                            {{Form::close()}}
                    </div>
                    <!-- /.card -->
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    var url = "{{url('ajaxwebtolead/getStateoptions')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#lileads").addClass("active");

        $("#account").change(function() {
            var acc = $(this).val();
            if (acc == "NewAccount") {
                $("#addAccount").show();
            } else {
                $("#addAccount").hide();
            }
        });

        $("#country").change(function() {
            var country = $(this).val();
            // alert(country);
            if (country > 0) {
                $.get(url, {
                    'country': country
                }, function(result, status) {
                    // alert(result);
                    $("#state").html(result);
                });
            }
        });

    });
</script>
@endsection