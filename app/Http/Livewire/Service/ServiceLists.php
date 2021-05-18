<?php

namespace App\Http\Livewire\Service;

use App\Service;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceLists extends Component
{
    use WithPagination, AuthorizesRequests;
    
    protected $paginationTheme = 'bootstrap';

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $serviceRow = Service::findOrFail($id);

        $this->authorize('delete', $serviceRow);
        
        $serviceRow->delete();
        
        session()->flash('message', 'company deleted successfully.');
    }

    public function render()
    {
        $services = Service::whereHas('user', function($query) {
            $query->where('user_id', auth()->user()->id);
        })->has('serviceCategory')->has('company')->search($this->search)->latest()->paginate(10)->WithQueryString();

        return view('livewire.service.service-lists', compact('services'));
    }
}
