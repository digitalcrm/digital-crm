@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1><i class="far fa-edit"></i> New Contact</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Content Header (Page header) -->
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
                    <div class="card shadow card-primary card-outline">
                        <!-- /.card-header -->
                        {{Form::open(['action'=>'ContactController@store','method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="card-body">

                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <div class="input-group my-group">
                                            <select id="salutation" name="salutation" class="selectpicker form-control">
                                                {!!$data['salutationoptions']!!}
                                            </select>
                                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="" value="{{old('first_name')}}" required tabindex="1">
                                        </div>
                                        <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                    </div>


                                    <div class="form-group">
                                        <label for="mobile">Mobile</label>
                                        <input type="text" class="form-control" name="mobile" id="mobile" placeholder="" value="{{old('mobile')}}">
                                        <span class="text-danger">{{ $errors->first('mobile') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="leadsource">Lead Source</label>
                                        <select class="form-control" name="leadsource" id="leadsource">
                                            {!!$data['leadsourceoptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('leadsource') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="5"></textarea>
                                        <span class="text-danger">{{ $errors->first('notes') }}</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <!-- &nbsp;<i class="fa fa-asterisk text-danger"></i> -->
                                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="" value="{{old('last_name')}}" tabindex="1">
                                        <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" name="phone" id="phone" placeholder="" value="{{old('phone')}}">
                                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="account">Account</label>
                                        <select class="form-control" name="account" id="account">
                                            {!!$data['accountoptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('account') }}</span>
                                        <br>
                                        <input class="form-control" type="text" name="addAccount" id="addAccount" style="display: none;" />
                                        <span class="text-danger">{{ $errors->first('addAccount') }}</span>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="addtoLead" id="customCheck1">
                                        <label class="custom-control-label" for="customCheck1">Add to Lead</label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="email">Email</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                        <input type="email" class="form-control" name="email" id="email" placeholder="" value="{{old('email')}}" required tabindex="3">
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="designation">Designation</label>
                                        <input type="text" class="form-control" name="designation" id="designation" placeholder="" value="{{old('designation')}}">
                                        <span class="text-danger">{{ $errors->first('designation') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="userpicture">Profile Picture</label>
                                        <input type="file" class="btn btn-default" name="userpicture" id="userpicture" />
                                        <span class="text-danger">{{ $errors->first('userpicture') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="website">Website</label>
                                        <input type="url" class="form-control" name="website" id="website" placeholder="" value="{{old('website')}}">
                                        <span class="text-danger">{{ $errors->first('website') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 pl-0">
                                <h3 class="badge badge-info">Social Networks</h3>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="google_id">Google</label>
                                        <input type="text" class="form-control" name="google_id" id="google_id" placeholder="" value="{{old('google_id')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="facebook_id">Facebook</label>
                                        <input type="text" class="form-control" name="facebook_id" id="facebook_id" placeholder="" value="{{old('facebook_id')}}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="linkedin_id">LinkedIn</label>
                                        <input type="text" class="form-control" name="linkedin_id" id="linkedin_id" placeholder="" value="{{old('linkedin_id')}}">
                                    </div>

                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="twitter_id">Twitter</label>
                                        <input type="text" class="form-control" name="twitter_id" id="twitter_id" placeholder="" value="{{old('twitter_id')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="col-lg-12 pl-0">
                                    <h3 class="badge badge-info">Address Information</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control" name="city" id="city" placeholder="" value="{{old('city')}}">
                                    </div>

                                    <div class="form-group">
                                        <label for="zip">Zip</label>
                                        <input type="text" class="form-control" name="zip" id="zip" placeholder="" value="{{old('zip')}}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <select class="form-control" name="country" id="country">
                                            {!!$data['countryoptions']!!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <select class="form-control" name="state" id="state">
                                            <option value="0">Select State</option>
                                        </select>
                                    </div>
                                </div>
                            </div>



                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top pull-right text-right">
                            <a href="{{url('contacts')}}" class="btn btn-outline-secondary mr-1">Cancel</a>
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
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        //        $("#ulleads").addClass('menu-open');
        //        $("#ulleads ul").css('display', 'block');
        $("#licontacts").addClass("active");

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