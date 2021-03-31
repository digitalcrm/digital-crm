@extends('layouts.adminlte-boot-4.user')
@section('title', "RFQ Lead")
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-4 mt-0">
                    <h1 class="m-0 text-dark">RFQ Leads <small class="badge badge-secondary">{{ count($rfq_leads) }}</small>
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
                        <div class="card-body p-0 table-responsive">
                            <table id="example2" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Contact Name</th>
                                        <th>Rfq</th>
                                        <th>Email</th>
                                        <th>Contact Number</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rfq_leads as $rfq_lead)
                                        <tr>
                                            <td>
                                                {{ $rfq_lead->contact_name }}
                                            </td>
                                            <td>
                                                {{-- <a href="{{ route('details.rfq',$rfq_lead->rfq->slug) }}" target="_blank">
                                                    {{ $rfq_lead->rfq->product_name }}
                                                </a> --}}
                                                <a href="{{ __('custom.rfq_quote_now').$rfq_lead->rfq->slug }}" rel="noopener noreferrer" target="_blank">
                                                    {{ $rfq_lead->rfq->product_name }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $rfq_lead->email }}
                                            </td>
                                            <td>
                                                {{ $rfq_lead->mobile_number }}
                                            </td>
                                            <td>
                                                {{ $rfq_lead->created_at->isoFormat('D/M/Y') }}
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
