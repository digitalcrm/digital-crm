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
					<h1 class="m-0 text-dark">Settings</h1>
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
				<div class="col-lg-6">
					<div class="card shadow card-primary card-outline">
						<div class="card-header with-border">
							<h3 class="card-title">
								Products &nbsp;
							</h3>
						</div>
						<div class="card-body p-0">
							<div class="row">
								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/productcategorys'); ?>" class="trash-link display-block">
												<span class=""><i class="nav-icon fas fa-user fa-2x"></i></span>
												<div class="text-bold mt-2">Product Category</div>
											</a>
										</div>
									</div>
								</div>

								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/productsubcategorys'); ?>" class="trash-link display-block">
												<span class=""><i class="nav-icon fas fa-user fa-2x"></i></span>
												<div class="text-bold mt-2">Product Sub Category</div>
											</a>
										</div>
									</div>
								</div>

								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/country'); ?>" class="trash-link display-block">
												<span class=""><i class="nav-icon fas fa-globe-americas fa-2x"></i></span>
												<div class="text-bold mt-2">Countries</div>
											</a>
										</div>
									</div>
								</div>

								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/states'); ?>" class="trash-link display-block">
												<span class=""><i class="fa fa-map fa-2x"></i></span>
												<div class="text-bold mt-2">States</div>
											</a>
										</div>
									</div>
								</div>

								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/currency'); ?>" class="trash-link display-block">
												<span class=""><i class="fa fa-money-bill-alt fa-2x"></i></span>
												<div class="text-bold mt-2">Currencies</div>
											</a>
										</div>
									</div>
								</div>

								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/units'); ?>" class="trash-link display-block">
												<span class=""><i class="fa fa-cog fa-2x"></i></span>
												<div class="text-bold mt-2">Units</div>
											</a>
										</div>
									</div>
								</div>

								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/leadsource'); ?>" class="trash-link display-block">
												<span class=""><i class="fas fa-chalkboard-teacher fa-2x"></i></span>
												<div class="text-bold mt-2">Lead Source</div>
											</a>
										</div>
									</div>
								</div>

								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/leadstatus'); ?>" class="trash-link display-block">
												<span class=""><i class="fa fa-battery-three-quarters fa-2x"></i></span>
												<div class="text-bold mt-2">Lead Status</div>
											</a>
										</div>
									</div>
								</div>

								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/dealstage'); ?>" class="trash-link display-block">
												<span class=""><i class="fa fa-cog fa-2x"></i></span>
												<div class="text-bold mt-2">Deal Stages</div>
											</a>
										</div>
									</div>
								</div>
								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/dealtypes'); ?>" class="trash-link display-block">
												<span class=""><i class="nav-icon fas fa-user fa-2x"></i></span>
												<div class="text-bold mt-2">Deal Types</div>
											</a>

										</div>
									</div>
								</div>

								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/paymentstatus'); ?>" class="trash-link display-block">
												<span class=""><i class="nav-icon fas fa-user fa-2x"></i></span>
												<div class="text-bold mt-2">Payment Status</div>
											</a>
										</div>
									</div>
								</div>


								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/accounttypes'); ?>" class="trash-link display-block">
												<span class=""><i class="nav-icon fas fa-user fa-2x"></i></span>
												<div class="text-bold mt-2">Account Types</div>
											</a>
										</div>
									</div>
								</div>

								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/industrytypes'); ?>" class="trash-link display-block">
												<span class=""><i class="nav-icon fas fa-industry fa-2x"></i></span>
												<div class="text-bold mt-2">Industry Types</div>
											</a>
										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="card shadow card-primary card-outline">
						<div class="card-header with-border">
							<h3 class="card-title">
								Users
							</h3>
						</div>
						<div class="card-body p-0">
							<div class="row">
								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/roles'); ?>" class="trash-link display-block">
												<span class=""><i class="nav-icon fas fa-user fa-2x"></i></span>
												<div class="text-bold mt-2">Roles</div>
											</a>
										</div>
									</div>
								</div>

								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/usertypes'); ?>" class="trash-link display-block">
												<span class=""><i class="nav-icon fas fa-user fa-2x"></i></span>
												<div class="text-bold mt-2">User Types</div>
											</a>
										</div>
									</div>
								</div>

								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/emails'); ?>" class="trash-link display-block">
												<span class=""><i class="fa fa-cog fa-2x"></i></span>
												<div class="text-bold mt-2">Email IDs</div>
											</a>
										</div>
									</div>
								</div>

								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/emailcategory'); ?>" class="trash-link display-block">
												<span class=""><i class="fa fa-cog fa-2x"></i></span>
												<div class="text-bold mt-2">Email Categories</div>
											</a>
										</div>
									</div>
								</div>

								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/emailtemplates'); ?>" class="trash-link display-block">
												<span class=""><i class="fa fa-cog fa-2x"></i></span>
												<div class="text-bold mt-2">Email Templates</div>
											</a>
										</div>
									</div>
								</div>


								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/projectstatus'); ?>" class="trash-link display-block">
												<span class=""><i class="nav-icon fas fa-user fa-2x"></i></span>
												<div class="text-bold mt-2">Project Status</div>
											</a>
										</div>
									</div>
								</div>

								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/rdtypes'); ?>" class="trash-link display-block">
												<span class=""><i class="nav-icon fas fa-user fa-2x"></i></span>
												<div class="text-bold mt-2">RD Types</div>
											</a>
										</div>
									</div>
								</div>

								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/rdprioritys'); ?>" class="trash-link display-block">
												<span class=""><i class="nav-icon fas fa-user fa-2x"></i></span>
												<div class="text-bold mt-2">RD Priority</div>
											</a>
										</div>
									</div>
								</div>

								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/rdtrends'); ?>" class="trash-link display-block">
												<span class=""><i class="nav-icon fas fa-user fa-2x"></i></span>
												<div class="text-bold mt-2">RD Trends</div>
											</a>
										</div>
									</div>
								</div>

								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/department'); ?>" class="trash-link display-block">
												<span class=""><i class="nav-icon fas fa-building fa-2x"></i></span>
												<div class="text-bold mt-2">Departments</div>
											</a>
										</div>
									</div>
								</div>



								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="<?php echo url('admin/filecategory'); ?>" class="trash-link display-block">
												<span class=""><i class="fa fa-cog fa-2x"></i></span>
												<div class="text-bold mt-2">File Categories</div>
											</a>
										</div>
									</div>
								</div>

								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="#" class="trash-link display-block">
												<span class=""><i class="fa fa-cog fa-2x"></i></span>
												<div class="text-bold mt-2">Cron Jobs</div>
											</a>
										</div>
									</div>
								</div>
								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="{{ route('outcomes.index') }}" class="trash-link display-block">
												<span class=""><i class="fa fa-cog fa-2x"></i></span>
												<div class="text-bold mt-2">Task Outcomes</div>
											</a>
										</div>
									</div>
								</div>
								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="{{ route('tasktypes.index') }}" class="trash-link display-block">
												<span class=""><i class="fa fa-cog fa-2x"></i></span>
												<div class="text-bold mt-2">Task Types</div>
											</a>
										</div>
									</div>
								</div>
								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="{{ route('ticket.settings') }}" class="trash-link display-block">
												<span class=""><i class="fa fa-cog fa-2x"></i></span>
												<div class="text-bold mt-2">Ticketing</div>
											</a>
										</div>
									</div>
								</div>
								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="{{ route('bookservices.index') }}" class="trash-link display-block">
												<span class=""><i class="fa fa-cog fa-2x"></i></span>
												<div class="text-bold mt-2">Booking Service</div>
											</a>
										</div>
									</div>
								</div>
								<div class="col-lg-2">
									<div class="card shadow card-primary card-outline">
										<!--/.card-header-->
										<div class="card-body">
											<a href="{{ route('activity_type.index') }}" class="trash-link display-block">
												<span class=""><i class="fa fa-cog fa-2x"></i></span>
												<div class="text-bold mt-2">Booking Activities</div>
											</a>
										</div>
									</div>
								</div>

							</div>
						</div>

					</div>
				</div>



				{{-- <div class="col-lg-2">
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
</div> --}}


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

{{-- <script>
	$(function() {
		$(".sidebar-menu li").removeClass("active");
		$("#lisettings").addClass("active");

	});
</script> --}}
@endsection