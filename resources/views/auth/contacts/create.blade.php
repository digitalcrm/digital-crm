@extends('layouts.adminlte-boot-4.user')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <h1><i class="far fa-edit"></i> New Contact</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content mx-0">
        <div class="container-fluid">
            <!-- Small cardes (Stat card) -->
            <div class="row">
                <div class="col-lg-6">
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
                    <!-- general form elements -->
                    <div class="card shadow card-primary card-outline">
                        <!-- /.card-header -->
                        
						
						
						
						{{Form::open(['action'=>'ContactController@store','method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="card-body">
						
									
<!-- <div class="row">
<div class="col-md-6">
<div class="row">
  <div class="wrap-input100 input-group-prepend col-md-2 col-sm-2">
    <div class="form-element form-select">
	<select class="input100" id="salutation" name="salutation" class="selectpicker form-control">{!!$data['salutationoptions']!!}</select>
	</div>
  </div>

  <div class="wrap-input100 col-md-10 col-sm-10">
    <input class="input100" type="text" name="first_name" id="first_name" value="{{old('first_name')}}" required tabindex="1">
	<span class="focus-input100" data-placeholder="First Name *"></span>
	<span class="text-danger small">{{ $errors->first('first_name') }}</span>
</div>
</div>
</div>

<div class="col-md-6 wrap-input100">
	<input class="input100" type="text" name="last_name" id="last_name"  value="{{old('last_name')}}" tabindex="2">
	<span class="focus-input100" data-placeholder="Last name"></span>
    <span class="text-danger small">{{ $errors->first('last_name') }}</span>
	
</div>
</div>

<div class="row">
<div class="wrap-input100 col-md-6">
    <input class="input100" type="email" name="email" id="email" value="{{old('email')}}" required tabindex="3">
	<span class="focus-input100" data-placeholder="Email *"></span>
    <span class="text-danger small">{{ $errors->first('email') }}</span>
</div>

<div class="wrap-input100 col-md-6">
    <input placeholder=" " class="input100" type="text" name="mobile" id="mobile" value="{{old('mobile')}}" tabindex="4">
	<span class="focus-input100" data-placeholder="Mobile"></span>
    <span class="text-danger small">{{ $errors->first('mobile') }}</span>
</div>
</div>

<div class="row">
<div class="wrap-input100 col-md-6">
    <input placeholder=" " class="input100" type="text" name="phone" id="phone" value="{{old('phone')}}" tabindex="5">
	<span class="focus-input100" data-placeholder="Phone"></span>
    <span class="text-danger small">{{ $errors->first('phone') }}</span>
</div>

<div class="wrap-input100 col-md-6">
    <input placeholder=" " class="input100" type="text" name="designation" id="designation" value="{{old('designation')}}" tabindex="6">
	<span class="focus-input100" data-placeholder="Designation"></span>
    <span class="text-danger small">{{ $errors->first('designation') }}</span>
</div>
</div>
										
<div class="row">
<div class="wrap-input100 col-md-6 pl-0">
    <select  name="leadsource" id="leadsource" class="input100" tabindex="7">
    {!!$data['leadsourceoptions']!!} 
	</select>
	<span class="focus-input100" data-placeholder=" "></span>
    <span class="text-danger small">{{ $errors->first('leadsource') }}</span>
</div>

<div class="wrap-input100 col-md-6 pl-0">
    <select class="input100" name="account" id="account" tabindex="8">
    {!!$data['accountoptions']!!}
    </select>
	<span class="focus-input100" data-placeholder=" "></span>
    <span class="text-danger small">{{ $errors->first('account') }}</span>
	<input placeholder="New Company" class="form-control" type="text" name="addAccount" id="addAccount" style="display: none;" />
    <span class="text-danger small">{{ $errors->first('addAccount') }}</span>
</div>
</div>

<div class="row">
<div class="wrap-input100 col-md-12">
    <input class="input100" type="url" name="website" id="website"  value="{{old('website')}}" tabindex="9">
    <span class="focus-input100" data-placeholder="Website URL with http://"></span>
	<span class="text-danger small">{{ $errors->first('website') }}</span>
</div>

<div class="wrap-input100">
    <textarea class="input100" name="notes" id="notes" rows="5" tabindex="10"></textarea>
    <span class="focus-input100" data-placeholder="Notes"></span>
	<span class="text-danger small">{{ $errors->first('notes') }}</span>
</div>

<div class="wrap-input100 mb-4">
    <input placeholder=" " class="input100" type="file" class="btn btn-default" name="userpicture" id="userpicture"  tabindex="11"/>
    <span class="focus-input100"></span>
	<span class="text-danger small">{{ $errors->first('userpicture') }}</span>
	
</div>


<div class="custom-control custom-control2 custom-checkbox">
    <input type="checkbox" class="custom-control-input" name="addtoLead" id="customCheck1" tabindex="12">
    <label class="custom-control-label custom-control-label2 text-theme" for="customCheck1">Add to Lead</label>
</div>
</div>

<div class="row">
<div class="col-md-12 mt-4 mb-3 pl-0">
<div class="form-checkbox-legend">Social Networks</div>
</div>
<div class="wrap-input100 col-md-6">
    <input placeholder=" " class="input100" type="text" name="facebook_id" id="facebook_id" value="{{old('facebook_id')}}" tabindex="13">
	<span class="focus-input100" data-placeholder="Facebooke"></span>
</div>

<div class="wrap-input100 col-md-6">
    <input placeholder=" " class="input100" type="text" name="twitter_id" id="twitter_id" value="{{old('twitter_id')}}" tabindex="14">
	<span class="focus-input100" data-placeholder="Twitter"></span>
</div>

<div class="wrap-input100 col-md-6">
    <input placeholder=" " class="input100" type="text" name="linkedin_id" id="linkedin_id" value="{{old('linkedin_id')}}" tabindex="15">
	<span class="focus-input100" data-placeholder="LinkedIn"></span>
</div>							

<div class="wrap-input100 col-md-6">
    <input placeholder=" " class="input100" type="text" name="google_id" id="google_id" value="{{old('google_id')}}" tabindex="16">
	<span class="focus-input100" data-placeholder="Google"></span>
</div>
</div>


<div class="row">
<div class="col-md-12 mb-3 pl-0">
<div class="form-checkbox-legend">Address Information</div>
</div>
<div class="wrap-input100 col-md-6">
    <input placeholder=" " class="input100" type="text" name="city" id="city" value="{{old('city')}}" tabindex="17">
	<span class="focus-input100" data-placeholder="City"></span>
</div>

<div class="wrap-input100 col-md-6 pl-0">
    <select placeholder=" " class="input100" name="country" id="country" tabindex="18">{!!$data['countryoptions']!!}</select>
	<span class="focus-input100"></span>
</div>

<div class="wrap-input100 col-md-6 pl-0">
    <select placeholder=" "  class="input100" name="state" id="state" tabindex="19">
    <option value="0">Select State</option>
    </select>
	<span class="focus-input100"></span>
</div>

<div class="wrap-input100 col-md-6">
    <input placeholder=" " class="input100" type="text" name="zip" id="zip" value="{{old('zip')}}" tabindex="20">
	<span class="focus-input100" data-placeholder="Zip"></span>
</div>
</div>-->
								
<!-- OLD FORM -->
  <div class="row">
    <div class="col-12">
        
               	
		
		
		
		<div class="form-group row">
          <label for="first_name" class="col-md-3 col-form-label text-right">First Name</label>
          <div class="col-md-9">
            <div class="input-group-prepend">
					<select id="salutation" name="salutation" class="selectpicker form-control">
						{!!$data['salutationoptions']!!}
					</select>
					
					<input type="text" class="form-control required" name="first_name" id="first_name" placeholder="" value="{{old('first_name')}}" required tabindex="1">
					<span class="text-danger small">{{ $errors->first('first_name') }}</span>
            </div>
          </div>
        </div>
    </div>
  </div>
  
<div class="row">
    <div class="col-12">
        <div class="form-group row">
          <label for="last_name" class="col-md-3 col-form-label text-right">Last Name</label>
          <div class="col-md-9">
            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="" value="{{old('last_name')}}" tabindex="1">
            <span class="text-danger small">{{ $errors->first('last_name') }}</span>
          </div>
        </div>
	</div>
</div>

<div class="row">
    <div class="col-12">
        <div class="form-group row">
          <label for="Email" class="col-md-3 col-form-label text-right">Email</label>
          <div class="col-md-9">
            <input type="email" class="form-control required" name="email" id="email" placeholder="" value="{{old('email')}}" required tabindex="3">
            <span class="text-danger small">{{ $errors->first('email') }}</span>
          </div>
        </div>
	</div>
</div>

<div class="row">
    <div class="col-12">
        <div class="form-group row">
          <label for="mobile" class="col-md-3 col-form-label text-right">Mobile</label>
          <div class="col-md-9">
            <input type="text" class="form-control" name="mobile" id="mobile" placeholder="" value="{{old('mobile')}}">
            <span class="text-danger small">{{ $errors->first('mobile') }}</span>
          </div>
        </div>
	</div>
</div>

<div class="row">
    <div class="col-12">
        <div class="form-group row">
          <label for="phone" class="col-md-3 col-form-label text-right">Phone</label>
          <div class="col-md-9">
            <input type="text" class="form-control" name="phone" id="phone" placeholder="" value="{{old('phone')}}">
            <span class="text-danger small">{{ $errors->first('phone') }}</span>
          </div>
        </div>
	</div>
</div>

<div class="row">
    <div class="col-12">
        <div class="form-group row">
          <label for="designation" class="col-md-3 col-form-label text-right">Designation</label>
          <div class="col-md-9">
            <input type="text" class="form-control" name="designation" id="designation" placeholder="" value="{{old('designation')}}">
            <span class="text-danger small">{{ $errors->first('designation') }}</span>
          </div>
        </div>
	</div>
</div>

<div class="row">
    <div class="col-12">
        <div class="form-group row">
          <label for="leadsource" class="col-md-3 col-form-label text-right">Lead Source</label>
          <div class="col-md-9">
            <select class="form-control" name="leadsource" id="leadsource">
                {!!$data['leadsourceoptions']!!}
            </select>
            <span class="text-danger small">{{ $errors->first('leadsource') }}</span>
          </div>
        </div>
	</div>
</div>

<div class="row">
    <div class="col-12">
        <div class="row">
          <label for="account" class="col-md-3 col-form-label text-right">Account</label>
          <div class="col-md-9">
            <select class="form-control" name="account" id="account">
                {!!$data['accountoptions']!!}
            </select>
            <span class="text-danger small">{{ $errors->first('account') }}</span>
            <br>
            <input class="form-control" type="text" name="addAccount" id="addAccount" style="display: none;" />
            <span class="text-danger small">{{ $errors->first('addAccount') }}</span>
          </div>
        </div>
	</div>
</div>

<div class="row">
    <div class="col-12">
        <div class="form-group row">
          <label for="userpicture" class="col-md-3 col-form-label text-right">Profile Picture</label>
          <div class="col-md-9">
            <input type="file" class="btn btn-default" name="userpicture" id="userpicture" />
            <span class="text-danger small">{{ $errors->first('userpicture') }}</span>
          </div>
        </div>
	</div>
</div>

<div class="row">
    <div class="col-12">
        <div class="form-group row">
          <label for="website" class="col-md-3 col-form-label text-right">Website</label>
          <div class="col-md-9">
            <input type="url" class="form-control" name="website" id="website" placeholder="" value="{{old('website')}}">
            <span class="text-danger small">{{ $errors->first('website') }}</span>
          </div>
        </div>
	</div>
</div>

<div class="row">
    <div class="col-12">
        <div class="form-group row">
          <label for="notes" class="col-md-3 col-form-label text-right">Notes</label>
          <div class="col-md-9">
            <textarea name="notes" id="notes" class="form-control" rows="5"></textarea>
            <span class="text-danger small">{{ $errors->first('notes') }}</span>
          </div>
        </div>
	</div>
</div>

<div class="row">
    <div class="col-12">
        <div class="form-group row">
          <label for="notes" class="col-md-3 col-form-label text-right"></label>
          <div class="col-md-9">
            <div class="custom-control custom-checkbox">
				<input type="checkbox" class="custom-control-input" name="addtoLead" id="customCheck1">
				<label class="custom-control-label" for="customCheck1">Add to Lead</label>
			</div>
          </div>
        </div>
	</div>
</div>

									<div class="row">
									<div class="col-md-3"></div>
									<div class="col-md-9">
                                    <h3 class="badge badge-info">Social</h3>
									</div>
									</div>

<div class="row">
    <div class="col-12">
        <div class="form-group row">
          <label for="facebook_id" class="col-md-3 col-form-label text-right">Facebok</label>
          <div class="col-md-9">
            <input type="text" class="form-control" name="facebook_id" id="facebook_id" placeholder="" value="{{old('facebook_id')}}">
          </div>
        </div>
	</div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group row">
          <label for="twitter_id" class="col-md-3 col-form-label text-right">Twitter</label>
          <div class="col-md-9">
            <input type="text" class="form-control" name="twitter_id" id="twitter_id" placeholder="" value="{{old('twitter_id')}}">
          </div>
        </div>
	</div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group row">
          <label for="linkedin_id" class="col-md-3 col-form-label text-right">Linkedin</label>
          <div class="col-md-9">
            <input type="text" class="form-control" name="linkedin_id" id="linkedin_id" placeholder="" value="{{old('linkedin_id')}}">
          </div>
        </div>
	</div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group row">
          <label for="google_id" class="col-md-3 col-form-label text-right">Google</label>
          <div class="col-md-9">
            <input type="text" class="form-control" name="google_id" id="google_id" placeholder="" value="{{old('google_id')}}">
          </div>
        </div>
	</div>
</div>

									<div class="row">
									<div class="col-md-3"></div>
									<div class="col-md-9">
                                    <h3 class="badge badge-info">Address</h3>
									</div>
									</div>
									
<div class="row">
    <div class="col-12">
        <div class="form-group row">
          <label for="city" class="col-md-3 col-form-label text-right">City</label>
          <div class="col-md-9">
            <input type="text" class="form-control" name="city" id="city" placeholder="" value="{{old('city')}}">
          </div>
        </div>
	</div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group row">
          <label for="country" class="col-md-3 col-form-label text-right">Country</label>
          <div class="col-md-9">
            <select class="form-control" name="country" id="country">
                {!!$data['countryoptions']!!}
            </select>
          </div>
        </div>
	</div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group row">
          <label for="state" class="col-md-3 col-form-label text-right">State</label>
          <div class="col-md-9">
            <select class="form-control" name="state" id="state">
                <option value="0">Select State</option>
            </select>
          </div>
        </div>
	</div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group row">
          <label for="zip" class="col-md-3 col-form-label text-right">ZIP</label>
          <div class="col-md-9">
            <input type="text" class="form-control" name="zip" id="zip" placeholder="" value="{{old('zip')}}">
          </div>
        </div>
	</div>
</div>
							<!-- <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="first_name">First Name</label>&nbsp;<i class="fa fa-asterisk text-danger small"></i>
                                        <div class="input-group my-group">
                                            <select id="salutation" name="salutation" class="selectpicker form-control">
                                                {!!$data['salutationoptions']!!}
                                            </select>
                                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="" value="{{old('first_name')}}" required tabindex="1">
                                        </div>
                                        <span class="text-danger small">{{ $errors->first('first_name') }}</span>
                                    </div>


                                    <div class="form-group">
                                        <label for="mobile">Mobile</label>
                                        <input type="text" class="form-control" name="mobile" id="mobile" placeholder="" value="{{old('mobile')}}">
                                        <span class="text-danger small">{{ $errors->first('mobile') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="leadsource">Lead Source</label>
                                        <select class="form-control" name="leadsource" id="leadsource">
                                            {!!$data['leadsourceoptions']!!}
                                        </select>
                                        <span class="text-danger small">{{ $errors->first('leadsource') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="5"></textarea>
                                        <span class="text-danger small">{{ $errors->first('notes') }}</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="" value="{{old('last_name')}}" tabindex="1">
                                        <span class="text-danger small">{{ $errors->first('last_name') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" name="phone" id="phone" placeholder="" value="{{old('phone')}}">
                                        <span class="text-danger small">{{ $errors->first('phone') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="account">Account</label>
                                        <select class="form-control" name="account" id="account">
                                            {!!$data['accountoptions']!!}
                                        </select>
                                        <span class="text-danger small">{{ $errors->first('account') }}</span>
                                        <br>
                                        <input class="form-control" type="text" name="addAccount" id="addAccount" style="display: none;" />
                                        <span class="text-danger small">{{ $errors->first('addAccount') }}</span>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="addtoLead" id="customCheck1">
                                        <label class="custom-control-label" for="customCheck1">Add to Lead</label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="email">Email</label>&nbsp;<i class="fa fa-asterisk text-danger small"></i>
                                        <input type="email" class="form-control" name="email" id="email" placeholder="" value="{{old('email')}}" required tabindex="3">
                                        <span class="text-danger small">{{ $errors->first('email') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="designation">Designation</label>
                                        <input type="text" class="form-control" name="designation" id="designation" placeholder="" value="{{old('designation')}}">
                                        <span class="text-danger small">{{ $errors->first('designation') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="userpicture">Profile Picture</label>
                                        <input type="file" class="btn btn-default" name="userpicture" id="userpicture" />
                                        <span class="text-danger small">{{ $errors->first('userpicture') }}</span>
                                    </div>

                                    <div class="form-group">
                                        <label for="website">Website</label>
                                        <input type="url" class="form-control" name="website" id="website" placeholder="" value="{{old('website')}}">
                                        <span class="text-danger small">{{ $errors->first('website') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 pl-0">
                                <h3 class="badge badge-info">Social Networks</h3>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="google_id">Google</label>
                                        <input type="text" class="form-control" name="google_id" id="google_id" placeholder="" value="{{old('google_id')}}">
                                    </div>
                                    <div class="form-group">
                                        <label for="facebook_id">Facebook</label>
                                        <input type="text" class="form-control" name="facebook_id" id="facebook_id" placeholder="" value="{{old('facebook_id')}}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="linkedin_id">LinkedIn</label>
                                        <input type="text" class="form-control" name="linkedin_id" id="linkedin_id" placeholder="" value="{{old('linkedin_id')}}">
                                    </div>

                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="twitter_id">Twitter</label>
                                        <input type="text" class="form-control" name="twitter_id" id="twitter_id" placeholder="" value="{{old('twitter_id')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="col-lg-12 pl-0">
                                    <h3 class="badge badge-info">Address Information</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control" name="city" id="city" placeholder="" value="{{old('city')}}">
                                    </div>

                                    <div class="form-group">
                                        <label for="zip">Zip</label>
                                        <input type="text" class="form-control" name="zip" id="zip" placeholder="" value="{{old('zip')}}">
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="country">Country</label>
                                        <select class="form-control" name="country" id="country">
                                            {!!$data['countryoptions']!!}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <select class="form-control" name="state" id="state">
                                            <option value="0">Select State</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
<!-- END OLD FORM -->									
									

							


						

                        </div>
                        <!-- /.card-body -->
						<div class="card-footer bg-white border-top pull-right text-right">
                            <a href="{{url('contacts')}}" class="btn btn-outline-secondary mr-1" tabindex="22">Cancel</a>
                            {{Form::submit('Create',['class'=>"btn btn-primary"])}}
                        </div>
                        <!-- </form> -->
                        {{Form::close()}}
                    </div>
                    <!-- /.card -->
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    var url = "{{url('ajaxwebtolead/getStateoptions')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("menu-open");
        $(".sidebar-menu li").removeClass("active");
        //        $("#ulleads").addClass('menu-open');
        //        $("#ulleads ul").css('display', 'block');
        $("#licontacts").addClass("active");

        $("#account").change(function() {
            var acc = $(this).val();
            if (acc == "NewAccount") {
                $("#addAccount").show();
            } else {
                $("#addAccount").hide();
            }
        });

        $("#country").change(function() {
            var country = $(this).val();
            // alert(country);
            if (country > 0) {
                $.get(url, {
                    'country': country
                }, function(result, status) {
                    // alert(result);
                    $("#state").html(result);
                });
            }
        });

    });
</script>

@endsection