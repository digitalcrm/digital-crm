@extends('layouts.adminlte-boot-4.user')
@section('title', "RFQ Lead")
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-4 mt-0">
                    <h1 class="m-0 text-dark">{{ $rfq_form->product_name }}</h1>

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
                        <div class="card-body p-0 table-responsive">
                            <table id="example2" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Contact Name</th>
                                        <th>Mobile Number</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rfq_form->rfqLeads as $rfq_lead)
                                        <tr>
                                            <td>
                                                {{ $rfq_lead->contact_name }}
                                            </td>
                                            <td>
                                                {{ $rfq_lead->mobile_number }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="15" class="text-center">No RFQ Leads Available!</td>
                                        </tr>
                                    @endforelse
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
