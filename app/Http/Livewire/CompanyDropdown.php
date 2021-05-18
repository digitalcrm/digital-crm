<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CompanyDropdown extends Component
{
    public $companies;
    public $company_id;
    public $value;

    // protected $listeners = ['dropDownValue'];

    public function dropDownValue()
    {
        if ($this->company_id) {
            $this->value = $this->company_id;
        }
        return false;
    }

    public function render()
    {
        $this->companies = auth()->user()->company()->get(['id', 'c_name', 'user_id']);

        return view('livewire.company-dropdown');
    }
}
