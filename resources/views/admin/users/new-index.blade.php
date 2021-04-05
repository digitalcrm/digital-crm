@extends('layouts.adminlte-boot-4.admin')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-0">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">Users <span class="badge badge-secondary">{!! $data['total'] !!}</span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content mt-2 mx-0">
            <div class="container-fluid">
                <!-- Small cardes (Stat card) -->
                <div class="row">
                    <div class="col-lg-12 p-0">
                        @if (session('success'))
                            <div class='alert alert-success'>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class='alert alert-success'>
                                {{ session('error') }}
                            </div>
                        @endif
                        <div class="card shadow card-primary card-outline">
                            <div class="card-body p-0">
                                <table id="usersTable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Job Title</th>
                                            <th>Status</th>
                                            <th>Email Verification</th>
                                            <th>Created</th>
                                            <th>Action</th>
                                            <th>Set Features</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($users as $userdetails)

                                        @empty

                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.card -->
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
    {{-- <script>
    $(function() {
        // $(".sidebar-menu li").removeClass("active");
//    $("#ulusers").addClass('menu-open');
//    $("#ulusers ul").css('display', 'block');
        // $("#liusers").addClass("active");
    });
</script> --}}
@endsection
