<?php

namespace App\Http\Livewire\Company;

use App\Company;
use App\Tbl_accounttypes;
use App\Tbl_countries;
use App\Tbl_productcategory;
use App\Tbl_states;
use Livewire\Component;
use Livewire\WithFileUploads;

class SubmitCompanyForm extends Component
{
    use WithFileUploads;

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
    public $document; // company catalog
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

    public $showLive = true;
    public $termsAccept = true;

    // descendent dropdown
    public $countries;
    public $states = [];
    public $companyType;
    public $businessType;

    protected $listeners = ['companyDelete' => 'deleteCompany'];

    public function updated()
    {
        $this->validate([
            'position' => 'nullable|max:45',
            'c_logo' => 'nullable|image|max:1024',
            'document' => 'nullable|mimes:jpg,bmp,png,pdf,docx,doc,ppt,pptx|max:2024',
            'c_cover' => 'nullable|image|max:1024',
        ]);
    }

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
            // $this->c_logo = $company->c_logo;
            // $this->c_cover = $company->c_cover;
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

    protected function rules()
    {
        // $imageCondition = ($this->company) ? 'image|max:1024' : 'required|image|max:1024';
        return [
            'personal_name' => 'required|max:25',
            'c_email' => 'email',
            'position' => 'nullable|max:45',
            'c_mobileNum' => 'required|digits_between:10,15|unique:companies,c_mobileNum,' . optional($this->company)->id,
            'c_whatsappNum' => 'required|digits_between:10,15|unique:companies,c_whatsappNum,' . optional($this->company)->id,
            'c_name' => 'required|max:55|unique:companies,c_name,' . optional($this->company)->id,
            'category_id' => 'required|not_in:0', //business type
            'actype_id' => 'required|not_in:0', // company type
            'c_bio' => 'required|min:15',
            'c_logo' => 'nullable|image|max:1024',
            'document' => 'nullable|mimes:jpg,bmp,png,pdf,docx,doc,ppt,pptx|max:2024',
            'c_cover' => 'nullable|image|max:1024',
            'c_webUrl' => 'nullable|',
            'c_gstNumber' => 'nullable|digits_between:12,15',
            'employees' => 'nullable|min:0',
            'google_map_url' => 'nullable|max:500',
            'yt_video_link' => 'nullable',
            'fb_link' => 'nullable',
            'tw_link' => 'nullable',
            'yt_link' => 'nullable',
            'linkedin_link' => '',
            'country_id' => 'required|not_in:0',
            'state_id' => 'required|not_in:0',
            'address' => 'required|max:255',
            'city' => 'required|max:45',
            'zipcode' => 'required|digits_between:5,6',
            'showLive' => 'accepted',
            'termsAccept' => 'accepted',
        ];
    }

    public function store()
    {
        $data = $this->validate() + ['user_id' => auth()->id()];

        if ($this->c_logo) {
            $data['c_logo'] = $this->c_logo->storePublicly('companyLogo', 'public');
        }

        if ($this->document) {
            $data['document'] = $this->document->storePublicly('companyCatalog', 'public');
        }

        if ($this->c_cover) {
            $data['c_cover'] = $this->c_cover->storePublicly('companyCover', 'public');
        }

        if (empty($this->employees)) {
            $data['employees'] = 0;
        }

        Company::create($data);

        session()->flash('success', 'company added successfully.');
        // $this->reset();
        return redirect()->route('companies.index');
    }

    public function update()
    {
        if ($this->company) {
            $data = $this->validate() + ['user_id' => auth()->id()];

            if ($this->c_logo) {
                $data['c_logo'] = $this->c_logo->storePublicly('companyLogo', 'public');
            } else {
                unset($data['c_logo']);
            }

            if ($this->document) {
                $data['document'] = $this->document->storePublicly('companyCatalog', 'public');
            } else {
                unset($data['document']);
            }

            if ($this->c_cover) {
                $data['c_cover'] = $this->c_cover->storePublicly('companyCover', 'public');
            } else {
                unset($data['c_cover']);
            }

            if (empty($this->employees)) {
                $data['employees'] = 0;
            }

            $this->company->update($data);

            session()->flash('success', 'company updated successfully.');

            return redirect()->to('/companies');
        }
    }

    // public function companyType()
    // {
    //     $this->companyType = Tbl_accounttypes::get();
    // }

    // public function businessType()
    // {
    //     $this->businessType = Tbl_industrytypes::get();
    // }

    public function deleteCompany()
    {
        dd('from subcompany component');
    }

    public function render()
    {
        $this->companyType = Tbl_accounttypes::get();
        $this->businessType = Tbl_productcategory::get();
        $this->countries = Tbl_countries::get();
        if (!empty($this->country_id)) {
            $this->states = Tbl_states::where('country_id', $this->country_id)->get();
        }

        return view('livewire.company.submit-company-form');
    }
}
