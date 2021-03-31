@extends('layouts.adminlte-boot-4.admin')
@section('title', "RFQ's")
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-4 mt-0">
                    <h1 class="m-0 text-dark">RFQ's <small class="badge badge-secondary">{{ count($rfqs) }}</small>
                    </h1>

                </div>
                <div class="col-sm-8">

                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow card-primary card-outline">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-8">
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="btn-group dropdown keep-open">
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                id="login" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="caret"><i class="fa fa-filter" aria-hidden="true"></i></span>
                                            <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                        <div class="dropdown-menu">
                                            <form action="#" id="popForm" method="get" class="p-2">
                                                <div id="filtertickettable">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0 table-responsive">
                            <table id="exampletable" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th style="display: none">UserName</th>
                                        <th>Prduct Category</th>
                                        <th>Prduct SubCategory</th>
                                        <th>Unit</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rfqs as $rfq)
                                        <tr>
                                            <td>
                                                {{ $rfq->product_name }}
                                            </td>
                                            <td style="display: none">
                                                {{ $rfq->user->name }}
                                            </td>
                                            <td>
                                                {{ $rfq->tbl_category->category ?? '' }}
                                            </td>
                                            <td>
                                                {{ $rfq->tbl_sub_category->category ?? '' }}
                                            </td>
                                            <td>
                                                {{ $rfq->product_quantity }}
                                            </td>
                                            <td>
                                                {{ $rfq->unit->name ?? '' }}
                                            </td>
                                            <td>
                                                {{ $rfq->purchase_price }}
                                            </td>
                                            {{-- <td>
                                                <div class="btn-group">
                                                    <button type="button"
                                                        class="btn btn-outline-secondary dropdown-toggle"
                                                        data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <small>
                                                            <a class="dropdown-item" href="#" onclick="event.preventDefault();
                                                    if(confirm('Are you sure!')){
                                                        $('#form-delete-{{ $rfq->id }}').submit();
                                                    }
                                                    ">Delete</a>
                                                            <form style="display:none" method="post"
                                                                id="form-delete-{{ $rfq->id }}"
                                                                action="{{ route('rfq-forms.destroy',$rfq->id) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                @method('delete')
                                                            </form>
                                                        </small>
                                                    </div>
                                                </div>
                                            </td> --}}
                                        @empty
                                            <td colspan="15" class="text-center">No RFQ Available yet!</td>
                                    @endforelse
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

@include('ticketing.message')
<script>
    $(document).ready(function() {
        $('#exampletable').DataTable( {
            "ordering": false,
            initComplete: function () {
                this.api().columns([1,2,3,6]).every( function (d) {//THis is used for specific column
                    var column = this;
                    var theadname = $('#exampletable th').eq([d]).text();
                    var select = $('<select class="form-control my-1"><option value="">'+theadname+': All</option></select>')
                    .appendTo( '#filtertickettable' )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                        $(this).val()
                        );

                        column
                        .search( val ? '^'+val+'$' : '', true, false )
                        .draw();
                    } );
                    column.data().unique().sort().each( function ( d, j ) {
                        var val = $('<div/>').html(d).text();
                        select.append( '<option value="'+val+'">'+val+'</option>' )
                    } );

                } );
            }
        } );
    } );
</script>

@endsection
