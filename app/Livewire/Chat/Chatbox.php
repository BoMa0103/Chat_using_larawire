<?php

namespace App\Livewire\Chat;

use App\Events\MessageRead;
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
            "echo-private:chat.{$auth_id},MessageRead" => 'broadcastedMessageRead',
            'loadChat', 'pushMessage', 'broadcastMessageRead', 'resetChat',
        ];
    }

    public function resetChat(){
        $this->selectedChat = null;
        $this->receiverInstance = null;
        $this->dispatch('refresh');
    }

    function broadcastedMessageReceived($event)
    {
        $this->dispatch('refresh');

        $broadcastedMessage = Message::find($event['message']['id']);

        if ($this->selectedChat) {

            if ((int) $this->selectedChat->id === (int)$event['chat']['id']) {

                $broadcastedMessage->read_status = 1;
                $broadcastedMessage->save();

                $this->pushMessage($broadcastedMessage->id);

                $this->dispatch('broadcastMessageRead');
            }

        }else {
            $this->dispatch('notify', ['user' => ['name' => $event['user']['name']]]);
        }

        $this->dispatch('refreshChatList');
    }

    public function broadcastMessageRead()
    {
        broadcast(new MessageRead(
            $this->selectedChat->id,
            $this->receiverInstance->id,
        ));
    }

    public function pushMessage(int $messageId)
    {
        $newMessage = Message::find($messageId);

        $this->messages->push($newMessage);

        $this->dispatch('rowChatToBottom');
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

        $this->dispatch('chat');
        $this->dispatch('header');

        $this->dispatch('chatSelected');
    }

    public function render()
    {
        return view('livewire.chat.chatbox');
    }
}
