@extends('layouts.adminlte-boot-4.user')
@section('title', "RFQ's")
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-0">
                    <div class="col-sm-4 mt-0">
                        <h1 class="m-0 text-dark">My RFQ's <small class="badge badge-secondary">{{ count($rfqs) }}</small>
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
                                        {{-- <a href="#comingSoon" class="btn btn-primary">My Inquiries</a> --}}
                                    </div>
                                    <div class="col-md-4 text-right">
                                        <a href="{{ route('rfq-leads.index') }}" class="btn btn-primary">RFQ Leads</a>
                                        <a href="{{ route('rfq-forms.create') }}" class="btn btn-primary">Add New</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0 table-responsive">

                                <table id="example2" class="table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Prduct Category</th>
                                            <th>Prduct SubCategory</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th>Document Type</th>
                                            <th>Inquiries</th>
                                            <th>Active</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($rfqs as $rfq)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('details.rfq', $rfq->slug) }}" rel="noopener noreferrer" target="_blank">
                                                        {{ $rfq->product_name }}
                                                    </a>
                                                    {{-- <a href="{{ __('custom.rfq_quote_now').$rfq->slug }}" rel="noopener noreferrer" target="_blank">
                                                        {{ $rfq->product_name }}
                                                    </a> --}}
                                                </td>
                                                <td>
                                                    {{ $rfq->tbl_category->category ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $rfq->tbl_sub_category->category ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $rfq->product_quantity }}{{ $rfq->unit->name ?? '' }}
                                                </td>
                                                <td>
                                                    {{ $rfq->purchase_price }}
                                                </td>
                                                <td>
                                                    @foreach ($rfq->images as $doc)
                                                        {{ $doc->file_name }}
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <a href="{{ route('rfq-leads.index',['name' => $rfq->slug]) }}">
                                                        {{ $rfq->rfqLeads->count() }}
                                                    </a>
                                                </td>
                                                <td>
                                                    {{-- <livewire:rfq.is-active :rfq="$rfq" /> --}}
                                                    @livewire('toggle-button', [
                                                    'model' => $rfq,
                                                    'field' => 'isActive',
                                                    ])
                                                <td>
                                                    {{ $rfq->created_at->isoFormat('D/MM/Y') }}
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle"
                                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                                                                    action="{{ route('rfq-forms.destroy', $rfq->slug) }}"
                                                                    enctype="multipart/form-data">
                                                                    @csrf
                                                                    @method('delete')
                                                                </form>
                                                            </small>
                                                        </div>
                                                    </div>
                                                </td>
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


@endsection
