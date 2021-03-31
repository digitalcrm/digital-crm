@extends('layouts.adminlte-boot-4.user')
@section('content')



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Sub User</h1>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <section class="content mt-2 mx-0">
        <div class="container-fluid">
            <!-- Main row -->
            <div class="row">
                <div class="col-lg-3">
                    <div class="card shadow card-primary card-outline">
                        <div class="card-body card-profile p-0">
                            <div class="card-header no-border text-center">
							<img class="profile-user-img img-responsive img-circle" src="<?php echo ($data['user']->picture != '') ? url($data['user']->picture) : url('uploads/default/user.png'); ?>" alt="<?php echo $data['user']->name; ?>">
							</div>
                            <h3 class="profile-username text-center"><?php echo $data['user']->name; ?></h3>
                            <p class="text-muted text-center"><?php echo $data['user']->jobtitle; ?></p>

                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>E-Mail</b> <a class="pull-right"><?php echo $data['user']->email; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Mobile</b> <a class="pull-right"><?php echo $data['user']->mobile; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Currency</b> <a class="pull-right"><?php echo $data['currency']->html_code . ' ' . $data['currency']->name; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Newsletter</b> <a class="pull-right"><?php echo ($data['user']->newsletter == 1) ? 'Yes' : 'No'; ?></a>
                                </li>
                                <li class="list-group-item">
                                    <b>Daily Reports</b> <a class="pull-right"><?php echo ($data['user']->daily_reports == 1) ? 'Yes' : 'No'; ?></a>
                                </li>
                            </ul>


                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <div class="col-lg-9">
                    
		<div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white card-primary card-outline" style="height:105px;">
			  <div class="inner">
			  <a href="<?php echo url('subusers/formleads/' . $data['user']->id); ?>">
                <h3><?php echo ($data['user']['tbl_formleads'] != '') ? count($data['user']['tbl_formleads']) : 0; ?></h3>
                <p>Web to Lead</p>
              </div>
              <div class="icon">
                <i class="fas fa-copy text-purple"></i>
              </div>
			  </a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white card-primary card-outline" style="height:105px;">
			  <div class="inner">
			  <a href="<?php echo url('subusers/forms/' . $data['user']->id); ?>">
                <h3><?php echo ($data['user']['tbl_forms'] != '') ? count($data['user']['tbl_forms']) : 0; ?></h3>
                <p>Forms</p>
              </div>
              <div class="icon">
                <i class="fas fa-file-invoice text-purple"></i>
              </div>
			  </a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white card-primary card-outline" style="height:105px;">
			  <div class="inner">
			  <a href="<?php echo url('subusers/leads/' . $data['user']->id); ?>">
                <h3><?php echo ($data['user']['tbl_leads'] != '') ? count($data['user']['tbl_leads']) : 0; ?></h3>

                <p>Leads</p>
              </div>
              <div class="icon">
                <i class="fas fa-bullhorn text-purple"></i>
              </div>
			 </a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white card-primary card-outline" style="height:105px;">
              <div class="inner">
			  <a href="<?php echo url('subusers/accounts/' . $data['user']->id); ?>">
                <h3><?php echo ($data['user']['tbl_accounts'] != '') ? count($data['user']['tbl_accounts']) : 0; ?></h3>

                <p>Accounts</p>
              </div>
              <div class="icon">
                <i class="fas fa-user text-purple"></i>
              </div>
			  </a>
            </div>
          </div>
          <!-- ./col -->
        </div>
			
		
		<div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white card-primary card-outline" style="height:105px;">
			  <div class="inner">
			  <a href="<?php echo url('subusers/contacts/' . $data['user']->id); ?>">
                <h3><?php echo ($data['user']['tbl_contacts'] != '') ? count($data['user']['tbl_contacts']) : 0; ?></h3>
                <p>Contacts</p>
              </div>
              <div class="icon">
                <i class="fas fa-phone text-purple"></i>
              </div>
			  </a>
            </div>
          </div>
		  
									
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white card-primary card-outline" style="height:105px;">
			  <div class="inner">
			  <a href="<?php echo url('subusers/deals/' . $data['user']->id); ?>">
                <h3><?php echo ($data['user']['tbl_deals'] != '') ? count($data['user']['tbl_deals']) : 0; ?></h3>
                <p>Deals</p>
              </div>
              <div class="icon">
                <i class="fas fa-suitcase text-purple"></i>
              </div>
			  </a>
            </div>
          </div>
		  
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white card-primary card-outline" style="height:105px;">
			  <div class="inner">
			  <a href="<?php echo url('subusers/customers/' . $data['user']->id); ?>">
                <h3><?php echo ($data['customers'] != '') ? $data['customers'] : 0; ?></h3>

                <p>Customers</p>
              </div>
              <div class="icon">
                <i class="fas fa-user-circle text-purple"></i>
              </div>
			 </a>
            </div>
          </div>
		  
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white card-primary card-outline" style="height:105px;">
              <div class="inner">
			  <a href="<?php echo url('subusers/sales/' . $data['user']->id); ?>">
                <h3><?php echo ($data['sales'] != '') ? '<span>' . $data['currency']->html_code . '</span>&nbsp;' . $data['sales'] : 0; ?></h3>
                <p>Sales</p>
              </div>
              <div class="icon">
                <i class="fas fa-cart-plus text-purple"></i>
              </div>
			  </a>
            </div>
          </div>
          <!-- ./col -->
        </div>

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
                <div class='alert alert-success'>
                    {{session('error')}}
                </div>
                @endif
                <!-- general form elements -->



                <!-- /.card -->

            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-10">

            </section>
            <!-- right col -->
        </div>
        <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    $(function() {
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        $("#ulaccounts").addClass('menu-open');
        $("#ulaccounts ul").css('display', 'block');
        // $("#licreatelead").addClass("active");
    });


</script>
@endsection