<?php

namespace App\Http\Livewire\Company\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\User;
use App\Company;

class CompanyList extends Component
{
    use WithPagination;
    public $searchTerm;
    public $uid;

    public function render()
    {
        $uid = (($this->uid > 0) && ($this->uid != 'All')) ? $this->uid : 'All';

        $users = User::orderby('id', 'asc')->get(['id', 'name']);
        // $uid = 'All';
        $useroptions = "<option value='All' selected>All</option>";
        foreach ($users as $userdetails) {
            $useroptions .= "<option value=" . $userdetails->id . ">" . $userdetails->name . "</option>";   // " . $selected . "
        }
        // $data['useroptions'] = $useroptions;

        $searchTerm = '%' . $this->searchTerm . '%';
        $companys = [];
        if ($uid == 'All') {
            $companys = Company::where('c_name', 'like', $searchTerm)->paginate(10);
        } else {
            $companys = Company::where('c_name', 'like', $searchTerm)->where('user_id', $uid)->paginate(10);
        }


        return view('livewire.company.admin.company-list', [
            'companys' => $companys
        ])->with('useroptions', $useroptions);
    }
}
