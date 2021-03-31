@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">


    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small cardes (Stat card) -->
        <div class="row">
            <div class="col-lg-12">
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
                <div class="card card-primary">
                    <div class="card-header with-border">
                        <h3 class="card-title">
                            Create Account
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <!-- <form role="form" > -->
                    {{Form::open(['action'=>'AccountController@store','method'=>'Post','enctype'=>'multipart/form-data'])}} 
                    @csrf
                    <div class="card-body">
                        <!-- Left col -->
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="name">Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="text" class="form-control" name="name" id="accountname" placeholder="" value="{{old('name')}}" required>
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>

                            <div class="form-group">
                                <label for="mobile">Mobile</label>
                                <input type="text" class="form-control" name="mobile" id="mobile" placeholder="" value="{{old('mobile')}}" >
                                <span class="text-danger">{{ $errors->first('mobile') }}</span>
                            </div>

                            <div class="form-group">
                                <label for="industrytype">Industry Type</label>
                                <select class="form-control" name="industrytype" id="industrytype">
                                    {!!$data['industryoptions']!!}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="employees">Employees</label>
                                <input type="text" class="form-control" name="employees" id="employees" placeholder="" value="{{old('employees')}}" >
                                <span class="text-danger">{{ $errors->first('employees') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="description">Notes</label>
                                <textarea name="description" id="description" class="form-control" rows="5"></textarea>
                            </div>

                        </section>
                        <!-- /.Left col -->
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="email">Email</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="email" class="form-control" name="email" id="email" placeholder="" value="{{old('email')}}" required>
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" name="phone" id="phone" placeholder=""  value="{{old('phone')}}">
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            </div>

                            <div class="form-group">
                                <label for="accounttype">Account Type</label>
                                <select class="form-control" name="accounttype" id="accounttype">
                                    {!!$data['accounttypeoptions']!!}
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="website">Website</label>
                                <input type="url" class="form-control" name="website" id="website" placeholder="" value="{{old('website')}}">
                                <span class="text-danger">{{ $errors->first('website') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="userpicture">Profile Picture</label>
                                <input type="file" class="btn btn-default" name="userpicture" id="userpicture" />
                                <span class="text-danger">{{ $errors->first('userpicture') }}</span>
                            </div>

                        </section>

                        <div class="form-group col-lg-12">
                            <div class="card-header with-border">
                                <h3 class="card-title">Address Information</h3>
                            </div>
                        </div>
                        <!-- right col -->
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="country">Country</label>
                                <select class="form-control" name="country" id="country">
                                    {!!$data['countryoptions']!!}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" class="form-control" name="city" id="city" placeholder="" value="{{old('city')}}">
                            </div>
                            <div class="form-group">
                                <label for="zip">Zip</label>
                                <input type="text" class="form-control" name="zip" id="zip" placeholder="" value="{{old('zip')}}">
                            </div>
                        </section>
                        <!-- Left col -->
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="state">State</label>
                                <select class="form-control" name="state" id="state">
                                    <option value="0">Select State</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="street">Street</label>
                                <input type="text" class="form-control" name="street" id="street" placeholder="" value="{{old('street')}}">
                            </div>
                        </section>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top">
                        <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                        {{Form::submit('Save',['class'=>"btn btn-primary float-right"])}}
                        <a href="{{url('/accounts')}}" class="btn btn-primary">Back</a>
                    </div>
                    <!-- </form> -->
                    {{Form::close()}}
                </div>
                <!-- /.card -->
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-7 connectedSortable">


            </section>
            <!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-5 connectedSortable">


            </section>
            <!-- right col -->
        </div>
        <!-- /.row (main row) -->
	</div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    var url = "{{url('ajaxwebtolead/getStateoptions')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#liaccounts").addClass("active");

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
                $.get(url, {'country': country}, function(result, status) {
                    // alert(result);
                    $("#state").html(result);
                });
            }
        });

    });
</script>
@endsection