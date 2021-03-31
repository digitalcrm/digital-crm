<?php
// echo json_encode($companys);
// exit();
?>
<div>
    <div class="">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-4">
                        <select class="form-control" wire:model="uid">
                            {!! $useroptions !!}
                        </select>
                    </div>
                    <div class="col-lg-8">
                        <input type="text" class="form-control" placeholder="Search" wire:model="searchTerm" />
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <table class="table table-striped table-bordered" style="margin: 10px 0 10px 0;">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Whatsapp</th>
                        <th>Account Type</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                    @foreach($companys as $company)
                    <tr>
                        <td>
                            {{ $company->c_name }}
                        </td>
                        <td>
                            {{ $company->c_email }}
                        </td>
                        <td>
                            {{ $company->c_mobileNum }}
                        </td>
                        <td>
                            {{ $company->c_whatsappNum }}
                        </td>
                        <td>
                            {{ ($company->tbl_accounttypes != '')?$company->tbl_accounttypes->type:'' }}
                        </td>
                        <td>
                            {{ date('d-m-Y',strtotime($company->created_at)) }}
                        </td>
                        <td>
                            <!-- <button wire:click="edit({{ $company->id }})" class="btn btn-primary btn-sm">Edit</button>
                            <button wire:click="delete({{ $company->id }})" class="btn btn-danger btn-sm">Delete</button> -->

                            <!-- //  url('admin/companies/' . $company->id . '/edit')route(company.edit,$company->id) -->
                            <a class="btn btn-warning" href="{{ url('admin/companies/' . $company->slug . '/edit') }}">Edit</a>
                            <!-- <a class="btn btn-danger" href="{{url('admin/companies/delete/' . $company->id)}}">Delete</a> -->
                        </td>
                    </tr>
                    @endforeach
                </table>
                {{ $companys->links("pagination::bootstrap-4") }}
            </div>
        </div>
    </div>
</div>