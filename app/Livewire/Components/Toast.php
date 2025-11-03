<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Toast extends Component
{

    public $show = false;
    public $message = '';
    public $type = ''; // success | error | warning | info

    protected $listeners = ['showToast'];

    public function showToast($message, $type)
    {
        // Force reset to trigger re-render
        $this->reset(['show', 'message', 'type']);

        // Small delay before showing again to ensure reactivity
        $this->message = $message;
        $this->type = $type;

        // Now show it
        $this->show = true;

        // Trigger Alpine animation
        $this->dispatch('toast-shown');
    }

    public function render()
    {
        return view('livewire.components.toast');
    }
}
