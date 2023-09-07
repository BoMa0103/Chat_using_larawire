<?php

namespace App\Services\Websocket\Handlers\Chats;

use App\Services\Websocket\Handlers\BaseHandler;
use Ratchet\ConnectionInterface;

class SelectChatHandler extends BaseHandler
{
    public function handle(ConnectionInterface $from, int $chatId): void
    {
        $userId = $this->connectedUsersId [$from->resourceId];

        $this->getWebsocketService()->storeChatIdForUser($userId, $chatId);

        $message = [
            'message' => 'chat_selected',
        ];

        $from->send(json_encode($message));
    }
}
