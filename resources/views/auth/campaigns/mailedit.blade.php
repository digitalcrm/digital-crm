@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content mt-2 mx-0"><div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-12">
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
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Edit Campaign Mail
                        </h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <!-- <form role="form" > -->
                    {{Form::open(['action'=>['CampaignController@updateCampaignMail',$data['camp_id']],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                    <!--store-->
                    @csrf
                    <div class="box-body">
                        <div class="form-group">
                            <label for="typeto">To</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                            <select name="typeto" id="typeto" class="form-control" required>
                                <option value="">Select ...</option>
                                <option value="1" >Accounts</option>
                                <option value="2" >Contacts</option>
                                <option value="3" >Leads</option>
                                <option value="4" >Forms</option>
                            </select>
                            <span class="text-danger">{{ $errors->first('sendto') }}</span>
                        </div>
                        <div class="form-group" style="display: none" id="formselectDiv">
                            <select name="forms" id="forms" class="form-control">
                                <option value="">Select Form...</option>
                            </select>
                            <span class="text-danger">{{ $errors->first('sendto') }}</span>
                        </div>
                        <div class="form-group">
                            <select name="sendto" id="sendto" class="form-control select2" multiple="multiple" data-placeholder="Select ..." style="width: 100%;" required></select>
                            <span class="text-danger">{{ $errors->first('sendto') }}</span>
                            <input type="hidden" value="" name="toIds" id="toIds"/>
                            <input type="hidden" value="" name="toNames" id="toNames"/>
                            <input type="hidden" value="" name="toEmails" id="toEmails"/>
                        </div>
                        <div class="form-group">
                            <label for="emailtemplates">Email Templates</label>
                            <select name="emailtemplates" id="emailtemplates" class="form-control" >
                                {!!$data['templateOptions']!!}
                            </select>
                            <span class="text-danger">{{ $errors->first('emailtemplates') }}</span>
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                            <input type="text" class="form-control" name="subject" id="subject" placeholder="" value="{{$data['subject']}}" required>
                            <span class="text-danger">{{ $errors->first('subject') }}</span>
                        </div>

                        <div class="form-group">
                            <label for="message">Message</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                            <textarea class="form-control" name="message" id="message" required>{{$data['message']}}</textarea>
                            <span class="text-danger">{{ $errors->first('message') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="attachment">Attachment</label>
                            <input type="file" class="btn btn-default" name="attachment" id="attachment" />
                            <span class="text-danger">{{ $errors->first('attachment') }}</span>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <div class="btn-group btn-flat pull-right">
                            <a href="{{url('campaigns/'.$data['camp_id'])}}" class="btn btn-default">Back</a>
                            <!--{{Form::hidden('_method','PUT')}}-->
                            {{Form::submit('Preview',['class'=>"btn btn-primary pull-right"])}}
                        </div>
                    </div>
                    {{Form::close()}}
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
<script src="{{asset('assets/bower_components/ckeditor/ckeditor.js')}}"></script>
<script>
var gettemplatedetails = "{{url('mailtemplates/gettemplatedetails/{id}')}}";
var getcampaignsendto = "{{url('campaigns/getcampaignsendto/{type}/{form}')}}";
var getforms = "{{url('campaigns/getforms/{type}')}}";
$(function() {
    $(".sidebar-menu li").removeClass("active");
    $("#licampaigns").addClass("active");

    CKEDITOR.replace('message');

    $('#sendto').select2();

    $('#typeto').change(function() {
        var type = $(this).val();

        if (type > 0) {

            if (type == 4) {
                $('#sendto').html("");
                $.get(getforms, {'type': type}, function(result, status) {
//                    alert(result);
                    $("#formselectDiv").show();
//                    alert(result);
                    $("#forms").html(result);
                });
            } else {
                $.get(getcampaignsendto, {'type': type, 'form': 0}, function(result, status) {
//                alert(result);
                    $("#formselectDiv").hide();
                    $('#toIds').val("");
                    $('#toNames').val("");
                    $('#toEmails').val("");
                    $('#sendto').html("").select2({
                        placeholder: "Select ...",
                        data: eval("(" + result + ")")
                    });
                });
            }
        }
    });

    $("#forms").change(function() {
        var form = $(this).val();
        var type = $('#typeto').val();
        $.get(getcampaignsendto, {'type': type, 'form': form}, function(result, status) {
//                alert(result);
            $('#toIds').val("");
            $('#toNames').val("");
            $('#toEmails').val("");
            $('#sendto').html("").select2({
                placeholder: "Select ...",
                data: eval("(" + result + ")")
            });
        });
    });

    $('#sendto').change(function() {
        var toIds = $.map($(this).select2('data'), function(val, i) {
            return val.id;
        }).join(",");
        var toNames = $.map($(this).select2('data'), function(val, i) {
            return val.text;
        }).join(",");
        var toEmails = $.map($(this).select2('data'), function(val, i) {
            return val.value;
        }).join(",");

//        alert(toIds);
//        alert(toNames);
//        alert(toEmails);

        $('#toIds').val(toIds);
        $('#toNames').val(toNames);
        $('#toEmails').val(toEmails);
    });




    $("#emailtemplates").change(function() {
//            alert(gettemplatedetails);
        var n = $(this).val();
        if (n > 0) {
            $.get(gettemplatedetails, {'temp_id': n}, function(result, status) {
//                    alert(result);
                var res = eval('(' + result + ')');
                $("#subject").val(res.subject);
//                    $("#message").val(res.message);
                CKEDITOR.instances['message'].setData(res.message)
            });
        } else {
            alert('Select template');
            return false;
        }
    });
});
</script>
@endsection
