@extends('layouts.adminlte-boot-4.user')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-4">
                    {{-- <h1 id="searchTitle"><small class="badge badge-secondary" id="labelTotal">{{$data['total']}}</small></h1> --}}
                    <h1 id="searchTitle" class="text text-black">
                        {{-- Here data comes from script. Scripts are written at the end of this file --}}
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content mt-2 mx-0">
        <!-- Small cardes (Stat card) -->
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

                @if(session('info'))
                <div class='alert alert-warning'>
                    {{session('info')}}
                </div>
                @endif
                <div class="card">

                    <div class="card-body p-0">
                        {!!$data['table']!!}
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    var searchTerm = '{{$data["search"]}}';
    $(function() {
//        alert(searchTerm);
        $("#searchTerm").val(searchTerm);
        // alert('active');
        $(".sidebar-menu li").removeClass("active");
//        $("#liaccounts").addClass("active");
    });
</script>
<script>
var selected = '{{ $selectedField }}';//using this we can get the selected field
var total = '{{$data['total']}}';
$(function() {
    if(selected == 'Deal'){
        $('#searchfield option:contains(Deal)').prop('selected',true);
        $('#searchTitle').html(`Deal <small class='badge badge-secondary'>${total}</small>`);
    } else if(selected == 'Lead'){
        $('#searchfield option:contains(Lead)').prop('selected',true);
        $('#searchTitle').html(`Lead <small class='badge badge-secondary'>${total}</small>`);
    } else if(selected == 'Contact'){
        $('#searchfield option:contains(Contact)').prop('selected',true);
        $('#searchTitle').html(`Contact <small class='badge badge-secondary'>${total}</small>`);
    } else if(selected == 'Account'){
        $('#searchfield option:contains(Account)').prop('selected',true);
        $('#searchTitle').html(`Account <small class='badge badge-secondary'>${total}</small>`);
    } else if(selected == 'Webtolead'){
        $('#searchfield option:contains(Webtolead)').prop('selected',true);
        $('#searchTitle').html(`Webtolead <small class='badge badge-secondary'>${total}</small>`);
    } else if(selected == 'Order') {
        $('#searchfield option:contains(Order)').prop('selected',true);
        $('#changehead2').html(`Order Number`);
        $('#searchTitle').html(`Order <small class='badge badge-secondary'>${total}</small>`);
    } else if(selected == 'Customer'){
        $('#searchfield option:contains(Customer)').prop('selected',true);
        $('#changehead1').html(`Lead`);
        $('#changehead2').html(`Deal`);
        $('#changehead3').html(`Value`);
        $('#changehead4').html(`Closing Date`);
        $('#searchTitle').html(`Customers <small class='badge badge-secondary'>${total}</small>`);
    } else if(selected == 'Sales'){
        $('#searchfield option:contains(Sales)').prop('selected',true);
        $('#changehead1').html(`Lead`);
        $('#changehead2').html(`Deal`);
        $('#changehead3').html(`Value`);
        $('#changehead4').html(`Closing Date`);
        $('#searchTitle').html(`Customers <small class='badge badge-secondary'>${total}</small>`);
    } else {
        $('#searchfield option:contains(All)').prop('selected',true);
        $('#searchTitle').html(`Search Result <small class='badge badge-secondary'>${total}</small>`);
    }
});
</script>

@endsection
