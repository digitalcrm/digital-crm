<?php

namespace App\Http\Livewire\Company;

use App\Company;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AllLists extends Component
{
    use WithPagination, AuthorizesRequests;

    public $companies;

    public function deleteCompany($id)
    {
        // Gate::authorize('delete', Company::class);

        $deleteComp = Company::findOrFail($id);

        $this->authorize('delete', $deleteComp);
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
