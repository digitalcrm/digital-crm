@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- <style>
    .modal.left .modal-dialog {
        position: fixed;
        right: 0;
        margin: auto;
        width: 320px;
        height: 100%;
        -webkit-transform: translate3d(0%, 0, 0);
        -ms-transform: translate3d(0%, 0, 0);
        -o-transform: translate3d(0%, 0, 0);
        transform: translate3d(0%, 0, 0);
    }

    .modal.left .modal-content {
        height: 100%;
        overflow-y: auto;
    }

    .modal.right .modal-body {
        padding: 15px 15px 80px;
    }

    .modal.right.fade .modal-dialog {
        left: -320px;
        -webkit-transition: opacity 0.3s linear, left 0.3s ease-out;
        -moz-transition: opacity 0.3s linear, left 0.3s ease-out;
        -o-transition: opacity 0.3s linear, left 0.3s ease-out;
        transition: opacity 0.3s linear, left 0.3s ease-out;
    }

    .modal.right.fade.show .modal-dialog {
        right: 0;
    }
</style> -->
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Accounts</h1> <small class="badge badge-secondary" id="totalBadge">{{$data['total']}}</small>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <!-- <button type="button" class="btn btn-demo" data-toggle="modal" data-target="#exampleModal">
                                Left Sidebar Modal
                            </button>

                            <a class="btn btn-sm btn-outline-secondary" href="#" data-toggle="modal" data-target="#AddAccount">Add Account</a> -->
                            <a class="btn btn-sm btn-primary mr-1" href="{{url('accounts/create')}}"><i class="far fa-plus-square mr-1"></i> New Account</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('accounts/import/csv')}}"><i class="fas fa-upload"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('accounts/export/csv')}}"><i class="fas fa-download"></i></a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{url('reports/accounts')}}"><i class="fa fa-chart-pie"></i></a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <div class="col-lg-12 p-0">
                    @if(session('success'))
                    <div id="alertSuccess" class='alert alert-success'>
                        {{session('success')}}
                    </div>
                    @endif

                    @if(session('error'))
                    <div id="alertError" class='alert alert-danger'>
                        {{session('error')}}
                    </div>
                    @endif

                    @if(session('info'))
                    <div id="alertWarning" class='alert alert-warning'>
                        {{session('info')}}
                    </div>
                    @endif

                    <!-- <div id="success-msg" class="hide alert alert-success">
                        <strong>Success!</strong> Account created successfully...!
                    </div>
                    <div id="error-msg" class="hide alert alert-danger">
                        <strong>Error occurred!</strong> Please try again later...!
                    </div> -->

                    <div class="card shadow card-primary card-outline">
                        <div class="card-body p-0" id="totalAccounts">
                            {!!$data['table']!!}
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white border-top">
                            <div class="btn-group btn-flat pull-left">
                                <button class="btn btn-sm btn-outline-secondary" id="selectAll" onclick="table_row_color_change();"><i class="fas fa-check"></i> Select All</button>
                                <button class="btn btn-sm text-danger btn-outline-secondary" onclick="return deleteAll();"><i class="far fa-trash-alt"></i></button>
                            </div>
                        </div>
                        <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->

                </div>
            </div>
            <!-- /.row -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!--Add Account Modal -->
