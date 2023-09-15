function markChatAsOnline(chat_id) {
    let chat = document.getElementById(chat_id);

    if (!chat) {
        return;
    }

    let circleDiv = chat.getElementsByClassName('online-circle')[0];

    circleDiv.style.display = 'block';
}

function markChatAsOffline(chat_id) {
    let chat = document.getElementById(chat_id);
    let circleDiv = chat.getElementsByClassName('online-circle')[0];

    circleDiv.style.display = 'none';
}


function markSelectedChat(li) {
    li.classList.add('selected');


    if (li.classList.contains('firstChat')) {
        document.getElementById('concave-left').style.display = 'none';
        document.getElementById('messages').style.borderTopLeftRadius = '0';
    } else {
        document.getElementById('concave-left').style.display = 'flex';
    }
}

/* Scroll */

function scrollToBottom() {
    let chatMessages = document.getElementById('messages');


    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function scrollToCurrentMessage() {
    let chatMessages = document.getElementById('messages');

    // chatMessages.scrollTop = chatMessages.scrollHeight - previousScrollHeight;
}


/* Notifications */


function notifyMe(json) {
    if (!("Notification" in window)) {
        alert("This browser does not support desktop notification");
    } else if (Notification.permission === "granted") {
        const notification = new Notification("Chat", {
            body: 'New message from ' + json.user.name,
            icon: '/images/free-icon-chat-bubble-6068634.png',
        });
    } else if (Notification.permission !== "denied") {
        Notification.requestPermission().then((permission) => {
            if (permission === "granted") {
                const notification = new Notification("Chat", {
                    body: 'New message from ' + json.user.name,
                    icon: '/images/free-icon-chat-bubble-6068634.png',
                });
            }
        });
    }
}


/* Events */

window.addEventListener("DOMContentLoaded", (event) => {
    let chatMessages = document.getElementById('messages');
    let textArea = document.getElementById('text');
    let sendButton = document.getElementById('send');
    let search = document.getElementById('default-search');

    textArea.addEventListener('input', function () {
        if (textArea.value.trim() !== '') {
            sendButton.removeAttribute('disabled');
        } else {
            sendButton.setAttribute('disabled', 'disabled');
        }
    });

    // chatMessages.addEventListener('scroll', function () {
    //     const scrollTop = chatMessages.scrollTop;
    //     const scrollHeight = chatMessages.scrollHeight;
    //
    //     console.log('sdfsd');
    //
    //
    //     if (scrollTop === 0 && loadMessagesCount !== 0) {
    //         previousScrollHeight = scrollHeight;
    //         socket.send('{"message": "require_messages_history", "load_messages_count": "' + loadMessagesCount + '", "default_messages_count_load": "' + DEFAULT_MESSAGES_COUNT_LOAD + '"}');
    //     }
    // });

    // textArea.addEventListener('input', function () {
    //     if (textArea.value.trim() !== '') {
    //         sendButton.removeAttribute('disabled');
    //     } else {
    //         sendButton.setAttribute('disabled', 'disabled');
    //     }
    // });

    search.addEventListener('input', function () {

    })
});



