<div class="chat-container">
    <div class="chat-list">
        @livewire('chat.chat-list')
    </div>

    <div class="chat">
        @livewire('chat.chatbox')
        @livewire('chat.send-message')
    </div>

    <div class="users">
        @livewire('chat.user-list')
    </div>

</div>

<script>

    $(window).resize(function (){
        if(window.innerWidth > 768) {
            $('.chat-list').show();
            $('.users').show();
            $('.chat').show();
        }
    });

    $(window).resize(function (){
        if(window.innerWidth < 768) {
            $('.chat-list').hide();
            $('.users').hide();
            $('.chat').show();
        }
    });

    $(document).on('click', '.return', function (){
        $('.chat-list').show();
        $('.users').hide();
        $('.chat').hide();
    });

    window.addEventListener('rowChatToBottom', event => {
        $('.chat-messages').scrollTop($('.chat-messages')[0].scrollHeight);
    });

    $('.chat-messages').scroll(function (){
        let top = $('.chat-messages').scrollTop();
        console.log(top)
        if(top > 1000) {
            window.livewire.dispatch('loadmore');
        }
    })

</script>


