<?php

namespace App\Livewire\Chat;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageSent;
use Livewire\Component;

class SendMessage extends Component
{
    public $selectedChat;
    public $receiverInstance;
    public $body;
    public $createdMessage;
    protected $listeners = ['updateSendMessage', 'dispatchMessageSent'];

    public function updateSendMessage(Chat $chat, User $receiver)
    {
        $this->selectedChat = $chat;
        $this->receiverInstance = $receiver;
    }

    function sendMessage()
    {

        if ($this->body == null) {
            return null;
        }

        $this->createdMessage = Message::create([
            'chat_id' => $this->selectedChat->id,
            'user_id' => auth()->id(),
            'value' => $this->body,
        ]);

        $this->selectedChat->last_time_message = $this->createdMessage->created_at;
        $this->selectedChat->save();

        $this->dispatch('pushMessage', $this->createdMessage->id);

        $this->dispatch('refresh');

        $this->reset('body');

//        $this->dispatch('dispatchMessageSent');

        $this->selectedChat->getReceiver()
            ->notify(new MessageSent(
                auth()->user(),
                $this->createdMessage,
                $this->selectedChat,
                $this->selectedChat->getReceiver()->id,
            ));
    }

//    public function dispatchMessageSent()
//    {
//        broadcast(new MessageSent(auth()->user(), $this->createdMessage, $this->selectedChat, $this->receiverInstance));
//    }

    public function render()
    {
        return view('livewire.chat.send-message');
    }
}
