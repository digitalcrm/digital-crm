@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    <h1><i class="far fa-edit"></i> New Mail</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <div class="col-lg-8">
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

                        <!-- /.card-header -->
                        <!-- form start -->
                        <!-- <form role="form" > -->
                        {{Form::open(['action'=>'MailController@store','method'=>'Post','enctype'=>'multipart/form-data'])}}
                        <!--store-->
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="sendto">To</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <select name="sendto" id="sendto" class="form-control select2" multiple="multiple" data-placeholder="Please select" style="width: 100%;" required></select>
                                <span class="text-danger">{{ $errors->first('sendto') }}</span>
                                <input type="hidden" value="" name="toIds" id="toIds" />
                                <input type="hidden" value="" name="toNames" id="toNames" />
                                <input type="hidden" value="" name="toEmails" id="toEmails" />
                                <input type="hidden" value="" name="toTypes" id="toTypes" />
                                <input type="hidden" value="" name="toTitles" id="toTitles" />
                                <!--                            <select class="select2" multiple="multiple" data-placeholder="Select a State"
                                    style="width: 100%;">
                                <option>Alabama</option>
                                <option>Alaska</option>
                                <option>California</option>
                                <option>Delaware</option>
                                <option>Tennessee</option>
                                <option>Texas</option>
                                <option>Washington</option>
                            </select>-->
                            </div>

                            <!--                        <div class="form-group">
                                                    <label for="billto">Lead</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                                    <input type="text" class="form-control" name="billto" id="billto" placeholder="" value="{{old('billto')}}" required>
                                                    <span class="text-danger">{{ $errors->first('billto') }}</span>
                        
                                                    <input type="hidden" value="3" name="userType" id="userType">
                                                    <input type="hidden" value="" name="userId" id="userId"> billtoId
                                                    <input type="hidden" value="" name="userEmail" id="userEmail"> billtovalue
                                                    <input type="hidden" value="" name="userName" id="userName"> billtolabel
                        
                                                    <div id="projectDiv"></div>
                                                </div>-->

                            <div class="form-group">
                                <label for="subject">Subject</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <input type="text" class="form-control" name="subject" id="subject" placeholder="" value="{{old('subject')}}" required>
                                <span class="text-danger">{{ $errors->first('subject') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="message">Message</label>&nbsp;<i class="fa fa-asterisk text-danger"></i>
                                <textarea class="form-control" name="message" id="message">{{old('Message')}}</textarea>
                                <span class="text-danger">{{ $errors->first('message') }}</span>
                            </div>
                            <div class="form-group">
                                <label for="attachment">Attachment</label>
                                <input type="file" class="btn btn-default" name="attachment" id="attachment" />
                                <span class="text-danger">{{ $errors->first('attachment') }}</span>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer bg-white pull-right text-right">
                            <a href="{{url('/mails')}}" class="btn btn-outline-secondary mr-1">Cancel</a>
                            {{Form::submit('Send',['class'=>"btn btn-primary pull-right"])}}
                        </div>
                        <!-- </form> -->
                        {{Form::close()}}
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

<script src="{{asset('assets/bower_components/ckeditor/ckeditor.js')}}"></script>
<script>
    var url = "{{url('ajaxwebtolead/getStateoptions')}}";
    var leadArray = '<?php echo json_encode($data['leads']); ?>';
    //alert(leadArray);
    var leadArr = [];

    $(function() {
        var elm = $('#sendto');
        elm.select2({
            placeholder: "Select features",
            data: eval("(" + leadArray + ")")
            //        data: [
            //            {id: 0, text: "enhancement"},
            //            {id: 1, text: "bug"},
            //            {id: 2, text: "duplicate"},
            //            {id: 3, text: "invalid"},
            //            {id: 4, text: "wontfix"}
            //        ]
        }).change(function() {

            var selectedIDs = $.map($(elm).select2('data'), function(val, i) {
                // alert(JSON.stringify(val));
                return val.id + " " + val.title + " " + val.text + " " + val.value;
            }).join(",");

            var toIds = $.map($(elm).select2('data'), function(val, i) {
                return val.id;
            }).join(",");
            var toNames = $.map($(elm).select2('data'), function(val, i) {
                return val.text;
            }).join(",");
            // var toEmails = $.map($(elm).select2('data'), function(val, i) {
            //     return val.value;
            // }).join(",");
            // var toTypes = $.map($(elm).select2('data'), function(val, i) {
            //     return val.type;
            // }).join(",");
            var toTitles = $.map($(elm).select2('data'), function(val, i) {
                return val.title;
            }).join(",");

            //    alert(selectedIDs);
            //    alert(toIds);
            //    alert(toNames);
            //    alert(toEmails);
            //    alert(toTypes);

            //        $('#selectedIDs').text(selectedIDs);

            $('#toIds').val(toIds);
            $('#toNames').val(toNames);
            $('#toTitles').val(toTitles);
            // var totitle = toTitles.split(",");
            // alert(totitle);
            // $('#toEmails').val(toEmails);
            // $('#toTypes').val(toTypes);
        });

        CKEDITOR.replace('message');

        $(".sidebar-menu li").removeClass("active");

        $("#account").change(function() {
            var acc = $(this).val();
            if (acc == "NewAccount") {
                $("#addAccount").show();
            } else {
                $("#addAccount").hide();
            }
        });

        $("#country").change(function() {
            var country = $(this).val();
            // alert(country);
            if (country > 0) {
                $.get(url, {
                    'country': country
                }, function(result, status) {
                    // alert(result);
                    $("#state").html(result);
                });
            }
        });

    });
</script>

<!--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<style>
    .ui-autocomplete {
        max-height: 200px;
        overflow-y: auto;
        /* prevent horizontal scrollbar */
        overflow-x: hidden;
    }
    /* IE 6 doesn't support max-height
     * we use height instead, but this forces the menu to always be this tall
     */
    * html .ui-autocomplete {
        height: 200px;
    }
</style>

<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/base/jquery-ui.css">
<script type='text/javascript'>//<![CDATA[
$(function() {
    //        alert(leadArray);
    $(function() {
        $("#billto").autocomplete({
            appendTo: $("#projectDiv"),
            source: eval("(" + leadArray + ")"), //countries_starting_with_A
            minLength: 1,
            select: function(event, ui) {
//                alert("Id : " + ui.item.id + " Value : " + ui.item.value + " Label : " + ui.item.label);
                $("#userId").val(ui.item.id);
                $("#userName").val(ui.item.label);
                $("#userEmail").val(ui.item.value);
            },
            open: function(event, ui) {
                var len = $('.ui-autocomplete > li').length;
            },
            close: function(event, ui) {
            },
            change: function(event, ui) {
                if (ui.item === null) {
                    $(this).val('');
                }
            }
        }).autocomplete("instance")._renderItem = function(ul, item) {
            return $("<li>")
                    .append("<div>" + item.label + "<br>" + item.value + "</div>")
                    .appendTo(ul);
        };
//        });

        // mustMatch (no value) implementation
        $("#billTo").focusout(function() {
        });
    });
}); //]]> 

</script>-->
@endsection