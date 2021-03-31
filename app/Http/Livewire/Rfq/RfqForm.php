<?php

namespace App\Http\Livewire\Rfq;

use App\Rfq;
use App\City;
use App\Company;
use App\currency;
use App\Tbl_units;
use Livewire\Component;
use App\Tbl_productcategory;
use App\Tbl_product_subcategory;
use Livewire\WithFileUploads;

class RfqForm extends Component
{
    use WithFileUploads;

    public $product_name;
    public $product_quantity;
    public $unit_id;
    public $currency_id;
    public $purchase_price;
    public $company_id;
    public $city;
    public $details;
    public $isChecked;

    public $images = [];
    public $iteration;

    public $product_category_id;
    public $subcategories = [];
    public $sub_category_id;

    // public function updated()
    // {
    //     $this->validate([
    //         'product_name' => 'required',
    //         'product_category_id' => 'required|not_in:0',
    //         'sub_category_id' => 'required|not_in:0',
    //         'product_quantity' => 'required',
    //         'unit_id' => 'required|not_in:0',
    //         'purchase_price' => 'nullable',
    //         'city' => 'required|',
    //         'isChecked' => 'required|accepted',
    //         'details' => 'required|string|min:1|max:500',
    //     ]);
    // }

    public function store()
    {
        $validatedData = $this->validate([
            'product_name' => 'required',
            'product_category_id' => 'required|not_in:0',
            'sub_category_id' => 'required|not_in:0',
            'company_id' => 'required|not_in:0',
            'product_quantity' => 'required',
            'unit_id' => 'required|not_in:0',
            'purchase_price' => 'nullable',
            'city' => 'required|',
            'isChecked' => 'required|accepted',
            'details' => 'required|string|min:1|max:500',
            'images.*' => 'mimes:jpg,jpeg,png,bmp,gif,svg,webp,pdf,docx|max:1024',
        ]) + [
            'user_id' => auth()->user()->id,
        ];

        $rfqData = Rfq::create($validatedData);

        if ($this->images)
        {
            foreach ($this->images as $photo) {
                $path_url = $photo->storePublicly('rfqs', 'public');
                // $path_url = $photo->store('dum','local');

                $rfqData->images()->create([
                    'file_name' => $photo->getClientOriginalName(),
                    'mime_type' => $photo->getMimeType(),
                    'file_path' => $path_url,
                ]);
            }
        }

        $this->images = null;
        $this->iteration++;

        session()->flash('message', 'RFQ submitted successfully.');

        $this->reset();

        return redirect()->route('rfq-forms.index');

    }

    public function render()
    {
        try {
            if (!empty($this->product_category_id)) {
                $this->subcategories = Tbl_product_subcategory::where('procat_id', $this->product_category_id)->get();
            }

            return view('livewire.rfq.rfq-form')->with([
                'categories' => Tbl_productcategory::has('tbl_product_subcategory')->get(),
                'units' => Tbl_units::get(),
                'currencies' => currency::where('status', true)->first(),
                'companies' => Company::where('user_id',auth()->id())->latest()->get(['id','c_name']),
                // 'cities' => City::where('country_code','IN')->get(['id','name','country_code']),
            ]);
        } catch (\Throwable $th) {
            dd('catch exception');
        }
    }
}
