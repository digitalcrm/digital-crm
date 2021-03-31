@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1><i class="far fa-edit"></i> New Widget</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

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

                    {{Form::open(['action'=>'WidgetsController@store','method'=>'Post','enctype'=>'multipart/form-data'])}} 
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="name">Widget Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <input type="text" class="form-control" name="name" id="accountname" placeholder="" value="{{old('name')}}" required>
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="mobile">Url</label>
                                    <input type="text" class="form-control" name="mobile" id="mobile" placeholder="" value="{{old('mobile')}}" >
                                    <span class="text-danger">{{ $errors->first('mobile') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="description">Notes</label>
                                    <textarea name="description" id="description" class="form-control" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="email">Email</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="" value="{{old('email')}}" required>
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder="" value="{{old('phone')}}">
                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="company">Company</label>
                                    <input type="text" class="form-control" name="company" id="company" placeholder="" value="{{old('company')}}">
                                    <span class="text-danger">{{ $errors->first('company') }}</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="userpicture">Profile Picture</label>
                                    <input type="file" class="btn btn-default" name="userpicture" id="userpicture" />
                                    <span class="text-danger">{{ $errors->first('userpicture') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="employees">Employees</label>
                                    <input type="number" class="form-control" name="employees" id="employees" placeholder="" value="{{old('employees')}}" >
                                    <span class="text-danger">{{ $errors->first('employees') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="website">Website</label>
                                    <input type="url" class="form-control" name="website" id="website" placeholder="" value="{{old('website')}}">
                                    <span class="text-danger">{{ $errors->first('website') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white pull-right text-right">
                        <a href="{{url('/widgets')}}" class="btn btn-outline-secondary mr-1">Cancel</a>
                        {{Form::submit('Create',['class'=>"btn btn-primary"])}}
                    </div>
                </div>
                <!-- /.card-body -->


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
        $("#licreateaccount").addClass("active");

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