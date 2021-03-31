@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->

	<!-- Main content -->

	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-0">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Ticket Settings</h1>
				</div>
			</div>
			<!-- /.row -->
		</div>
		<!-- /.container-fluid -->
	</div>
	<section class="content mt-2 mx-0">
		<div class="container-fluid">
			<!-- Small cardes (Stat card) -->
			<div class="row text-center">
				<div class="col-lg-2">
					<div class="card shadow card-primary card-outline">
						<!--/.card-header-->
						<div class="card-body">
							<a href="{{ route('tickettype.index') }}" class="trash-link display-block">
								<span class=""><i class="fa fa-cog fa-2x"></i></span>
								<div class="text-bold mt-2">Ticket Types</div>
							</a>
						</div>
					</div>
				</div>
				<div class="col-lg-2">
					<div class="card shadow card-primary card-outline">
						<!--/.card-header-->
						<div class="card-body">
							<a href="{{ route('ticketstatus.index') }}" class="trash-link display-block">
								<span class=""><i class="fa fa-cog fa-2x"></i></span>
								<div class="text-bold mt-2">Ticket Status</div>
							</a>
						</div>
					</div>
				</div>
				<div class="col-lg-2">
					<div class="card shadow card-primary card-outline">
						<!--/.card-header-->
						<div class="card-body">
							<a href="{{ route('priorities.index') }}" class="trash-link display-block">
								<span class=""><i class="fa fa-cog fa-2x"></i></span>
								<div class="text-bold mt-2">Priorities</div>
							</a>
						</div>
					</div>
				</div>


			</div>

		</div>
		<!-- /.card-body -->
</div>
<!-- /.card -->
</div>
</div>
<!-- /.row -->
</div>
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
