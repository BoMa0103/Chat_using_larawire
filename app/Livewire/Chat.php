<?php

namespace App\Livewire;

use Livewire\Component;

class Chat extends Component
{
    public function render()
    {
        return view('livewire.chat', [
            "user_id" => auth()->user()->id,
        ]);
    }
}
