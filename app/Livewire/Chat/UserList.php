<?php

namespace App\Livewire\Chat;

use App\Events\ChatCreate;
use App\Events\MarkAsOnline;
use App\Models\Chat;
use App\Models\User;
use Livewire\Component;

class UserList extends Component
{
    public $users;

    public function checkChat(int $userId)
    {
        $checkedChat = Chat::where('user_id_first', auth()->user()->id)
            ->where('user_id_second', $userId)
            ->orWhere('user_id_first', $userId)
            ->where('user_id_second', auth()->user()->id)
            ->get();

        if (count($checkedChat) == 0) {
            $createdChat = Chat::create([
                'user_id_first' => auth()->user()->id,
                'user_id_second' => $userId,
            ]);

            broadcast(event: new ChatCreate(
                $createdChat->id,
                $userId));

            $this->dispatch('refreshChatList');

            broadcast(event: new MarkAsOnline(
                auth()->user()->id));
        }
    }

    public function render()
    {
        $this->users = User::where('id', '!=', auth()->user()->id)->get();
        return view('livewire.chat.user-list');
    }
}
