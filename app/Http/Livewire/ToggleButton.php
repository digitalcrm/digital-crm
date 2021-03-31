<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;

class ToggleButton extends Component
{
    public Model $model;
    public string $field;
    public int $modelId;

    public bool $active;

    public function mount()
    {
        $this->active = (bool) $this->model->getAttribute($this->field);
    }

    public function updating($field, $value)
    {
        // dd($value, $this->field);
        $this->model->setAttribute($this->field, $value)->save();
        $this->emit('statusUpdated');
    }

    // public function toggleUpdate($id)
    // {
    //     // dd($this->active);
    //     $rowValue = $this->model::findOrFail($id);
    //     $value = ($rowValue === true) ? false : true;
    //     $rowValue->update([
    //         $this->field = $value,
    //     ]);
    //     dd($value);
    // }

    public function render()
    {
        return view('livewire.toggle-button');
    }
}
