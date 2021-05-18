<?php

namespace App\Http\Livewire\Service;

use App\Tbl_leads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceLeads extends Component
{
    use WithPagination, AuthorizesRequests;

    protected $paginationTheme = 'bootstrap';

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete(int $id)
    {
        $data = Tbl_leads::findOrFail($id);

        // $this->authorize('delete', Tbl_leads::class);
        
        $this->authorize('delete', $data);
        
        $data->delete();

        session()->flash('message', 'lead deleted successfully.');

        return redirect()->route('auth.serviceleads');
    }

    public function render()
    {
        $serviceLeads = Tbl_leads::where('uid', auth()->user()->id)
        ->serviceLeads()
        ->search($this->search)
        ->latest()
        ->paginate(10)
        ->WithQueryString();

        return view('livewire.service.service-leads', compact('serviceLeads'));
    }
}
