@extends('layouts.adminlte-boot-4.user')
@section('content')
<!--style css-->
<style>
    /* change border radius for the tab , apply corners on top*/

    #exTab3 .nav-pills > li > a {
        border-radius: 4px 4px 0 0 ;
    }

    #exTab2 .tab-content {
        padding : 15px 15px;
    }

    /*#exTab2{
      padding-left: 15px;
    }*/
</style>
<!--end of style css-->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
<!--    <section class="content-header">
        <h1>
            View Form
             <small>Control panel</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Create Web to Lead</li>
        </ol>
    </section>-->

    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small boxes (Stat box) -->
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
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{!!$form->title!!}</h3>
                        <a class="pull-right btn btn-primary" href="{{url('webtolead/'.$form->form_id.'/edit')}}">Edit</a>
                    </div>
                    <div class="box-body">
                        <input type="hidden" name="from_id" id="from_id" value="{{$form->post_url}}">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <p>{!!$form->title!!}</p>
                        </div>
                        <div class="form-group">
                            <label for="posturl">Post Url</label>
                            <p>{!!$form->post_url!!}</p>
                        </div>
                        <div class="form-group">
                            <label for="redirecturl">Redirect Url</label>
                            <p>{!!$form->redirect_url!!}</p>
                        </div>
                        <div class="form-group">
                            <label for="frommail">From Mail</label>
                            <p>{!!$form->from_mail!!}</p>
                        </div>
                        <?php
                        if (($form->site_key != '') && ($form->secret_key != '')) {
                            ?>
                            <div class="form-group">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Google Captcha</h3>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="site_key">Site Key</label>
                                <p>{!!$form->site_key!!}</p>
                            </div>
                            <div class="form-group">
                                <label for="secret_key">Secret Key</label>
                                <p>{!!$form->secret_key!!}</p>
                            </div>
                            <?php
                        }
                        ?>

                        <div class="form-group">
                            <div class="box-header with-border">
                                <h3 class="box-title">Autoresponder</h3>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <p>{!!$form->subject!!}</p>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <p>{!!$form->message!!}</p>
                        </div>


                    </div>
                    <div class="box-footer">
                        <a href="{{url('webtolead')}}" class="btn text-primary pull-right">Back</a>

                    </div>
                </div>
                <!-- /.box -->

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
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        $("#ulwebtolead").addClass('menu-open');
        $("#ulwebtolead ul").css('display', 'block');
        $("#liwebtolead").addClass("active");
    });


</script>
@endsection
