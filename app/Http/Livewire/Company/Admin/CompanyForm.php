<?php

namespace App\Http\Livewire\Company\Admin;

use Livewire\Component;
use App\User;
use App\Company;
use App\Tbl_accounttypes;
use App\Tbl_countries;
use App\Tbl_productcategory;
use App\Tbl_states;
use Livewire\WithFileUploads;

class CompanyForm extends Component
{
    // public $data, $c_name, $c_email, $c_id;
    public $updateMode = false;
    public $company;
    // personal details
    public $personal_name;
    public $c_email;
    public $position;
    public $c_mobileNum;
    public $c_whatsappNum;
    // Company details
    public $c_name;
    public $category_id; //business type
    public $actype_id; // company type
    public $c_bio;
    public $c_logo;
    public $c_cover;
    public $c_webUrl;
    public $c_gstNumber;
    public $employees;

    public $google_map_url;
    public $yt_video_link;
    public $fb_link;
    public $tw_link;
    public $yt_link;
    public $linkedin_link;

    public $user_id;
    public $country_id;
    public $state_id;
    public $address;
    public $city;
    public $zipcode;

    public $showLive;
    public $termsAccept;

    // descendent dropdown
    public $countries;
    public $states = [];
    public $companyType;
    public $businessType;

    public function mount($company = null)
    {
        if ($company) {
            $this->personal_name = $company->personal_name;
            $this->c_email =     $company->c_email;
            $this->position =     $company->position;
            $this->c_mobileNum =     $company->c_mobileNum;
            $this->c_whatsappNum =     $company->c_whatsappNum;

            $this->c_name = $company->c_name;
            $this->category_id = $company->category_id; //business type; //business type
            $this->actype_id = $company->actype_id; // company type; // company type
            $this->c_bio = $company->c_bio;
            $this->c_logo = $company->c_logo;
            $this->c_cover = $company->c_cover;
            $this->c_webUrl = $company->c_webUrl;
            $this->c_gstNumber = $company->c_gstNumber;
            $this->employees = $company->employees;

            $this->google_map_url = $company->google_map_url;
            $this->yt_video_link =  $company->yt_video_link;
            $this->fb_link = $company->fb_link;
            $this->tw_link = $company->tw_link;
            $this->yt_link = $company->yt_link;
            $this->linkedin_link = $company->linkedin_link;

            $this->user_id = $company->user_id;
            $this->country_id = $company->country_id;
            $this->state_id = $company->state_id;
            $this->address =  $company->address;
            $this->city = $company->city;
            $this->zipcode = $company->zipcode;

            $this->showLive =  $company->showLive;
            $this->termsAccept = $company->termsAccept;
        }
    }

    private function resetInput()
    {
        $this->c_name = null;
        $this->c_email = null;
    }

    public function edit($id)
    {
        $this->updateMode = true;
        $record = Company::findOrFail($id);
        // echo json_encode($record);
        // exit();
        // $this->c_id = $id;
        // $this->c_name = $record->c_name;
        // $this->c_email = $record->c_name;
    }

    public function update()
    {
        // echo json_encode($this->company);
        // $this->validate([
        //     'c_id' => 'required|numeric',
        //     'c_name' => 'required|min:5',
        //     'c_email' => 'required|email:rfc,dns'
        // ]);

        // if ($this->c_id) {
        //     $record = Company::find($this->c_id);
        //     $record->update([
        //         'c_name' => $this->c_name,
        //         'c_email' => $this->c_email
        //     ]);
        //     $this->resetInput();
        //     $this->updateMode = false;
        // }
        if ($this->company) {


            echo 'Helloooo';
            // $data = $this->validate() + ['user_id' => auth()->id()];

            // // if ($this->c_logo) {
            // //     $data['c_logo'] = $this->c_logo->storePublicly('companyLogo','public');
            // // }

            // if ($this->c_cover) {
            //     $data['c_cover'] = $this->c_cover->storePublicly('companyCover', 'public');
            // }

            // // dd($this->company->id);
            // $this->company->update($data);

            // session()->flash('success', 'company updated successfully.');

            // return redirect()->to('/companies');
        }
    }


    public function delete($id)
    {
        echo $id;
    }

    public function render()
    {
        $this->companyType = Tbl_accounttypes::get();
        $this->businessType = Tbl_productcategory::get();
        $this->countries = Tbl_countries::get();
        if (!empty($this->country_id)) {
            $this->states = Tbl_states::where('country_id', $this->country_id)->get();
        }

        return view('livewire.company.admin.company-form');
    }
}
