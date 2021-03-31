@extends('layouts.adminlte-boot-4.user')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1>Read Campaign Mail</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content mt-2 mx-0"><div class="container-fluid">
        <!-- Small boxes (Stat box) -->
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
                <a href="{{url('mails/create')}}" class="btn btn-primary btn-block mb-3">Compose</a>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Folders</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">

                            <li class="nav-item">
                                <a href="{{url('campaigns/mails/sent/'.$data['camp_id'])}}" class="nav-link">
                                    <i class="far fa-envelope"></i> Sent
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('campaigns/mails/trash/'.$data['camp_id'])}}" class="nav-link">
                                    <i class="far fa-trash-alt"></i> Trash
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

            </div>

            <div class="col-lg-10">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">{!!$data['subject']!!}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div class="mailcard-read-info">
                            <h6>To: <?php
                                    if ($data['type'] == 1) {
                                        echo 'Accounts';
                                    }
                                    if ($data['type'] == 2) {
                                        echo 'Contacts';
                                    }
                                    if ($data['type'] == 3) {
                                        echo 'Leads';
                                    }
                                    if ($data['type'] == 4) {
                                        echo 'Formleads';
                                    }
                                    ?>
                                <span class="mailcard-read-time float-right">{!! date('d-m-Y h:i a',strtotime($data['created_at']))!!}</span></h6>
                        </div>
                        <!-- /.mailcard-read-info -->

                        <div class="mailcard-read-message">
                            {!!$data['message']!!}
                        </div>
                        <!-- /.mailcard-read-message -->
                    </div>
                    <!-- /.card-body -->


                    <?php
                    if ($data['attachments'] != '') {

                        $attachments = explode(",", $data['attachments']);

                        echo '<div class="card-footer bg-white">';
                        echo '<ul class="mailcard-attachments d-flex align-items-stretch clearfix">';
                        foreach ($attachments as $attachment) {

                            $attachment = str_replace('\\', '/', $attachment);
                            //                            echo $attachment;
                            $attachmentName = explode("/", $attachment);

                            echo '<li>';
                            echo '<span class="mailcard-attachment-icon"><i class="fa fa-paperclip"></i></span>';
                            echo '<div class="mailcard-attachment-info">';
                            echo '<a href="' . url($attachment) . '" class="mailcard-attachment-name">' . $attachmentName[count($attachmentName) - 1] . '</a>';
                            echo '</div>';
                            echo '</li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                    }
                    ?>


                    <!-- /.card-footer -->
                    <div class="card-footer">
                        <div class="float-right">
                            <a href="{{url('mails/trash/delete/'.$data['mail_id'])}}" class="btn btn-outline-secondary mr-1"><i class="far text-danger fa-trash-alt"></i></a>
                            <a href="{{url('campaigns/mails/sent/'.$data['camp_id'])}}" class="btn btn-outline-secondary">Back</a>
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
<!-- /.content-wrapper -->
<script src="{{asset('assets/bower_components/ckeditor/ckeditor.js')}}"></script>
<script>
    $(function() {
        $(".sidebar-menu li").removeClass("menu-open");
        //    $(".sidebar-menu li").removeClass("active");
        //    $("#ulaccounts").addClass('menu-open');
        //    $("#ulaccounts ul").css('display', 'block');
        // $("#licreatelead").addClass("active");

    });
</script>
@endsection