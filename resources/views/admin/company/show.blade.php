@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Accounts Show</h1>
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
                <div class="col-lg-10">
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

                    <div class="card shadow card-primary card-outline mt-2">
                        <div class="card-header with-border">
                            <h3 class="card-title">
                                <img src="<?php echo ($data['accounts']['picture'] != "") ? url($data['accounts']['picture']) : url('/uploads/default/accounts.png'); ?>" height="50" width="50" />&nbsp;
                                <?php echo $data['accounts']['name']; ?>
                            </h3>
                            <!--<a href="{!!$data['editLink']!!}" class="btn btn-primary float-right">Edit</a>-->
                        </div>
                        <div class="card-body">
                            <section class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="title">Name</label>
                                        <p>{!!$data['accounts']['name']!!}</p>
                                    </div>

                                    <div class="form-group">
                                        <label for="redirecturl">Mobile</label>
                                        <p><?php echo ($data['accounts']['mobile'] != null) ? $data['accounts']['mobile'] : ''; ?></p>
                                    </div>


                                    <div class="form-group">
                                        <label for="frommail">Industry Type</label>
                                        <p><?php echo ($data['accounts']['tbl_industrytypes'] != null) ? $data['accounts']['tbl_industrytypes']['type'] : ''; ?></p>
                                    </div>

                                    <div class="form-group">
                                        <label for="message">Description</label>
                                        <p><?php echo ($data['accounts']['description'] != null) ? $data['accounts']['description'] : ''; ?></p>
                                    </div>

                                </div>
                                {{-- </section> --}}
                                {{-- <section class="col-lg-6"> --}}
                                <div class="col-lg-6">

                                    <div class="form-group">
                                        <label for="posturl">Email</label>
                                        <p>{!!$data['accounts']['email']!!}</p>
                                    </div>

                                    <div class="form-group">
                                        <!-- <label for="title">Profile Picture</label> -->
                                        <img src="<?php echo ($data['accounts']['picture'] != "") ? url($data['accounts']['picture']) : url('/uploads/default/accounts.png'); ?>" height="75" width="75" />
                                    </div>

                                    <div class="form-group">
                                        <label for="frommail">Phone</label>
                                        <p>{!!$data['accounts']['phone']!!}</p>
                                    </div>

                                    <div class="form-group">
                                        <label for="frommail">Account Type</label>
                                        <p><?php echo ($data['accounts']['tbl_accounttypes'] != null) ? $data['accounts']['tbl_accounttypes']['account_type'] : ''; ?></p>
                                    </div>

                                    <div class="form-group">
                                        <label for="frommail">Website</label>
                                        <p><?php echo ($data['accounts']['website'] != null) ? $data['accounts']['website'] : ''; ?></p>
                                    </div>
                                </div>

                            </section>
                            <div class="col-lg-12 pl-0">
                                <h3 class="badge badge-secondary">Address Information</h3>
                            </div>
                            <section class="row">
                                <div class="col-lg-6">

                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <p><?php echo ($data['accounts']['tbl_countries'] != null) ? $data['accounts']['tbl_countries']['name'] : ''; ?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <p><?php echo ($data['accounts']['city'] != null) ? $data['accounts']['city'] : ''; ?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="zip">Zip</label>
                                        <p><?php echo ($data['accounts']['zip'] != null) ? $data['accounts']['zip'] : ''; ?></p>
                                    </div>
                                </div>
                                {{-- </section> --}}
                                <!-- Left col -->
                                {{-- <section class="col-lg-6"> --}}
                                <div class="col-lg-6">

                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <p><?php echo ($data['accounts']['tbl_states'] != null) ? $data['accounts']['tbl_states']['name'] : ''; ?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="street">Street</label>
                                        <p><?php echo ($data['accounts']['street'] != null) ? $data['accounts']['street'] : ''; ?></p>
                                    </div>
                                </div>
                            </section>

                        </div>
                        <div class="card-footer pull-right text-right">
                            <a href="{{url('admin/accounts')}}" class="btn btn-outline-secondary mr-1">Back</a>
                            <a href="#" class="btn text-danger btn-outline-secondary"><i class="far fa-trash-alt"></i></a>
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
        $("#liaccounts").addClass("active");
    });
</script>

@endsection