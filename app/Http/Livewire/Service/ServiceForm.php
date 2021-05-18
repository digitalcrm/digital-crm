<?php

namespace App\Http\Livewire\Service;

use App\Servcategory;
use App\Service;
use App\ServSubcategory;
use Livewire\Component;
use Livewire\WithFileUploads;

class ServiceForm extends Component
{
    use WithFileUploads;

    public $service; // model

    public $title;
    public $price;
    public $servcategory_id;
    public $serv_subcategory_id = [];
    public $company_id;
    public $image;
    public $description;
    public $brand;
    public $tags;

    public $companies; //get all companies
    public $categories; // get all categories
    public $subcategories = []; // get all subcategories

    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'price' => 'numeric|',
            'servcategory_id' => 'required|numeric|not_in:0',
            'serv_subcategory_id' => 'required|not_in:0',
            'company_id' => 'required|not_in:0',
            'image' => 'nullable|image|max:2024',
            'description' => 'required|string',
            'brand' => 'nullable',
            'tags' => 'nullable',
        ];
    }

    public function mount($service = null)
    {
        if ($service) {
            $this->title = $service->title;
            $this->price = $service->price;
            $this->servcategory_id = $service->servcategory_id;
            $this->serv_subcategory_id = $service->getSubcategoryIds();
            $this->company_id = $service->company_id;
            // $this->image = $service->image;
            $this->description = $service->description;
            $this->brand = $service->brand;
            $this->tags = $service->tags;
        }
    }

    public function store()
    {
        $data = $this->validate() + ['user_id' => auth()->id()];

        if ($this->image) {
            $data['image'] = $this->image->storePublicly('serviceLogo', 'public');
        }

        if (empty($this->price)) {
            $data['price'] = 0;
        }

        $services = Service::create($data);

        if ($this->serv_subcategory_id) {
            $services->serviceSubcategories()->attach($this->serv_subcategory_id);
        }

        session()->flash('success', 'Service added successfully.');

        return redirect()->route('services.index');
    }

    public function update()
    {
        if ($this->service) {

            $data = $this->validate() + ['user_id' => auth()->id()];
            
            if ($this->image) {
                $data['image'] = $this->image->storePublicly('serviceLogo', 'public');
            } else {
                unset($data['image']);
            }
    
            if (empty($this->price)) {
                $data['price'] = 0;
            }

            $this->service->update($data);

            $this->service->serviceSubcategories()->sync($this->serv_subcategory_id);
    
            session()->flash('success', 'service updated successfully.');

            return redirect()->route('services.index');
        }    
    }

    public function render()
    {
        $this->companies = auth()->user()->company()->get(['id', 'c_name', 'user_id']);

        $this->categories = Servcategory::get(['id', 'name']);

        if (!empty($this->servcategory_id) || !($this->servcategory_id === 0)) {
            $this->subcategories = ServSubcategory::where('servcategory_id', $this->servcategory_id)->get(['id', 'name']);
        }
        return view('livewire.service.service-form');
    }
}
