@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1><i class="far fa-edit"></i> Edit Lead</h1>
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

                        <!--<div class="card-header with-border">
                            <h3 class="card-title">
                                <img src="<?php echo ($data['leadarr']['picture'] != "") ? url($data['leadarr']['picture']) : url('/uploads/default/leads.png'); ?>" height="30" width="30" />&nbsp;
                                <?php echo $data['leadarr']['first_name'] . ' ' . $data['leadarr']['last_name']; ?>
                            </h3>
                        </div>-->
                        {{Form::open(['action'=>['Admin\LeadController@updateProductLead',$data['leadarr']['ld_id']],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="first_name">First Name</label>
                                <div class="col-md-9">
                                    <div class="input-group-prepend">
                                        <select id="salutation" name="salutation" class="selectpicker form-control">
                                            {!!$data['salutationoptions']!!}
                                        </select>
                                        <input type="text" class="form-control required" name="first_name" id="first_name" placeholder="" value="{{$data['leadarr']['first_name']}}" required>
                                    </div>
                                    <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="last_name">Last Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="last_name" id="last_name" placeholder="" value="{{$data['leadarr']['last_name']}}">
                                    <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="usermail">Email</label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control" name="email" id="email" placeholder="" value="{{$data['leadarr']['email']}}">
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="product">Product</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="product" id="product" required>
                                        {!!$data['productoptions']!!}
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="mobile">Mobile</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="mobile" id="mobile" placeholder="" value="{{$data['leadarr']['mobile']}}" required>
                                    <span class="text-danger">{{ $errors->first('mobile') }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="phone">Phone</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder="" value="{{$data['leadarr']['phone']}}">
                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="notes">Notes</label>
                                <div class="col-md-9">
                                    <textarea name="notes" id="notes" class="form-control" rows="5">{{$data['leadarr']['notes']}}</textarea>
                                </div>
                            </div>

                            <!-- Left col -->
                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-9">
                                    <h3 class="badge badge-info">Address Information</h3>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="street">Address</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="street" id="street" placeholder="" value="{{$data['leadarr']['street']}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="city">City</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="city" id="city" placeholder="" value="{{$data['leadarr']['city']}}">
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
                                        {!!$data['stateoptions']!!}
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="zip">Zip</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="zip" id="zip" placeholder="" value="{{$data['leadarr']['zip']}}">
                                </div>
                            </div>
                            <br />
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="account">Account</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="account" id="account">
                                        {!!$data['accountoptions']!!}
                                    </select>
                                    <!--<br>-->
                                    <input class="form-control" type="text" name="addAccount" id="addAccount" style="display: none;margin-top: 5px; " />
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
                                <label class="col-md-3 col-form-label text-right" for="company">Company</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="company" id="company" placeholder="" value="{{$data['leadarr']['company']}}">
                                    <span class="text-danger">{{ $errors->first('company') }}</span>
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
                                <label class="col-md-3 col-form-label text-right" for="designation">Designation</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="designation" id="designation" placeholder="" value="{{$data['leadarr']['designation']}}">
                                    <span class="text-danger">{{ $errors->first('designation') }}</span>
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
                                <label class="col-md-3 col-form-label text-right" for="leadsource">Lead Source</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="leadsource" id="leadsource">
                                        {!!$data['leadsourceoptions']!!}
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="website">Website</label>
                                <div class="col-md-9">
                                    <input type="url" class="form-control" name="website" id="website" placeholder="" value="{{$data['leadarr']['website']}}">
                                    <span class="text-danger">{{ $errors->first('website') }}</span>
                                </div>
                            </div>


                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white pull-right text-right">
                            <a href="{{url('admin/leads/getproductleads/list')}}" class="btn btn-outline-secondary mr-1">Cancel</a>
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

        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    var stateurl = "{{url('admin/ajax/getStateoptions')}}";
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
             //alert(country);
			 //alert(stateurl);
            if (country > 0) {
                $.get(stateurl, {
                    'country': country
                }, function(result, status) {
                     //alert(result);
                    $("#state").html(result);
                });
            }
        });

    });
</script>
@endsection