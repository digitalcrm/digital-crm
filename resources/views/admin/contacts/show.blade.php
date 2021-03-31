@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Contacts Show</h1>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
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
                        <div class="card-header">
                            <h3 class="card-title">
                                <img src="<?php echo ($data['contact']['picture'] != "") ? url($data['contact']['picture']) : url('/uploads/default/contacts.png'); ?>" height="30" width="30" />&nbsp;
                                <?php echo $data['contact']['first_name'] . ' ' . $data['contact']['last_name']; ?>
                            </h3>
                            <!--<a href="{!!$data['editLink']!!}" class="btn btn-primary float-right">Edit</a>-->
                        </div>
                        <div class="card-body">
                            <section class="row">
                                <div class="col-lg-4">

                                    <div class="form-group">
                                        <label for="title">First Name</label>
                                        <p>{!!$data['contact']['first_name']!!}</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="redirecturl">Mobile</label>
                                        <p><?php echo ($data['contact']['mobile'] != null) ? $data['contact']['mobile'] : ''; ?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="frommail">Lead Source</label>
                                        <p><?php echo ($data['contact']['tbl_leadsource'] != null) ? $data['contact']['tbl_leadsource']['leadsource'] : ''; ?></p>
                                    </div>

                                    <div class="form-group">
                                        <label for="title">Designation</label>
                                        <p>{!!$data['contact']['designation']!!}</p>
                                    </div>

                                </div>
                                {{-- </section> --}}
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="title">Last Name</label>
                                        <p><?php echo ($data['contact']['last_name'] != null) ? $data['contact']['last_name'] : ''; ?></p>
                                    </div>

                                    <div class="form-group">
                                        <label for="frommail">Phone</label>
                                        <p>{!!$data['contact']['phone']!!}</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="frommail">Account</label>
                                        <p><?php echo ($data['contact']['tbl__accounts'] != null) ? $data['contact']['tbl__accounts']['name'] : ''; ?></p>
                                    </div>

                                    <div class="form-group">
                                        <label for="message">Notes</label>
                                        <p><?php echo ($data['contact']['notes'] != null) ? $data['contact']['notes'] : ''; ?></p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="posturl">Email</label>
                                        <p>{!!$data['contact']['email']!!}</p>
                                    </div>
                                    <div class="form-group">
                                        <!-- <label for="title">Profile Picture</label> -->
                                        <img src="<?php echo ($data['contact']['picture'] != "") ? url($data['contact']['picture']) : url('/uploads/default/contacts.png'); ?>" height="50" width="50" alt="" />
                                    </div>
                                    <div class="form-group">
                                        <label for="frommail">Website</label>
                                        <p><?php echo ($data['contact']['website'] != null) ? $data['contact']['website'] : ''; ?></p>
                                    </div>
                                </div>
                            </section>
                            <div class="col-lg-12 p-0">
                                <h3 class="badge badge-secondary">Address Information</h3>
                            </div>
                            <section class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <p><?php echo ($data['contact']['tbl_countries'] != null) ? $data['contact']['tbl_countries']['name'] : ''; ?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <p><?php echo ($data['contact']['city'] != null) ? $data['contact']['city'] : ''; ?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="zip">Zip</label>
                                        <p><?php echo ($data['contact']['zip'] != null) ? $data['contact']['zip'] : ''; ?></p>
                                    </div>
                                </div>
                                <!-- Left col -->
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <p><?php echo ($data['contact']['tbl_states'] != null) ? $data['contact']['tbl_states']['name'] : ''; ?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="street">Street</label>
                                        <p><?php echo ($data['contact']['street'] != null) ? $data['contact']['street'] : ''; ?></p>
                                    </div>
                                </div>
                            </section>

                        </div>
                        <div class="card-footer bg-white border-top text-right pull-right">
                            <button href="#" class="btn text-danger btn-outline-secondary"><i class="far fa-trash-alt"></i></button>
                            <a href="{{url('admin/contacts')}}" class="btn btn-outline-secondary">Back</a>
                        </div>
                    </div>

                    <!-- /.card -->

                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <section class="col-lg-10">

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
    $(function() {

        $(".sidebar-menu li").removeClass("active");
        $("#licontacts").addClass("active");
    });
</script>
@endsection