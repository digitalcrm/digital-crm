@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1><i class="far fa-edit"></i> Edit Contact</h1>
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
                        <!-- /.card-header -->
                        <!-- form start -->
                        <!-- <form role="form" > -->
                        {{Form::open(['action'=>['ContactController@update',$data['contact']['cnt_id']],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="card-body">
						
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="first_name">First Name</label>
                                        <div class="col-md-9">
										<div class="input-group-prepend">
                                            <select id="salutation" name="salutation" class="selectpicker form-control">
                                                {!!$data['salutationoptions']!!}
                                            </select>
                                            <input type="text" class="form-control required" name="first_name" id="first_name" placeholder="" value="{{$data['contact']['first_name']}}" required>
                                        </div>
                                        <span class="text-danger">{{ $errors->first('first_name') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="mobile">Mobile</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control" name="mobile" id="mobile" placeholder="987...." value="{{$data['contact']['mobile']}}">
                                        <span class="text-danger">{{ $errors->first('mobile') }}</span>
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
                                        <label class="col-md-3 col-form-label text-right" for="notes">Notes</label>
                                        <div class="col-md-9">
										<textarea name="notes" id="notes" class="form-control" rows="5">{{$data['contact']['notes']}}</textarea>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="last_name">Last Name</label>
                                        <!-- &nbsp;<i class="fa fa-asterisk text-danger"></i> -->
                                        <div class="col-md-9">
										<input type="text" class="form-control" name="last_name" id="last_name" placeholder="Enter Last Name" value="{{$data['contact']['last_name']}}" >
                                        <span class="text-danger">{{ $errors->first('last_name') }}</span>
										</div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="phone">Phone</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control" name="phone" id="phone" placeholder="" value="{{$data['contact']['phone']}}">
                                        <span class="text-danger">{{ $errors->first('phone') }}</span>
										</div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <label class="col-md-3 col-form-label text-right" for="account">Account</label>
                                        <div class="col-md-9">
										<select class="form-control" name="account" id="account">
                                            {!!$data['accountoptions']!!}
                                        </select>
                                        <br>
                                        <input class="form-control" type="text" name="addAccount" id="addAccount" style="display: none;" />
										</div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-md-3" for="account"></label>
                                        <div class="col-md-9">
										<?php
                                    if ($data['contact']['ld_id'] == 0) {
                                        echo '<div class = "checkbox">';
                                        echo '<label>';
                                        echo '<div class="custom-control custom-checkbox">
								<input type="checkbox" class="custom-control-input" name="addtoLead" id="customCheck1">
								<label class="custom-control-label" for="customCheck1">Add to Lead</label>
								</div>';
                                        echo '</label>';
                                        echo '</div>';
                                    }
                                    ?>
										</div>
                                    </div>

									
									
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="usermail">Email</label>
                                        <div class="col-md-9">
										<input type="email" class="form-control required" name="email" id="email" placeholder="Enter Email Id" value="{{$data['contact']['email']}}" required>
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
										</div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="designation">Designation</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control" name="designation" id="designation" placeholder="" value="{{$data['contact']['designation']}}">
                                        <span class="text-danger">{{ $errors->first('designation') }}</span>
										</div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="userpicture">Profile Picture</label>
                                        <div class="col-md-9">
										<input type="file" class="btn btn-default" name="userpicture" id="userpicture" />
                                        <small>upload only .png, .jpg, .gif</small>
                                        <span class="text-danger">{{ $errors->first('userpicture') }}</span>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="website">Website</label>
                                        <div class="col-md-9">
										<input type="url" class="form-control" name="website" id="website" placeholder="Website" value="{{$data['contact']['website']}}">
                                        <small>please add http://</small>
                                        <span class="text-danger">{{ $errors->first('website') }}</span>
										</div>
                                    </div>
									
									<div class="row">
									<div class="col-md-3"></div>
									<div class="col-md-9">
                                    <h3 class="badge badge-info">Social Networks</h3>
									</div>
									</div>
							
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="google_id">Google</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control" name="google_id" id="google_id" placeholder="" value="{{$data['contact']['google_id']}}">
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="facebook_id">Facebook</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control" name="facebook_id" id="facebook_id" placeholder="" value="{{$data['contact']['facebook_id']}}">
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="linkedin_id">LinkedIn</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control" name="linkedin_id" id="linkedin_id" placeholder="" value="{{$data['contact']['linkedin_id']}}">
										</div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="twitter_id">Twitter</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control" name="twitter_id" id="twitter_id" placeholder="" value="{{$data['contact']['twitter_id']}}">
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
										<input type="text" class="form-control" name="street" id="street" placeholder="Street" value="{{$data['contact']['street']}}">
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="state">State</label>
                                        <div class="col-md-9">
										<select class="form-control" name="state" id="state">
                                            {!!$data['stateoptions']!!}
                                        </select>
										</div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="city">City</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control" name="city" id="city" placeholder="City" value="{{$data['contact']['city']}}">
										</div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="zip">Zip</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control" name="zip" id="zip" placeholder="Zip" value="{{$data['contact']['zip']}}">
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
									

                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer border-top bg-white pull-right text-right">
                            <a href="{{url('contacts')}}" class="btn btn-outline-secondary mr-1">Cancel</a>
                            {{Form::hidden('_method','PUT')}}
                            {{Form::submit('Save',['class'=>"btn btn-primary"])}}
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
            var country = $(this).val(); // alert(country);
            if (country > 0) {
                $.get(url, {
                    'country': country
                }, function(result, status) { // alert(result);
                    $("#state").html(result);
                });
            }
        });

    });
</script>
@endsection