<?php

namespace App\Http\Livewire\Rfq;

use App\Rfq;
use Livewire\Component;

class IsActive extends Component
{
    public $rfq;
    
    public function isActive($id)
    {
        $rowValue = Rfq::findOrFail($id);

        $this->isRfqActive = ($rowValue->isActive === 1) ? false : true;

        $rowValue->update([
            'isActive' => $this->isRfqActive,
        ]);

        session()->flash('message', 'Status updated successfully.');

        return redirect()->route('rfq-forms.index');
    }
    public function mount(Rfq $rfq)
    {
        $this->rfq = $rfq;
    }
    public function render()
    {
        return view('livewire.rfq.is-active');
    }
}
