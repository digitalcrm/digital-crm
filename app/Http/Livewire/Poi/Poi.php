<?php

namespace App\Http\Livewire\Poi;

use App\Poi as AppPoi;
use Livewire\Component;
use App\Tbl_productcategory;
use App\Tbl_product_subcategory;

class Poi extends Component
{
    /**tabel pois attribute */
    public $product_name;
    public $user_id;
    public $product_category_id;
    public $sub_category_id;
    public $isActive;
    
    /**for looping subcategories */
    public $subcategories = [];

    /**for editing */
    public $wantsToEdit = false;
    public $editId;

    public function store()
    {
        $validatedData = $this->validate([
            'product_name' => 'required',
            'product_category_id' => 'required',
            'sub_category_id' => 'required',
        ]);

        $validatedData['user_id'] = auth()->user()->id;

        AppPoi::create($validatedData);

        $this->dispatchBrowserEvent('closeModal', ['message' => 'data added successfully']);

        session()->flash('message', 'Row data successfully created.');

        $this->reset();

    }

    public function isActive($id)
    {
        $rowValue = AppPoi::findOrFail($id);

        $this->isActive = ($rowValue->isActive === 1) ? false : true;

        $rowValue->update([
            'isActive' => $this->isActive,
        ]);

        $this->dispatchBrowserEvent('alert', ['message' => 'product of interest status has been changed']);

        // return redirect()->route('pois.index');

    }

    /**if user clicks on edit button then this function will triggered */
    public function editPoi($id)
    {
        $editRowValue = AppPoi::findOrFail($id);

        $this->wantsToEdit = true;

        $this->editId = $editRowValue->id;

        $this->product_name = $editRowValue->product_name;
        $this->product_category_id = $editRowValue->product_category_id;
        $this->sub_category_id = $editRowValue->sub_category_id;
    }

    /**updating the values */
    public function editSave()
    {
        if($this->wantsToEdit)
        {
            $findRowValue = AppPoi::findOrFail($this->editId);

            $validatedData = $this->validate([
                'product_name' => 'required',
                'product_category_id' => 'required',
                'sub_category_id' => 'required',
            ]);
    
            $validatedData['user_id'] = auth()->user()->id;
    
            $findRowValue->update($validatedData);

            $this->dispatchBrowserEvent('closeModal',['message' => 'data updated successfully']);
    
            session()->flash('message', 'Row data updated.');
    
            $this->reset();
        }
    }

    public function render()
    {
        try {
            if (!empty($this->product_category_id)) {
                $this->subcategories = Tbl_product_subcategory::where('procat_id', $this->product_category_id)->get();
            }
            return view('livewire.poi.poi')->with([
                'product_of_interests' => AppPoi::latest()->get(),
                'categories' => Tbl_productcategory::has('tbl_product_subcategory')->get(),
            ]);
        } catch (\Throwable $th) {
            dd('catch exception', $th->getMessage());
        }
    }
}
