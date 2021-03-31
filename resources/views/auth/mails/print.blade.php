@extends('layouts.print')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content mt-2 mx-0">
	<div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">



            <div class="col-lg-12">


                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{!!$data['subject']!!}</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="mailbox-read-info">
                            <h5>To: <?php echo Auth::user()->email; ?></h5>
                        </div>
                        <div class="mailbox-read-info">
                            <h5>To: {!!$data['emails']!!}
                                <span class="mailbox-read-time pull-right">{!! date('d-m-Y h:i a',strtotime($data['created_at']))!!}</span>
                            </h5>
                        </div>
                        <!-- /.mailbox-read-info -->

                        <!-- /.mailbox-controls -->
                        <div class="mailbox-read-message">

                            {!!$data['message']!!}

                        </div>
                        <!-- /.mailbox-read-message -->
                    </div>
                    <!-- /.box-body -->

                    <?php
                    if ($data['attachments'] != '') {

                        $attachments = explode(",", $data['attachments']);

                        echo '<div class="box-footer">';
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


                    
                </div>

                <!-- /.box -->

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


</script>
@endsection