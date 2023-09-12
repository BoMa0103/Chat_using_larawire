<div>

    <div class="chat-search">
        <label for="default-search"
               class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                </svg>
            </div>
            <input type="search" id="default-search"
                   class="block w-full p-4 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                   placeholder="Search" required>
        </div>
    </div>

    <div class="chat-list-box">

        <ul id="chat-list">
            @foreach($chats as $chat)

                <li class="chat-item" id="chat-item" wire:key='{{$chat->id}}' wire:click="chatUserSelected({{$chat}}, {{$this->getChatUserInstance($chat, $name='id')}})">
                        <div class='chat-info' id="{{$chat->id}}">
                            <img class="w-7 h-7 mr-6 rounded-full" src="/images/alexander-hipp-iEEBWgY_6lA-unsplash.jpg"
                                 alt="User image">
                            <div class='online-circle'></div>
                            <div class='chat-name-last-message'>
                                <p class="chat-name"> {{$this->getChatUserInstance($chat, $name='name')}} </p>
                                <p class="chat-last-message" id="chat-last-message"> {{$chat->messages->last()->value}} </p>
{{--                                <p class=\"chat-last-message-data\" id=\"chat-last-message-data\"> {{$chat->messages->last()->created_at->shortAbsoluteDiffForHumans()}} </p>--}}
                            </div>
{{--                            <div class="chat-unread-messages-count">  1 </div>--}}
                        </div>
                </li>

            @endforeach
        </ul>

    </div>
</div>
