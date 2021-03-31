@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1><i class="far fa-edit"></i> Edit RD</h1>
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
                        {{Form::open(['action'=>['RdController@update',$data['rd_id']],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="card-body">

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="first_name">Rd Title</label>
                                        <div class="col-md-9">
										<input type="text" class="form-control required" name="title" id="title" placeholder="" value="{!!$data['title']!!}" required tabindex="1">
                                        <span class="text-danger">{{ $errors->first('title') }}</span>
										</div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_id">Product</label>
                                        <div class="col-md-9">
										<select class="form-control required" name="pro_id" id="pro_id" required tabindex="2">
                                            {!!$data['productoptions']!!}
                                        </select>
										</div>
                                    </div>

                                    <!-- <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="lead">Industry Type</label>
                                        <select class="form-control" name="intype_id" id="intype_id" required tabindex="3">
                                            {!!$data['industryoptions']!!}
                                        </select>
                                    </div> -->

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="last_name">RD Type</label>
                                        <div class="col-md-9">
										<select class="form-control required" name="rdtype_id" id="rdtype_id" required tabindex="4">
                                            {!!$data['rdtypeoptions']!!}
                                        </select>
                                        <span class="text-danger">{{ $errors->first('rdtypeoptions') }}</span>
										</div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="rdpr_id">Priority</label>
                                        <div class="col-md-9">
										<select class="form-control required" name="rdpr_id" id="rdpr_id" required tabindex="5">
                                            {!!$data['rdprtypeoptions']!!}
                                        </select>
										</div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="rd_status">Status</label>
                                        <div class="col-md-9">
										<select class="form-control" name="rd_status" id="rd_status">
                                            <option value='0'>None</option>
                                            <option value='1' {{(($data['status'] == 1) ? 'selected' : '')}}>Yes</option>
                                            <option value='2' {{(($data['status'] == 2) ? 'selected' : '')}}>No</option>
                                        </select>
										</div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="userpicture">Link</label>
                                        <div class="col-md-9">
										<input type="text" name="link" id="link" class="form-control" placeholder="" value="{!!$data['link']!!}">
                                        <span class="text-danger">{{ $errors->first('link') }}</span>
										</div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="rdtr_id">Trend</label>
                                        <div class="col-md-9">
										<select class="form-control" name="rdtr_id" id="rdtr_id">
                                            {!!$data['rdtrendoptions']!!}
                                        </select>
										</div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="pro_category">Product Category</label>
                                        <div class="col-md-9">
										<input type="text" name="pro_category" id="pro_category" class="form-control" placeholder="Product Category" value="{{$data['pro_category']}}" max="100">
                                        <span class="text-danger">{{ $errors->first('pro_category') }}</span>
										</div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="closingdate">Creation Date</label>
                                        <div class="col-md-9">
										<div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="createdate" id="datepicker" placeholder="" value="<?php echo ($data['creation_date'] != null) ? date('d-m-Y', strtotime($data['creation_date'])) : ''; ?>">
                                            <span class="text-danger">{{ $errors->first('createdate') }}</span>
                                        </div>
										</div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="closingdate">Submission Date</label>
                                        <div class="col-md-9">
										<div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="submitdate" id="datepicker2" placeholder="" value="<?php echo ($data['submission_date'] != null) ? date('d-m-Y', strtotime($data['submission_date'])) : ''; ?>">
                                            <span class="text-danger">{{ $errors->first('submitdate') }}</span>
                                        </div>
										</div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 col-form-label text-right" for="uploadeddate">Uploaded Date</label>
                                        <div class="col-md-9">
										<div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                            </div>
                                            <input type="text" class="form-control" name="uploadeddate" id="datepicker3" placeholder="" value="<?php echo ($data['uploaded_date'] != null) ? date('d-m-Y', strtotime($data['uploaded_date'])) : ''; ?>">
                                            <span class="text-danger">{{ $errors->first('uploadeddate') }}</span>
                                        </div>
										</div>
                                    </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white text-right pull-right">
                            <a href="{{URL::previous()}}" class="btn btn-outline-secondary mr-1">Cancel</a>
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
<!--JQuery Datepicker-->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    var ajaxgetproductdetails = "{{url('products/ajaxgetproductdetails/{id}')}}";

    $(function() {

        $("#datepicker").datepicker({
            "dateFormat": 'dd-mm-yy'
        });

        $("#datepicker2").datepicker({
            "dateFormat": 'dd-mm-yy'
        });

        $("#datepicker3").datepicker({
            "dateFormat": 'dd-mm-yy'
        });

        $("#pro_id").change(function() {
            var proId = $(this).val();
            // alert(proId);
            if (Number(proId) > 0) {
                $.get(ajaxgetproductdetails, {
                    'id': proId
                }, function(result) {
                    // alert(result);
                    var res = eval("(" + result + ")");
                    var pro_category = '';
                    if (res.procat_id > 0) {
                        // alert(res.tbl_productcategory.category);
                        pro_category = (res.tbl_productcategory != '') ? res.tbl_productcategory.category : '';
                    }
                    $("#pro_category").val(pro_category);
                });
            }
        });
    });
</script>
@endsection