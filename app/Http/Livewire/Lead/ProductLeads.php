<?php

namespace App\Http\Livewire\Lead;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProductLeads extends Component
{
    public $data;

    public function render()
    {
        return view('livewire.lead.product-leads');
    }
}
