<div id="chat" class="chat">

    @if($selectedChat)
        <div class="chat-header">
            <div class="return">
                <i class="bi bi-arrow-left"></i>
            </div>
            {{$receiverInstance->name}}
            <div class="concave-left" id="concave-left">
                <div class='leftconcave'></div>
            </div>
            <div class="concave-right" id="concave-right">
                <div class='rightconcave'></div>
            </div>
        </div>

        <div class="chat-messages" id="messages">
            @foreach($messages as $message)
                <div wire:key='{{$message->id}}' class="message {{auth()->id() == $message->user_id ? 'outgoing' : 'incoming'}}">
{{--                    <div class="message_user"> {{$message->user->name}} </div>--}}
                    <div class="message-content" id="message">
                        <pre> {{$message->value}} </pre>
                    </div>
                    <div class="message-time" id="time"> {{$message->created_at->format('H:i')}} </div>
                </div>
            @endforeach
        </div>

    @else
        @livewire('chat.no-chatbox')
    @endif
</div>

<script>

    document.addEventListener('DOMContentLoaded', function () {
        if(window.innerWidth < 768) {
            $('.chat-list').hide();
            $('.users').hide();
            $('.chat').show();
        }
    });

    window.addEventListener('rowChatToBottom', event => {
        $('.chat-messages').scrollTop($('.chat-messages')[0].scrollHeight);
    });

    $('.chat-messages').scroll(function (){
        let top = $('.chat-messages').scrollTop();
        console.log(top)
        if(top < 1000) {
            window.livewire.dispatch('loadmore');
        }
    });

</script>



