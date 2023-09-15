<?php

namespace App\Livewire\Chat;

use App\Events\MarkAsOffline;
use App\Events\MarkAsOnline;
use App\Events\ReceiveMarkAsOnline;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Livewire\Component;
use App\Events\ChatCreate;

class ChatList extends Component
{
    public $auth_id;
    public $chats;
    public $receiverInstance;
    public $name;
    public $selectedChat;
    public $selectedFirstChatFlag = false;

    public function getListeners()
    {
        $auth_id = auth()->user()->id;

        return [
            "echo-private:chat.{$auth_id},ChatCreate" => 'refreshChatList',
            "echo:online,MarkAsOnline" => 'markChatAsOnline',
            "echo:online,MarkAsOffline" => 'markChatAsOffline',
            "echo:online.{$auth_id},ReceiveMarkAsOnline" => 'markReceiveChatAsOnline',
            'chatUserSelected', 'refresh' => '$refresh', 'resetChat', 'refreshChatList', 'sendEventMarkChatAsOffline'
        ];
    }

    public function resetChat(){
        $this->selectedChat = null;
        $this->receiverInstance = null;
    }

    public function markReceiveChatAsOnline($event)
    {
        $user_id = $this->auth_id;

        $chat = Chat::where(function ($query) use ($user_id, $event) {
            $query->where('user_id_first', $user_id)->where('user_id_second', $event['receiver_user_id']);
        })->orWhere(function ($query) use ($user_id, $event) {
            $query->where('user_id_first', $event['receiver_user_id'])->where('user_id_second', $user_id);
        })->first();

        if($chat){
            $this->dispatch('markChatCircleAsOnline', $chat->id);
        }
    }

    public function markChatAsOnline($event)
    {
        $user_id = $this->auth_id;

        $chat = Chat::where(function ($query) use ($user_id, $event) {
            $query->where('user_id_first', $user_id)->where('user_id_second', $event['user_id']);
        })->orWhere(function ($query) use ($user_id, $event) {
            $query->where('user_id_first', $event['user_id'])->where('user_id_second', $user_id);
        })->first();


        if($chat){
            broadcast(event: new ReceiveMarkAsOnline(
                $event['user_id'],
                $user_id));

            $this->dispatch('markChatCircleAsOnline', $chat->id);
        }
    }

    public function markChatAsOffline($event)
    {
        $user_id = $this->auth_id;

        $chat = Chat::where(function ($query) use ($user_id, $event) {
            $query->where('user_id_first', $user_id)->where('user_id_second', $event['user_id']);
        })->orWhere(function ($query) use ($user_id, $event) {
            $query->where('user_id_first', $event['user_id'])->where('user_id_second', $user_id);
        })->first();


        if($chat){
            $this->dispatch('markChatCircleAsOffline', $chat->id);
        }
    }

    public function sendEventMarkChatAsOffline()
    {
        broadcast(event: new MarkAsOffline(
            $this->auth_id));
    }

    public function refreshChatList() {
        $this->chats = Chat::where('user_id_first', $this->auth_id)
            ->orWhere('user_id_second', $this->auth_id)
            ->orderBy('last_time_message', 'DESC')
            ->get();

        $this->dispatch('refresh');
    }

    public function chatUserSelected(Chat $chat, $receiverId)
    {
        $this->selectedChat = $chat;

        $receiverInstance = User::find($receiverId);

        Message::where('chat_id', $chat->id)
            ->where('user_id', $receiverInstance->id)
            ->update(['read_status' => 1]);

        $this->dispatch('loadChat', $this->selectedChat, $receiverInstance);

        $this->dispatch('loadChatData', $this->selectedChat);

        $this->dispatch('updateSendMessage', $this->selectedChat, $receiverInstance);

        $this->dispatch('broadcastMessageRead');
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

        broadcast(event: new MarkAsOnline(
            $this->auth_id));
    }

    public function render()
    {
        return view('livewire.chat.chat-list');
    }
}
