<?php

namespace App\Livewire\Chat;

use App\Events\MessageSent;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Livewire\Component;

class Chatbox extends Component
{
    public $selectedChat;
    public $receiverInstance;
    public $messages;
    public $paginateVar = 20;
    public $messages_count;

    public function getListeners()
    {
        $auth_id = auth()->user()->id;
        return [
            "echo-private:chat.{$auth_id},MessageSentEvent" => 'broadcastedMessageReceived',
            'loadChat', 'pushMessage', 'loadmore', 'updateHeight'
        ];
    }

    function broadcastedMessageReceived($event)
    {

        $broadcastedMessage = Message::find($event['message']['id']);

        if ($this->selectedChat) {

            if ((int) $this->selectedChat->id === (int)$event['chat']['id']) {

                $broadcastedMessage->read_status = 1;
                $broadcastedMessage->save();

                $this->pushMessage($broadcastedMessage->id);

            }

        }
    }

    public function pushMessage(int $messageId)
    {
        $newMessage = Message::find($messageId);

        $this->messages->push($newMessage);

        $this->dispatch('rowChatToBottom');
    }

    function loadmore()
    {
        dd('top reached');
    }

    public function loadChat(Chat $chat, User $receiver)
    {

        $this->selectedChat = $chat;
        $this->receiverInstance = $receiver;

        $this->messages_count = Message::where('chat_id', $this->selectedChat->id)->count();

        $this->messages = Message::where('chat_id', $this->selectedChat->id)
            ->skip($this->messages_count - $this->paginateVar)
            ->take($this->paginateVar)
            ->get();

        $this->dispatch('chatSelected');
    }

    public function render()
    {
        return view('livewire.chat.chatbox');
    }
}
