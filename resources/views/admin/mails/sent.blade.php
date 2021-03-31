@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Mails</h1>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <div class="row">
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
            <div class="col-md-2">
                <a href="{{url('admin/mails/create')}}" class="btn btn-primary btn-block margin-bottom">Compose</a>

                <div class="card card-solid">
                    <div class="card-body no-padding">
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="{{url('admin/mails')}}"><i class="fa fa-inbox"></i> Inbox
                                    <span class="label label-primary float-right">12</span></a></li>
                            <li class="active"><a href="{{url('admin/sentmails')}}"><i class="fa fa-envelope-o"></i> Sent</a></li>
                            <li><a href="{{url('admin/mails/trash')}}"><i class="fa fa-trash-o"></i> Trash</a></li>
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /. card -->

            </div>
            <!-- /.col -->
            <div class="col-md-10">
                <div class="card card-primary">
                    <div class="card-header with-border">
                        <h3 class="card-title">Inbox</h3>
                        <!-- /.card-tools -->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body no-padding">

                        <div class="table-responsive mailbox-messages">
                            <table class="table table-hover table-striped">
                                <tbody>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                                        <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                                        <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                                        </td>
                                        <td class="mailbox-attachment"></td>
                                        <td class="mailbox-date">5 mins ago</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                                        <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                                        <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                                        </td>
                                        <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                                        <td class="mailbox-date">28 mins ago</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                                        <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                                        <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                                        </td>
                                        <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                                        <td class="mailbox-date">11 hours ago</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                                        <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                                        <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                                        </td>
                                        <td class="mailbox-attachment"></td>
                                        <td class="mailbox-date">15 hours ago</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                                        <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                                        <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                                        </td>
                                        <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                                        <td class="mailbox-date">Yesterday</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                                        <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                                        <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                                        </td>
                                        <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                                        <td class="mailbox-date">2 days ago</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                                        <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                                        <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                                        </td>
                                        <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                                        <td class="mailbox-date">2 days ago</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                                        <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                                        <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                                        </td>
                                        <td class="mailbox-attachment"></td>
                                        <td class="mailbox-date">2 days ago</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                                        <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                                        <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                                        </td>
                                        <td class="mailbox-attachment"></td>
                                        <td class="mailbox-date">2 days ago</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                                        <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                                        <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                                        </td>
                                        <td class="mailbox-attachment"></td>
                                        <td class="mailbox-date">2 days ago</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                                        <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                                        <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                                        </td>
                                        <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                                        <td class="mailbox-date">4 days ago</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                                        <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                                        <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                                        </td>
                                        <td class="mailbox-attachment"></td>
                                        <td class="mailbox-date">12 days ago</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td class="mailbox-star"><a href="#"><i class="fa fa-star-o text-yellow"></i></a></td>
                                        <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                                        <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                                        </td>
                                        <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                                        <td class="mailbox-date">12 days ago</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                                        <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                                        <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                                        </td>
                                        <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                                        <td class="mailbox-date">14 days ago</td>
                                    </tr>
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                                        <td class="mailbox-name"><a href="read-mail.html">Alexander Pierce</a></td>
                                        <td class="mailbox-subject"><b>AdminLTE 2.0 Issue</b> - Trying to find a solution to this problem...
                                        </td>
                                        <td class="mailbox-attachment"><i class="fa fa-paperclip"></i></td>
                                        <td class="mailbox-date">15 days ago</td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- /.table -->
                        </div>
                        <!-- /.mail-box-messages -->
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer no-padding">

                    </div>
                </div>
                <!-- /. card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
	</div>
    </section>
    <!-- /.content -->

</div>
<!-- /.content-wrapper -->

<script>

    $(function() {
//        alert('hi');
        $(".sidebar-menu li").removeClass("active");
        $("#limail").addClass("active");


    });



</script>
@endsection