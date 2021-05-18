<?php

namespace App\Http\Livewire\Service\Admin;

use App\Service;
use App\User;
use Livewire\Component;
use Livewire\WithPagination;

class AllServices extends Component
{
    use WithPagination;

    public $search = '';
    public $users;

    public $userId;

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function mount(User $user)
    {
        $this->users = $user->isActive()->get();
    }

    public function render()
    {
        $query = Service::query();

        $services = $query->whereHas('user', function($query) {
            $query->isActive()->selectedUser($this->userId);
        })->has('serviceCategory')->has('company')->search($this->search)->latest()->paginate(10)->WithQueryString();

        return view('livewire.service.admin.all-services', compact('services'));
    }
}
