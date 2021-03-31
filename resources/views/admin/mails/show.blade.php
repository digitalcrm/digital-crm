@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
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
        <!-- Small boxes (Stat card) -->
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
            <!-- general form elements -->
            <div class="col-md-2">
                <a href="{{url('mails/create')}}" class="btn btn-primary btn-block margin-bottom">Compose</a>

                <div class="card card-solid">
                    <div class="card-body no-padding">
                        <ul class="nav nav-pills nav-stacked">
<!--                            <li><a href="{{url('mails')}}"><i class="fa fa-inbox"></i> Inbox
                                    <span class="label label-primary float-right">{!!$data['total']!!}</span></a>
                            </li>-->
                            <li class="active"><a href="{{url('admin/mails')}}"><i class="fa fa-envelope"></i> Sent</a></li>
                            <li><a href="{{url('admin/mails/trash/deletedmails')}}"><i class="fa fa-trash"></i> Trash</a></li>
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /. card -->

            </div>

            <div class="col-lg-10">


                <div class="card card-primary">
                    <div class="card-header with-border">
                        <h3 class="card-title">{!!$data['subject']!!}</h3>

                        <!--                        <div class="card-tools float-right">
                                                    <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="" data-original-title="Previous"><i class="fa fa-chevron-left"></i></a>
                                                    <a href="#" class="btn btn-box-tool" data-toggle="tooltip" title="" data-original-title="Next"><i class="fa fa-chevron-right"></i></a>
                                                </div>-->

                        <!--<div class="mailbox-controls">-->
                        <div class="float-right">
                            <!--<button type="button" class="btn btn-default"><i class="fa fa-reply"></i> Reply</button>-->
                            <!--<button type="button" class="btn btn-default"><i class="fa fa-share"></i> Forward</button>-->
                            <!--<button type="button" class="btn btn-default"><i class="fa fa-trash"></i> Delete</button>-->
                            <a href="{{url('admin/mails/delete/'.$data['mail_id'])}}" class="btn btn-default"><i class="fa fa-trash"></i> Delete</a>
                            <!--<a href="{{url('admin/mails/print/'.$data['mail_id'])}}" class="btn btn-default"><i class="fa fa-print"></i> Print</a>-->
                        </div>
                        <!--</div>-->
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body no-padding">
                        <div class="mailbox-read-info">
                            <!--<h3>Message Subject Is Placed Here</h3>-->
                            <h5>To: {!!$data['emails']!!}
                                <span class="mailbox-read-time float-right">{!! date('d-m-Y h:i a',strtotime($data['created_at']))!!}</span></h5>
                        </div>
                        <!-- /.mailbox-read-info -->

                        <!-- /.mailbox-controls -->
                        <div class="mailbox-read-message">

                            {!!$data['message']!!}

                        </div>
                        <!-- /.mailbox-read-message -->
                    </div>
                    <!-- /.card-body -->

                    <?php
                    if ($data['attachments'] != '') {

                        $attachments = explode(",", $data['attachments']);

                        echo '<div class="card-footer bg-white border-top">';
                        echo '<ul class="mailbox-attachments clearfix">';
                        foreach ($attachments as $attachment) {

                            $attachment = str_replace('\\', '/', $attachment);
//                            echo $attachment;
                            $attachmentName = explode("/", $attachment);



                            echo '<li>';
                            echo '<span class="mailbox-attachment-icon"><i class="fa fa-paperclip"></i></span>';
                            echo '<div class="mailbox-attachment-info">';
                            echo '<a href="' . url($attachment) . '" class="mailbox-attachment-name">' . $attachmentName[count($attachmentName) - 1] . '</a>';
                            echo '</div>';
                            echo '</li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                    }
                    ?>


                    <!-- /.card-footer -->
                    <div class="card-footer bg-white border-top">
                        <div class="float-right">
                            <a href='<?php echo url('admin/mails'); ?>' class="btn btn-default">Back</a>
                        </div>
                    </div>
                    <!-- /.card-footer -->
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
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#limail").addClass('menu-open');
    });


</script>
@endsection