@extends('layouts.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
<!--    <section class="content-header">
        <h1>
            Edit Lead
             <small>Control panel</small> 
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Edit Lead</li>
        </ol>
    </section>-->

    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small boxes (Stat box) -->
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
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <!-- form start -->
                    <!-- <form role="form" > -->
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <img src="<?php echo ($data['accountarr']['picture'] != "") ? url($data['accountarr']['picture']) : ''; ?>" height="30" width="30" />&nbsp;
                            <?php echo $data['accountarr']['name']; ?>
                        </h3>
                    </div>
                    {{Form::open(['action'=>['AccountController@update',$data['accountarr']['acc_id']],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                    @csrf
                    <div class="box-body">
                        <!-- Left col -->
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="first_name">Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="text" class="form-control" name="name" id="name" placeholder="" value="{{$data['accountarr']['name']}}" required>
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>

                            <div class="form-group">
                                <label for="mobile">Mobile</label>
                                <input type="text" class="form-control" name="mobile" id="mobile" placeholder="" value="{{$data['accountarr']['mobile']}}" pattern="^\d{10}$">
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
                                <input type="text" class="form-control" name="employees" id="employees" placeholder="" value="{{$data['accountarr']['employees']}}" >
                                <span class="text-danger">{{ $errors->first('employees') }}</span>
                            </div>

                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea name="notes" id="notes" class="form-control" rows="5">
                                    {{$data['accountarr']['description']}}
                                </textarea>
                            </div>
                        </section>
                        <!-- /.Left col -->
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="email">Email</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="email" class="form-control" name="email" id="usermail" placeholder="" value="{{$data['accountarr']['email']}}" required>
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            </div>     
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" name="phone" id="phone" placeholder="" pattern="^\d{15}$" value="{{$data['accountarr']['phone']}}">
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="accounttype">Account Type</label>
                                <select class="form-control" name="accounttype" id="accounttype">
                                    {!!$data['accounttypeoptions']!!}
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="userpicture">Profile Picture</label>
                                <input type="file" class="btn btn-default" name="userpicture" id="userpicture" />
                                <small>upload only .png, .jpg, .gif</small>
                                <span class="text-danger">{{ $errors->first('userpicture') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="website">Website</label>
                                <input type="url" class="form-control" name="website" id="website" placeholder="" value="{{$data['accountarr']['website']}}">
                                <small>please add http://</small>
                                <span class="text-danger">{{ $errors->first('website') }}</span>
                            </div>
                        </section>

                        <div class="form-group col-lg-12">
                            <div class="box-header with-border">
                                <h3 class="box-title">Address Information</h3>
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
                                <input type="text" class="form-control" name="city" id="city" placeholder="" value="{{$data['accountarr']['city']}}">
                            </div>
                            <div class="form-group">
                                <label for="zip">Zip</label>
                                <input type="text" class="form-control" name="zip" id="zip" placeholder="" value="{{$data['accountarr']['zip']}}">
                            </div>
                        </section>
                        <!-- Left col -->
                        <section class="col-lg-6">
                            <div class="form-group">
                                <label for="state">State</label>
                                <select class="form-control" name="state" id="state">
                                    {!!$data['stateoptions']!!}
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="street">Street</label>
                                <input type="text" class="form-control" name="street" id="street" placeholder="" value="{{$data['accountarr']['street']}}">
                            </div>
                        </section>

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        {{Form::hidden('_method','PUT')}}
                        {{Form::submit('Save',['class'=>"btn btn-primary pull-right"])}}
                        <a href="{{url('/accounts')}}" class="btn btn-primary">Back</a>
                    </div>
                    <!-- </form> -->
                    {{Form::close()}}
                </div>
                <!-- /.box -->
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
    $(function () {
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        $("#ulleads").addClass('menu-open');
        $("#ulleads ul").css('display', 'block');
        $("#licreatelead").addClass("active");

        $("#account").change(function () {
            var acc = $(this).val();
            if (acc == "NewAccount") {
                $("#addAccount").show();
            } else {
                $("#addAccount").hide();
            }
        });

        $("#country").change(function () {
            var country = $(this).val();
            // alert(country);
            if (country > 0) {
                $.get(url, {'country': country}, function (result, status) {
                    // alert(result);
                    $("#state").html(result);
                });
            }
        });

    });
</script>
@endsection