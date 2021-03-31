<?php

namespace App\Http\Livewire\Company;

use App\Company;
use Livewire\Component;
use Livewire\WithPagination;

class AllLists extends Component
{
    use WithPagination;

    public $companies;

    public function deleteCompany($id)
    {
        $deleteComp = Company::findOrFail($id);
        // dd($deleteComp);
        $deleteComp->delete();

        session()->flash('message', 'company deleted successfully.');
        // $this->emit('companyDelete', $slug);
    }
    public function render()
    {
        $this->companies = Company::with(['tbl_products'])->where('user_id',auth()->id())->latest()->get();
        return view('livewire.company.all-lists');
    }
}
