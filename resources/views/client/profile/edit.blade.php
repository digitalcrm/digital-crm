@extends('layouts.adminlte-boot-4.client')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Profile </h1>
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
                    <div class='alert alert-success'>
                        {{session('error')}}
                    </div>
                    @endif
                    <!-- general form elements -->
                    <div class="card shadow card-primary card-outline">
                        <form action="{{url('clients/update/'.$data['userdata']['id'])}}" enctype="multipart/form-data" method="POST">
                            @csrf
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="username" class="control-label">Full Name</label>
                                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" value="{!!$data['userdata']['name']!!}">
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        </div>

                                        <div class="form-group">
                                            <label for="userpicture" class="control-label">Profile Picture</label>
                                            <img src="<?php echo ($data['userdata']['picture'] != null) ? url($data['userdata']['picture']) : url('/uploads/default/user.png'); ?>" alt="Snow" width="36" height="36">
                                            <input type="file" class="btn btn-default" id="userpicture" name="userpicture" accept="image/*">
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="email" class="control-label">Email</label>
                                            <input type="email" class="form-control" name="email" id="email" placeholder="Email" value="{!!$data['userdata']['email']!!}" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="currency" class="control-label">Currency</label>
                                            <select class="form-control" name="currency" id="currency" required>
                                                {!!$data['croptions']!!}
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="mobile" class="control-label">Mobile</label>
                                            <input type="text" class="form-control" name="mobile" id="mobile" placeholder="mobile" value="{!!$data['userdata']['mobile']!!}">
                                            <span class="text-danger">{{ $errors->first('Mobile') }}</span>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="card-header no-border">
                                        <h3 class="card-title">Address Information</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="address" class="control-label">Address</label>
                                            <input type="text" class="form-control" name="address" id="address" placeholder="Address" value="{!!$data['userdata']['address']!!}">
                                            <span class="text-danger">{{ $errors->first('address') }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="city" class="control-label">City</label>
                                            <input type="text" class="form-control" name="city" id="city" placeholder="City" value="{!!$data['userdata']['city']!!}">
                                            <span class="text-danger">{{ $errors->first('city') }}</span>
                                        </div>


                                    </div>
                                    <div class="col-4">

                                        <div class="form-group">
                                            <label for="country" class="control-label">Country</label>
                                            <select class="form-control" name="country" id="country">
                                                <?php echo $data['countryoptions']; ?>
                                            </select>
                                            <span class="text-danger">{{ $errors->first('country') }}</span>
                                        </div>

                                        <div class="form-group">
                                            <label for="zip" class="control-label">Zip Code</label>
                                            <input type="text" class="form-control" name="zip" id="zip" placeholder="Zip Code" value="{!!$data['userdata']['zip']!!}">
                                            <span class="text-danger">{{ $errors->first('zip') }}</span>

                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="state" class="control-label">State</label>
                                            <select class="form-control" name="state" id="state">
                                                <?php echo $data['stateoptions']; ?>
                                            </select>
                                            <span class="text-danger">{{ $errors->first('state') }}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer bg-white pull-right text-right">
                                <a href="{{url('/clients/show/'.$data['userdata']['id'])}}" class="btn btn-outline-secondary mr-1">Back</a>
                                {{Form::hidden('_method','PUT')}}
                                {{Form::submit('Save',['class'=>"btn btn-primary pull-right"])}}
                            </div>
                            <!-- /.card-footer -->
                    </div>
                </div>

                </form>
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
    var stateurl = "{{url('clients/ajax/getstateoptions')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");


        $("#country").change(function() {
            var country = $(this).val();
            // alert(country);
            if (country > 0) {
                $.get(stateurl, {
                    'country': country
                }, function(result, status) {
                    // alert(result);
                    $("#state").html(result);
                });
            } else {
                $("#state").val(0);
                return false;
            }
        });


    });
</script>
@endsection