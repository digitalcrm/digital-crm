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
	<section class="content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">
            <h1>{!!$form->title!!}</h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>


    <!-- Main content -->
    <section class="content mt-2 mx-0">
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
                <div class='alert alert-success'>
                    {{session('error')}}
                </div>
                @endif
                <!-- general form elements -->
                <div class="card shadow card-primary card-outline">
                    <div class="card-body">
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
                                <div class="card-header with-border">
                                    <h3 class="card-title">Google Captcha</h3>
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
                            <div class="card-header with-border">
                                <h3 class="card-title">Autoresponder</h3>
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
                    <div class="card-footer bg-white pull-right text-right">
                            <a href="{{url('webtolead')}}" class="btn btn-outline-secondary">Back</a>
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
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        $("#ulwebtolead").addClass('menu-open');
        $("#ulwebtolead ul").css('display', 'block');
        $("#liwebtolead").addClass("active");
    });


</script>
@endsection