<?php

namespace App\Livewire\Chat;

use App\Models\Chat;
use App\Models\User;
use Livewire\Component;

class ChatList extends Component
{
    public $auth_id;
    public $chats;
    public $receiverInstance;
    public $name;
    public $selectedChat;

    protected $listeners = ['chatUserSelected', 'refresh' => '$refresh'];

    public function chatUserSelected(Chat $chat, $receiverId)
    {
        $this->selectedChat = $chat;

        $receiverInstance = User::find($receiverId);

        $this->dispatch('loadChat', $this->selectedChat, $receiverInstance);

        $this->dispatch('updateSendMessage', $this->selectedChat, $receiverInstance);
    }

    public function getChatUserInstance(Chat $chat, $request)
    {
        $this->auth_id = auth()->id();

        if ($chat->user_id_first == $this->auth_id) {
            $this->receiverInstance = User::firstWhere('id', $chat->user_id_second);
        } else {
            $this->receiverInstance = User::firstWhere('id', $chat->user_id_first);
        }

        if (isset($request)) {
            return $this->receiverInstance->$request;
        }
    }

    public function mount()
    {
        $this->auth_id = auth()->id();

        $this->chats = Chat::where('user_id_first', $this->auth_id)
            ->orWhere('user_id_second', $this->auth_id)
            ->orderBy('last_time_message', 'DESC')
            ->get();
    }

    public function render()
    {
        return view('livewire.chat.chat-list');
    }
}
