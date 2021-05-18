<?php

namespace App\Http\Livewire\Dashboard;

use App\Company;
use App\RfqLead;
use App\Tbl_leads;
use App\Tbl_products;
use Livewire\Component;

class InfoBox extends Component
{
    public $countProducts;
    public $countCompany;
    public $countProductLeads;
    public $countRfqLead;

    public function render()
    {
        $this->countProducts = Tbl_products::where('uid', auth()->id())->isActive()->count();
        
        $this->countCompany = Company::where('user_id', auth()->id())->isActive()->count();

        $this->countProductLeads = Tbl_leads::whereHas('tbl_products', function ($query) {
            $query->where('uid', auth()->id())->isActive();
        })->where('uid', auth()->id())->isActive()->count();

        $this->countRfqLead = RfqLead::whereHas('rfq', function($query){
            $query->where('user_id', auth()->id());
        })->count();

        return view('livewire.dashboard.info-box');
    }
}