<div id="AddAccount" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-center primecolor">Add Account</h3>
                <button type="button" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body" style="overflow: hidden;">


                <div class="col-md-offset-1 col-md-10">
                    {{Form::open(['action'=>'AccountController@store','method'=>'Post','enctype'=>'multipart/form-data', 'id'=>"AddAccountForm"])}}
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name">Account Name</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <input type="text" class="form-control" name="name" id="accountname" placeholder="" value="{{old('name')}}">
                                    <span class="text-danger" id="name-error">{{ $errors->first('name') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="mobile">Mobile</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <input type="text" class="form-control" name="mobile" id="mobileno" placeholder="" value="{{old('mobile')}}">
                                    <span class="text-danger" id="mobile-error">{{ $errors->first('mobile') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="industrytype">Industry Type</label>
                                    <select class="form-control" name="industrytype" id="industrytype">
                                        {!!$data['industryoptions']!!}
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="description">Notes</label>
                                    <textarea name="description" id="description" class="form-control" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="email">Email</label>&nbsp;&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                    <!--<i class="fa fa-asterisk text-danger"></i>-->
                                    <input type="email" class="form-control" name="email" id="email" placeholder="" value="{{old('email')}}">
                                    <span class="text-danger" id="email-error">{{ $errors->first('email') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder="" value="{{old('phone')}}">
                                    <span class="text-danger">{{ $errors->first('phone') }}</span>
                                </div>

                                <div class="form-group">
                                    <label for="accounttype">Account Type</label>
                                    <select class="form-control" name="accounttype" id="accounttype">
                                        {!!$data['accounttypeoptions']!!}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="company">Company</label>
                                    <input type="text" class="form-control" name="company" id="company" placeholder="" value="{{old('company')}}">
                                    <span class="text-danger">{{ $errors->first('company') }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="userpicture">Profile Picture</label>
                                    <input type="file" class="btn btn-default" name="userpicture" id="userpicture" />
                                    <span class="text-danger">{{ $errors->first('userpicture') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="employees">Employees</label>
                                    <input type="number" class="form-control" name="employees" id="employees" placeholder="" value="{{old('employees')}}">
                                    <span class="text-danger">{{ $errors->first('employees') }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="website">Website</label>
                                    <input type="url" class="form-control" name="website" id="website" placeholder="" value="{{old('website')}}">
                                    <span class="text-danger">{{ $errors->first('website') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 pl-0">
                            <h3 class="badge badge-info">Address Information</h3>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="street">Address</label>
                                    <input type="text" class="form-control" name="street" id="street" placeholder="" value="{{old('street')}}">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="country">Country</label>
                                    <select class="form-control" name="country" id="country">
                                        {!!$data['countryoptions']!!}
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="state">State</label>
                                    <select class="form-control" name="state" id="state">
                                        <option value="0">Select State</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" name="city" id="city" placeholder="" value="{{old('city')}}">
                                </div>
                                <div class="form-group">
                                    <label for="zip">Zip</label>
                                    <input type="text" class="form-control" name="zip" id="zip" placeholder="" value="{{old('zip')}}">
                                </div>
                            </div>

                        </div>

                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white pull-right text-right">
                        <a href="{{url('/accounts')}}" class="btn btn-outline-secondary mr-1">Cancel</a>
                        {{Form::submit('Create',['class'=>"btn btn-primary" ])}}
                    </div>
                    <!-- </form> -->
                    {{Form::close()}}
                </div>
            </div>
        </div>

    </div>
</div>

<div class="modal left fade" id="exampleModal" tabindex="" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="nav flex-sm-column flex-row">
                    <a class="nav-item nav-link active" href="#">Home</a>
                    <a href="#" class="nav-item nav-link">Link</a>
                    <a href="#" class="nav-item nav-link">Link</a>
                    <a href="#" class="nav-item nav-link">Link</a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    var deleteAllUrl = "{{url('accounts/deleteAll/{id}')}}";

    var statesurl = "{{url('ajaxwebtolead/getStateoptions')}}";
    var ajaxaccountsurl = "{{url('accounts/ajax/getlist')}}";
    $(function() {


        // $("#submitForm").submit(function(event) {
        //     event.preventDefault(); //prevent default action
        //     var post_url = $(this).attr("action"); //get form action url
        //     var form_data = $(this).serialize(); //Encode form elements for submission

        //     $.post(post_url, form_data, function(response) {
        //         alert(response);
        //         // $("#server-results").html(response);
        //     });
        // });

        // $('#AddAccountForm').submit(function(e) {

            // alert('sdsjhjk');

        //     e.preventDefault();
        //     var registerForm = $("#AddAccountForm");
        //     var formData = registerForm.serialize();
        //     $('#name-error').html("");
        //     $('#email-error').html("");
        //     $('#mobile-error').html("");

        //     $.ajax({
        //         url: '/accounts',
        //         type: 'POST',
        //         data: formData,
        //         success: function(data) {

        //             // alert(data);
        //             // console.log(data);
        //             if (data.errors) {
        //                 if (data.errors.name) {
        //                     $('#name-error').html(data.errors.name[0]);
        //                 }
        //                 if (data.errors.email) {
        //                     $('#email-error').html(data.errors.email[0]);
        //                 }
        //                 if (data.errors.mobile) {
        //                     $('#mobile-error').html(data.errors.mobile[0]);
        //                 }

        //             }

        //             if (data.success) {
        //                 getAccountAjax();
        //                 $("#AddAccountForm")[0].reset();
        //                 $('#AddAccount').modal('hide');
        //                 $('#success-msg').removeClass('hide');
        //                 setInterval(function() {
        //                     $('#success-msg').addClass('hide');
        //                 }, 3000);
        //             }

        //             if (data.error) {
        //                 $("#AddAccountForm")[0].reset();
        //                 $('#AddAccount').modal('hide');
        //                 $('#error-msg').removeClass('hide');
        //                 setInterval(function() {
        //                     $('#error-msg').addClass('hide');
        //                 }, 3000);
        //             }
        //         },
        //     });
        // });

        // alert('active');
        $(".sidebar-menu li").removeClass("active");
        $("#liaccounts").addClass("active");

        $("#selectAll").click(function() {
            // $(".checkAll").prop('checked', $(this).prop('checked'));
            var checked = ($(".checkAll").prop('checked') == false) ? true : false;
            $(".checkAll").prop('checked', checked);
        });

        $("#country").change(function() {
            var country = $(this).val();
            // alert(country);
            if (country > 0) {
                $.get(statesurl, {
                    'country': country
                }, function(result, status) {
                    // alert(result);
                    $("#state").html(result);
                });
            }
        });


    });

    function deleteAll() {
        var deleteIdlength = $('.checkAll:checked').length;
        if (deleteIdlength > 0) {
            var checkList = $('.checkAll:checked');
            var itemIds = [];
            $(checkList).each(function(index) {
                itemIds[index] = $(checkList).get(index).id;
            });

            // alert(itemIds);

            $.get(deleteAllUrl, {
                'id': itemIds
            }, function(result, status) {
                if (result > 0) {
                    alert('Deleted successfully...');
                } else {
                    alert('Failed. Try again...');
                }
                location.reload();
            });
        } else {
            alert('Please select...');
        }
    }

    function getAccountAjax() {
        $.get(ajaxaccountsurl, {}, function(result, status) {
            // alert(result);
            var res = eval("(" + result + ")");
            $("#totalBadge").text(res.total);
            $("#totalAccounts").html(res.table);
            $('#accountsTable').DataTable({
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': false,
                'info': true,
                'autoWidth': false,
                'columnDefs': [{
                    type: 'date-uk',
                    targets: 4
                }]
            });
        });
    }
</script>
@endsection
