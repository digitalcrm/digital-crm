@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1><i class="far fa-edit"></i> <?php echo $data['accountarr']['name']; ?></h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>


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
                        {{Form::open(['action'=>['AccountController@update',$data['accountarr']['acc_id']],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="card-body">


                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="first_name">Account Name</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control required" name="name" id="name" placeholder="" value="{{$data['accountarr']['name']}}" required>
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="mobile">Mobile</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="mobile" id="mobile" placeholder="" value="{{$data['accountarr']['mobile']}}">
                                    <span class="text-danger">{{ $errors->first('mobile') }}</span>
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
                                <label class="col-md-3 col-form-label text-right" for="notes">Notes</label>
                                <div class="col-md-9">
                                    <textarea name="notes" id="notes" class="form-control" rows="5">
                                    {{$data['accountarr']['description']}}
                                    </textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="email">Email</label>
                                <!---<i class="fa fa-asterisk text-danger"></i>-->
                                <div class="col-md-9">
                                    <input type="email" class="form-control" name="email" id="usermail" placeholder="" value="{{$data['accountarr']['email']}}">
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="phone">Phone</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder="" value="{{$data['accountarr']['phone']}}">
                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="accounttype">Account Type</label>
                                <div class="col-md-9">
                                    <select class="form-control" name="accounttype" id="accounttype">
                                        {!!$data['accounttypeoptions']!!}
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="company">Company</label>
                                <div class="col-md-9">
                                    <!-- <input type="text" class="form-control" name="company" id="company" placeholder="" value="{{$data['accountarr']['company']}}"> -->
                                    <select class="form-control" name="company[]" id="company" multiple>
                                        {!!$data['companyoption']!!}
                                    </select>
                                    <span class="text-danger">{{ $errors->first('company') }}</span>
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
                                <label class="col-md-3 col-form-label text-right" for="employees">Employees</label>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" name="employees" id="employees" placeholder="" value="{{$data['accountarr']['employees']}}">
                                    <span class="text-danger">{{ $errors->first('employees') }}</span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="website">Website</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="website" id="website" placeholder="" value="{{$data['accountarr']['website']}}">
                                    <small>please add http://</small>
                                    <span class="text-danger">{{ $errors->first('website') }}</span>
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
                                    <input type="text" class="form-control" name="street" id="street" placeholder="" value="{{$data['accountarr']['street']}}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="city">City</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="city" id="city" placeholder="" value="{{$data['accountarr']['city']}}">
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
                                    <input type="text" class="form-control" name="zip" id="zip" placeholder="" value="{{$data['accountarr']['zip']}}">
                                </div>
                            </div>



                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white text-right pull-right">
                            <a href="{{url('/accounts')}}" class="btn btn-outline-secondary mr-1">Cancel</a>
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