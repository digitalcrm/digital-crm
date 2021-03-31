@extends('layouts.user')
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
                <div class="box">
                    <div class="box-header">
                        <h1 class="box-title">Newsletter &nbsp;<small class="badge badge-secondary">{{$data['total']}}</small></h1>
                        <div class="btn-group btn-default pull-right">
                            <a class="btn bg-blue" href="{{url('newsletter/create')}}"><i class="fas fa-plus"></i>&nbsp; Create</a>
                        </div>

                    </div> 
                    <!--/.box-header--> 
                    <div class="box-body">
                        {!!$data['table']!!}
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
                <div id="resulttt">

                </div>
            </div>
        </div>
        <!-- /.row -->
	</div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    $(function() {
    });



</script>
@endsection