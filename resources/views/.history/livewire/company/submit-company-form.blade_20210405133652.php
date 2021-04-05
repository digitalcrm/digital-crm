<div>
    <div class="row">
        <div class="col-lg-6">
            <!-- general form elements -->
            <div class="card shadow card-primary card-outline">
                <!-- <form role="form" > -->
                @if ($company)
                    <form wire:submit.prevent="update" enctype="multipart/form-data">
                    @else
                    <form wire:submit.prevent="store" enctype="multipart/form-data">
                @endif
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <h3 class="badge badge-default">Personal Details</h3>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="personal_name">Your Name</label>
                        <div class="col-md-9">
                            <input wire:model="personal_name" type="text" class="form-control required"
                                name="personal_name" id="personal_name" placeholder="">
                            @error('personal_name') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="position">Position</label>
                        <div class="col-md-9">
                            <input wire:model="position" type="text" class="form-control required" name="position"
                                id="position" placeholder="">
                            @error('position') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="c_email">Email</label>
                        <div class="col-md-9">
                            <input wire:model="c_email" wire:model="c_email" type="email" class="form-control"
                                name="c_email" id="c_email" placeholder="your company email">
                            @error('c_email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="c_mobileNum">Mobile Number</label>
                        <div class="col-md-9">
                            <input wire:model="c_mobileNum" wire:model="c_mobileNum" type="tel"
                                class="form-control required" name="c_mobileNum" id="c_mobileNum" placeholder="">
                            @error('c_mobileNum') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="c_whatsappNum">WhatsApp
                            Number</label>
                        <div class="col-md-9">
                            <input wire:model="c_whatsappNum" type="tel" class="form-control" name="c_whatsappNum"
                                id="c_whatsappNum" placeholder="">
                            @error('c_whatsappNum') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <h3 class="badge badge-default">Company Information</h3>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="c_name">Company Name</label>
                        <div class="col-md-9">
                            <input wire:model="c_name" type="text" class="form-control required" name="c_name"
                                id="c_name" placeholder="company name">
                            @error('c_name') <span class="text-danger">{{ $message }}</span> @enderror

                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="category_id">Category</label>
                        <div class="col-md-9">
                            <select wire:model="category_id" class="form-control required" name="category_id"
                                id="category_id">
                                <option value="0">Select Type</option>
                                @if ($businessType)
                                    @foreach ($businessType as $b_type)
                                        <option value="{{ $b_type->procat_id }}">{{ $b_type->category }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="actype_id">Company Type</label>
                        <div class="col-md-9">
                            <select wire:model="actype_id" class="form-control required" name="actype_id"
                                id="actype_id">
                                <option value="0">Select Type</option>
                                @if ($companyType)
                                    @foreach ($companyType as $c_type)
                                        <option value="{{ $c_type->actype_id }}">{{ $c_type->account_type }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('actype_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="c_bio">Company Bio</label>
                        <div class="col-md-9">
                            <textarea wire:model="c_bio" name="c_bio" id="c_bio" class="form-control required"
                                rows="5"></textarea>
                            @error('c_bio') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right " for="c_logo">Company Logo
                            Picture</label>
                        <div class="col-md-9">
                            <input wire:model="c_logo" type="file" class="btn btn-default" name="c_logo"
                                id="c_logo" />
                            @error('c_logo') <span class="text-danger">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="c_logo">Uploading...</div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right " for="c_logo">Catalog</label>
                        <div class="col-md-9">
                            <input wire:model="document" type="file" class="btn btn-default" name="document"
                                id="document" />
                            @error('document') <span class="text-danger">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="document">Uploading...</div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="c_cover">Cover Picture</label>
                        <div class="col-md-9">
                            <input wire:model="c_cover" type="file" class="btn btn-default" name="c_cover"
                                id="c_cover" />
                            @error('c_cover') <span class="text-danger">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="c_cover">Uploading...</div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="c_webUrl">Website</label>
                        <div class="col-md-9">
                            <input wire:model="c_webUrl" type="text" class="form-control" name="c_webUrl" id="c_webUrl"
                                placeholder="">
                            @error('c_webUrl') <span class="text-danger">{{ $message }}</span> @enderror

                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="c_gstNumber">Company Gst
                            Number</label>
                        <div class="col-md-9">
                            <input wire:model="c_gstNumber" type="text" class="form-control required" name="c_gstNumber"
                                id="c_gstNumber" placeholder="">
                            @error('c_gstNumber') <span class="text-danger">{{ $message }}</span> @enderror

                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="employees">Employees</label>
                        <div class="col-md-9">
                            <input wire:model="employees" type="number" class="form-control required" name="employees"
                                id="employees" placeholder="">
                            @error('employees') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <h3 class="badge badge-default">Google Details</h3>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="yt_video_link">Youtube Link</label>
                        <div class="col-md-9">
                            <input wire:model="yt_video_link" type="text" class="form-control" name="yt_video_link"
                                id="yt_video_link" placeholder="">

                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="google_map_url">Google Map</label>
                        <div class="col-md-9">
                            <input wire:model="google_map_url" type="text" class="form-control" name="google_map_url"
                                id="google_map_url" placeholder="">

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <h3 class="badge badge-default">Social Profiles</h3>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="fb_link">Facebook Url</label>
                        <div class="col-md-9">
                            <input wire:model="fb_link" type="text" class="form-control" name="fb_link" id="fb_link"
                                placeholder="">

                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="tw_link">Twitter Url</label>
                        <div class="col-md-9">
                            <input wire:model="tw_link" type="text" class="form-control" name="tw_link" id="tw_link"
                                placeholder="">

                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="linkedin_link">LinkedIn Url</label>
                        <div class="col-md-9">
                            <input wire:model="linkedin_link" type="text" class="form-control" name="linkedin_link"
                                id="linkedin_link" placeholder="">

                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="yt_link">YouTube Url</label>
                        <div class="col-md-9">
                            <input wire:model="yt_link" type="text" class="form-control" name="yt_link" id="yt_link"
                                placeholder="">

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <h3 class="badge badge-default">Company Address Information</h3>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="address">Address</label>
                        <div class="col-md-9">
                            <textarea wire:model="address" name="address" class="form-control" id="address" cols="30"
                                rows="5"></textarea>
                            @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="city">City</label>
                        <div class="col-md-9">
                            <input wire:model="city" type="text" class="form-control required" name="city" id="city"
                                placeholder="">
                            @error('city') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                    </div>


                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="country_id">Country</label>
                        <div class="col-md-9">
                            <select wire:model="country_id" class="form-control required" name="country_id"
                                id="country_id">
                                <option value="0">select country</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('country_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                    </div>


                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="state_id">State</label>
                        <div class="col-md-9">
                            <select wire:model="state_id" class="form-control required" name="state_id" id="state_id">
                                <option value="0">Select State</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                            @error('state_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label text-right" for="zipcode">Zip</label>
                        <div class="col-md-9">
                            <input wire:model="zipcode" type="text" class="form-control required" name="zipcode"
                                id="zipcode" placeholder="">
                            @error('zipcode') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-md-9 form-check">
                        <input wire:model="showLive" type="checkbox" class="form-check-input" name="showLive"
                            id="showLive">
                        <label class="form-check-label" for="showLive">Publicly Show</label>
                    </div>
                    @error('showLive') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="form-group row">
                    <div class="col-md-3"></div>
                    <div class="col-md-9 form-check">
                        <input wire:model="termsAccept" type="checkbox" class="form-check-input" name="termsAccept"
                            id="termsAccept">
                        <label class="form-check-label" for="termsAccept">{{ __('custom.terms') }}</label>
                    </div>
                    @error('termsAccept') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

            </div>
            <!-- /.card-body -->
            <div class="card-footer bg-white pull-right text-right">
                <a href="#" class="btn btn-outline-secondary mr-1">Cancel</a>
                <button type="submit" class="btn btn-primary">{{ __('custom.submit') }}</button>
            </div>
            <!-- </form> -->
            </form>
        </div>
        <!-- /.card -->
    </div>
</div>
