@extends('layouts.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
<!--    <section class="content-header">
        <h1>
            Create Lead
             <small>Control panel</small> 
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Create Lead</li>
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
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Create Lead
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <!-- <form role="form" > -->
                    <!-- {{Form::open(['action'=>'LeadController@store','method'=>'Post'])}} -->
                    <form action="{{url('leads')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <!-- Left col -->
                            <section class="col-lg-6">
                                <div class="form-group">
                                    <label for="first_name">First Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <input type="text" class="form-control" name="first_name" id="first_name" placeholder="" value="{{old('first_name')}}" required>
                                    <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="usermail">Email</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <input type="email" class="form-control" name="usermail" id="usermail" placeholder="" value="{{old('usermail')}}" required>
                                    <span class="text-danger">{{ $errors->first('usermail') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="mobile">Mobile</label>
                                    <input type="text" class="form-control" name="mobile" id="mobile" placeholder="" value="{{old('mobile')}}" pattern="^\d{10}$">
                                    <span class="text-danger">{{ $errors->first('mobile') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="leadsource">Lead Source</label>
                                    <select class="form-control" name="leadsource" id="leadsource">
                                        {!!$data['leadsourceoptions']!!}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="industrytype">Industry Type</label>
                                    <select class="form-control" name="industrytype" id="industrytype">
                                        {!!$data['industryoptions']!!}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control" rows="5"></textarea>
                                </div>
                            </section>
                            <!-- /.Left col -->
                            <section class="col-lg-6">
                                <div class="form-group">
                                    <label for="last_name">Last Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <input type="text" class="form-control" name="last_name" id="last_name" placeholder="" value="{{old('last_name')}}" required>
                                    <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                </div>                                 
                                <div class="form-group">
                                    <label for="userpicture">Profile Picture</label>
                                    <input type="file" class="btn btn-default" name="userpicture" id="userpicture" />
                                    <span class="text-danger">{{ $errors->first('userpicture') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder="" pattern="^\d{15}$" value="{{old('phone')}}">
                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="leadstatus">Lead Status</label>
                                    <select class="form-control" name="leadstatus" id="leadstatus">
                                        {!!$data['leadstatusoptions']!!}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="account">Account</label>
                                    <select class="form-control" name="account" id="account">
                                        {!!$data['accountoptions']!!}
                                    </select>
                                    <br>
                                    <input class="form-control" type="text" name="addAccount" id="addAccount" style="display: none;" />
                                </div>
                                <div class="form-group">
                                    <label for="website">Website</label>
                                    <input type="url" class="form-control" name="website" id="website" placeholder="" value="{{old('website')}}">
                                    <span class="text-danger">{{ $errors->first('website') }}</span>
                                </div>
                            </section>

                            <div class="form-group col-lg-12">
                                <h4 class="box-title text-primary">Address Information</h4>
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
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                            <a href="{{url('/leads')}}" class="btn btn-primary pull-left">Back</a>
                            {{Form::submit('Save',['class'=>"btn btn-primary pull-right"])}}
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
    $(function() {
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        $("#ulleads").addClass('menu-open');
        $("#ulleads ul").css('display', 'block');
        $("#licreatelead").addClass("active");

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