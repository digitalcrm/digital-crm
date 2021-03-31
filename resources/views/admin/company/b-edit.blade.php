@extends('layouts.adminlte-boot-4.admin')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-0">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark"><?php echo $data['accounts']->c_name; ?></h1>
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
                    <div class="card card-primary">
                        <!-- /.card-header -->
                        <!-- form start -->
                        <!-- <form role="form" > -->
                        <div class="card-header with-border">
                            <h3 class="card-title">
                                <img src="<?php echo ($data['accounts']->c_logo != "") ? url($data['accounts']->c_logo) : url('uploads/default/accounts.png'); ?>" height="30" width="30" />&nbsp;
                                <?php echo $data['accounts']->c_name; ?>
                            </h3>
                        </div>
                        {{Form::open(['action'=>['Admin\CompanyController@update',$data['accounts']->id],'method'=>'Post','enctype'=>'multipart/form-data'])}}
                        @csrf
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-9">
                                    <h3 class="badge badge-info">Personal Details</h3>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="personal_name">Your Name</label>
                                <div class="col-md-9">
                                    <input value="{{$data['accounts']->personal_name}}" type="text" class="form-control required" name="personal_name" id="personal_name" placeholder="">
                                    @error('personal_name') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="position">Position</label>
                                <div class="col-md-9">
                                    <input value="{{$data['accounts']->position}}" type="text" class="form-control required" name="position" id="position" placeholder="">
                                    @error('position') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="c_email">Email</label>
                                <div class="col-md-9">
                                    <input value="{{$data['accounts']->c_email}}" type="email" class="form-control" name="c_email" id="c_email" placeholder="your company email">
                                    @error('c_email') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="c_mobileNum">Mobile Number</label>
                                <div class="col-md-9">
                                    <input value="{{$data['accounts']->c_mobileNum}}" type="tel" class="form-control required" name="c_mobileNum" id="c_mobileNum" placeholder="">
                                    @error('c_mobileNum') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="c_whatsappNum">WhatsApp
                                    Number</label>
                                <div class="col-md-9">
                                    <input value="{{$data['accounts']->c_whatsappNum}}" type="tel" class="form-control" name="c_whatsappNum" id="c_whatsappNum" placeholder="">
                                    @error('c_whatsappNum') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-9">
                                    <h3 class="badge badge-info">Company Information</h3>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="c_name">Company Name</label>
                                <div class="col-md-9">
                                    <input value="{{$data['accounts']->c_name}}" type="text" class="form-control required" name="c_name" id="c_name" placeholder="company name">
                                    @error('c_name') <span class="text-danger">{{ $message }}</span> @enderror

                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="category_id">Category</label>
                                <div class="col-md-9">
                                    <select class="form-control required" name="category_id" id="category_id">
                                        <!-- <option value="0">Select Type</option> -->
                                        {!!$data['categoryoption']!!}
                                    </select>
                                    @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="actype_id">Company Type</label>
                                <div class="col-md-9">
                                    <select class="form-control required" name="actype_id" id="actype_id">
                                        {!!$data['accounttypeoptions']!!}
                                    </select>
                                    @error('actype_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="c_bio">Company Bio</label>
                                <div class="col-md-9">
                                    <textarea name="c_bio" id="c_bio" class="form-control required" rows="5">{{$data['accounts']->c_bio}}</textarea>
                                    @error('c_bio') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                            </div>



                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right " for="c_logo">Company Logo
                                    Picture</label>
                                <div class="col-md-9">
                                    <input value="c_logo" type="file" class="btn btn-default required" name="c_logo" id="c_logo" />
                                    @error('c_logo') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="c_cover">Cover Picture</label>
                                <div class="col-md-9">
                                    <input value="c_cover" type="file" class="btn btn-default" name="c_cover" id="c_cover" />
                                    @error('c_cover') <span class="text-danger">{{ $message }}</span> @enderror

                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="c_webUrl">Website</label>
                                <div class="col-md-9">
                                    <input value="{{$data['accounts']->c_webUrl}}" type="text" class="form-control" name="c_webUrl" id="c_webUrl" placeholder="">
                                    @error('c_webUrl') <span class="text-danger">{{ $message }}</span> @enderror

                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="c_gstNumber">Company Gst
                                    Number</label>
                                <div class="col-md-9">
                                    <input value="{{$data['accounts']->c_gstNumber}}" type="text" class="form-control required" name="c_gstNumber" id="c_gstNumber" placeholder="">
                                    @error('c_gstNumber') <span class="text-danger">{{ $message }}</span> @enderror

                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="employees">Employees</label>
                                <div class="col-md-9">
                                    <input value="{{$data['accounts']->employees}}" type="number" class="form-control required" name="employees" id="employees" placeholder="">
                                    @error('employees') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-9">
                                    <h3 class="badge badge-info">Google Details</h3>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="yt_video_link">Youtube Link</label>
                                <div class="col-md-9">
                                    <input value="{{$data['accounts']->yt_video_link}}" type="text" class="form-control" name="yt_video_link" id="yt_video_link" placeholder="">

                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="google_map_url">Google Map</label>
                                <div class="col-md-9">
                                    <input value="{{$data['accounts']->google_map_url}}" type="text" class="form-control" name="google_map_url" id="google_map_url" placeholder="">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-9">
                                    <h3 class="badge badge-info">Social Profiles</h3>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="fb_link">Facebook Url</label>
                                <div class="col-md-9">
                                    <input value="{{$data['accounts']->fb_link}}" type="text" class="form-control" name="fb_link" id="fb_link" placeholder="">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="tw_link">Twitter Url</label>
                                <div class="col-md-9">
                                    <input value="{{$data['accounts']->tw_link}}" type="text" class="form-control" name="tw_link" id="tw_link" placeholder="">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="linkedin_link">LinkedIn Url</label>
                                <div class="col-md-9">
                                    <input value="{{$data['accounts']->linkedin_link}}" type="text" class="form-control" name="linkedin_link" id="linkedin_link" placeholder="">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="yt_link">YouTube Url</label>
                                <div class="col-md-9">
                                    <input value="{{$data['accounts']->yt_link}}" type="text" class="form-control" name="yt_link" id="yt_link" placeholder="">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-9">
                                    <h3 class="badge badge-info">Company Address Information</h3>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="address">Address</label>
                                <div class="col-md-9">
                                    <textarea name="address" class="form-control" id="address" cols="30" rows="5">{{$data['accounts']->address}}</textarea>
                                    @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="city">City</label>
                                <div class="col-md-9">
                                    <input value="{{$data['accounts']->city}}" type="text" class="form-control required" name="city" id="city" placeholder="">
                                    @error('city') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                            </div>


                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="country_id">Country</label>
                                <div class="col-md-9">
                                    <select value="country_id" class="form-control required" name="country_id" id="country_id">
                                        {!!$data['countryoptions']!!}
                                    </select>
                                    @error('country_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                            </div>


                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="state_id">State</label>
                                <div class="col-md-9">
                                    <select value="state_id" class="form-control required" name="state_id" id="state_id">
                                        {!!$data['stateoptions']!!}
                                    </select>
                                    @error('state_id') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-3 col-form-label text-right" for="zipcode">Zip</label>
                                <div class="col-md-9">
                                    <input value="{{$data['accounts']->zipcode}}" type="text" class="form-control required" name="zipcode" id="zipcode" placeholder="">
                                    @error('zipcode') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-9">
                                <h3 class="badge badge-info">Additional Information</h3>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3"></div>
                            <div class="col-md-9 form-check">
                                <input type="checkbox" class="form-check-input" name="showLive" id="showLive" <?php echo (($data['accounts']->showLive == 1) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="showLive">Publicly Show</label>
                            </div>
                            @error('showLive') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-group row">
                            <div class="col-md-3"></div>
                            <div class="col-md-9 form-check">
                                <input type="checkbox" class="form-check-input" name="termsAccept" id="termsAccept" <?php echo (($data['accounts']->termsAccept == 1) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="termsAccept">{{ __('custom.terms') }}</label>
                            </div>
                            @error('termsAccept') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer bg-white border-top">
                        {{Form::hidden('_method','PUT')}}
                        {{Form::submit('Save',['class'=>"btn btn-primary float-right"])}}
                        <a href="{{url('admin/accounts')}}" class="btn btn-primary">Back</a>
                    </div>
                    <!-- </form> -->
                    {{Form::close()}}
                </div>
                <!-- /.card -->
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-7 connectedSortable">


            </section>
            <!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-5 connectedSortable">


            </section>
            <!-- right col -->
        </div>
        <!-- /.row (main row) -->
</div>
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
    var url = "{{url('admin/ajax/getStateoptions')}}";
    $(function() {
        $(".sidebar-menu li").removeClass("active");
        $("#liaccounts").addClass("active");

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