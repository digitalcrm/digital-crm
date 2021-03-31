@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
     <!-- Content Header (Page header) -->
     <section class="content-header">
          <div class="container-fluid">
               <div class="row">
                    <div class="col-sm-4">
                         <h1>Profile </h1>
                    </div>

               </div>
          </div><!-- /.container-fluid -->
     </section>

     <!-- Main content -->
     <section class="content mt-2 mx-0">
          <div class="container-fluid">
               <!-- Small cardes (Stat card) -->
               <div class="row">
                    <div class="col-lg-4">
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

                              {{Form::open(['action'=>['HomeController@userCurrencyUpdate',Auth::user()->id],'method'=>'Post','enctype'=>'multipart/form-data','class'=>'form-horizontal'])}}
                              @csrf
                              <div class="card-body">

                                   <div class="row">

                                             <div class="form-group">
                                                  <label for="currency" class="control-label">Currency</label>
                                                  <select class="form-control" name="currency" id="currency" required>
                                                       {!!$data['croptions']!!}
                                                  </select>
                                             </div>



                                   </div>

                              </div>
                              <!-- /.card-body -->
                              <div class="card-footer bg-white pull-right text-right">
                                   <a href="{{url('/user/currency/'.Auth::user()->id)}}" class="btn btn-outline-secondary mr-1">Back</a>
                                   {{Form::hidden('_method','PUT')}}
                                   {{Form::submit('Save',['class'=>"btn btn-primary pull-right"])}}
                              </div>
                              <!-- /.card-footer -->
                         </div>
                    </div>

                    </form>
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
     var url = "{{url('ajaxwebtolead/getStateoptions')}}";
     $(function() {
          $(".sidebar-menu li").removeClass("active");


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
               } else {
                    $("#state").val(0);
                    return false;
               }
          });


     });
</script>
@endsection