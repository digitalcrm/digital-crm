@extends('layouts.adminlte-boot-4.admin')
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<div class="content-header">
		<div class="container-fluid">
			<div class="row mb-0">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark">Trash Files</h1>
				</div>
			</div>
			<!-- /.row -->
		</div>
		<!-- /.container-fluid -->
	</div>
	<!-- Main content -->
	<section class="content mt-2 mx-0">
		<div class="container-fluid">

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


					<div class="row text-center">
						<div class="col-lg-2">
							<div class="card shadow card-primary card-outline">
								<!--/.card-header-->
								<div class="card-body">
									<a href="<?php echo url('admin/trash/forms'); ?>" class="trash-link display-block">
										<i class="nav-icon fas fa-file fa-2x"></i>
										<div class="text-bold mt-2">Web to Lead - Forms</div>
									</a>

								</div>
							</div>
						</div>

						<div class="col-lg-2">
							<div class="card shadow card-primary card-outline">
								<!--/.card-header-->
								<div class="card-body">
									<a href="<?php echo url('admin/trash/formleads'); ?>" class="trash-link display-block">
										<span class="trash-link display-block"><i class="nav-icon fas fa-file-alt fa-2x"></i></span>
										<div class="text-bold mt-2">Web to Lead - Form Leads</div>
									</a>

								</div>
							</div>
						</div>

						<div class="col-lg-2">
							<div class="card shadow card-primary card-outline">
								<!--/.card-header-->
								<div class="card-body">
									<a href="<?php echo url('admin/trash/leads'); ?>" class="trash-link display-block">
										<span class=""><i class="nav-icon fas fa-paper-plane fa-2x"></i></span>
										<div class="text-bold mt-2">Leads</div>
									</a>

								</div>
							</div>
						</div>

						<div class="col-lg-2">
							<div class="card shadow card-primary card-outline">
								<!--/.card-header-->
								<div class="card-body">
									<a href="<?php echo url('admin/trash/productleads'); ?>" class="trash-link display-block">
										<span class=""><i class="nav-icon fas fa-paper-plane fa-2x"></i></span>
										<div class="text-bold mt-2">Product Leads</div>
									</a>
								</div>
							</div>
						</div>

						<div class="col-lg-2">
							<div class="card shadow card-primary card-outline">
								<!--/.card-header-->
								<div class="card-body">
									<a href="<?php echo url('admin/trash/accounts'); ?>" class="trash-link display-block">
										<span class=""><i class="nav-icon fas fa-user fa-2x"></i></span>
										<div class="text-bold mt-2">Accounts</div>
									</a>

								</div>
							</div>
						</div>

						<div class="col-lg-2">
							<div class="card shadow card-primary card-outline">
								<!--/.card-header-->
								<div class="card-body">
									<a href="<?php echo url('admin/trash/contacts'); ?>" class="trash-link display-block">
										<span class=""><i class="nav-icon fas fa-phone fa-2x"></i></span>
										<div class="text-bold mt-2">Contacts</div>
									</a>

								</div>
							</div>
						</div>

						<div class="col-lg-2">
							<div class="card shadow card-primary card-outline">
								<!--/.card-header-->
								<div class="card-body">
									<a href="<?php echo url('admin/trash/deals'); ?>" class="trash-link display-block">
										<span class=""><i class="nav-icon fas fa-briefcase fa-2x"></i></span>
										<div class="text-bold mt-2">Deals</div>
									</a>

								</div>
							</div>
						</div>

						<div class="col-lg-2">
							<div class="card shadow card-primary card-outline">
								<!--/.card-header-->
								<div class="card-body">
									<a href="<?php echo url('admin/trash/products'); ?>" class="trash-link display-block">
										<span class=""><i class="nav-icon fab fa-product-hunt"></i></span>
										<div class="text-bold mt-2">Products</div>
									</a>

								</div>
							</div>
						</div>

						<div class="col-lg-2">
							<div class="card shadow card-primary card-outline">
								<!--/.card-header-->
								<div class="card-body">
									<a href="<?php echo url('admin/trash/documents'); ?>" class="trash-link display-block">
										<span class=""><i class="nav-icon fas fa-file-word fa-2x"></i></span>
										<div class="text-bold mt-2">Documents</div>
									</a>

								</div>
							</div>
						</div>

						<div class="col-lg-2">
							<div class="card shadow card-primary card-outline">
								<!--/.card-header-->
								<div class="card-body">
									<a href="<?php echo url('admin/trash/invoices'); ?>" class="trash-link display-block">
										<span class=""><i class="nav-icon fas fa-money-bill fa-2x"></i></span>
										<div class="text-bold mt-2">Invoices</div>
									</a>

								</div>
							</div>
						</div>

						<div class="col-lg-2">
							<div class="card shadow card-primary card-outline">
								<!--/.card-header-->
								<div class="card-body">
									<a href="<?php echo url('admin/trash/territory'); ?>" class="trash-link display-block">
										<span class=""><i class="nav-icon fas fa-map-marked fa-2x"></i></span>
										<div class="text-bold mt-2">Territory</div>
									</a>

								</div>
							</div>
						</div>

						<div class="col-lg-2">
							<div class="card shadow card-primary card-outline">
								<!--/.card-header-->
								<div class="card-body">
									<a href="<?php echo url('admin/trash/events'); ?>" class="trash-link display-block">
										<span class=""><i class="nav-icon fas fa-calendar-alt fa-2x"></i></span>
										<div class="text-bold mt-2">Events</div>
									</a>

								</div>
							</div>
						</div>
						
						<div class="col-lg-2">
							<div class="card shadow card-primary card-outline">
								<!--/.card-header-->
								<div class="card-body">
									<a href="<?php echo url('admin/trash/currency'); ?>" class="trash-link display-block">
										<span class=""><i class="nav-icon fas fa-money-bill-alt fa-2x"></i></span>
										<div class="text-bold mt-2">Currency</div>
									</a>

								</div>
							</div>
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

<script>
	$(function() {
		$(".sidebar-menu li").removeClass("active");
		$("#litrash").addClass("active");
	});
</script>
@endsection