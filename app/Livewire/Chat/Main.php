<?php

namespace App\Livewire\Chat;

use Livewire\Component;

class Main extends Component
{
    public $user_id;

    public function render()
    {
        $this->user_id = auth()->user()->id;
        return view('livewire.chat.main');
    }
}
