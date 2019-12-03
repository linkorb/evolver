<?php

namespace Evolver\Example;

use Evolver\Event;
use Evolver\ProjectorInterface;
use Evolver\Evolver;

class StreamProjector implements ProjectorInterface
{
    protected $evolver;

    public function __construct(Evolver $evolver)
    {
        $this->evolver = $evolver;
    }

    public function getState(string $key): array
    {
        return $this->evolver->getState($key);
    }

    public function setState(string $key, array $state): void
    {
        $this->evolver->setState($key, $state);
    }

    public static function getConfig()
    {
        return [
            'name' => 'stream-projector',
            'events' => [
                'stream:create',
                'stream:add-user',
                'stream:message',
                'stream:react',
            ]
        ];
    }

    public function project(Event $event)
    {
        $payload = $event->getPayload();
        $meta = $event->getMeta();
        $streamId = $payload['streamId'] ?? null;
        $streamKey = 'stream:' . $streamId;
        
        switch ($event->getType()) {
            case 'stream:create':
                $state = $this->getState($streamKey);
                $state['users'] = [];
                $state['messages'] = [];
                $user = $payload['user'];
                if ($user) {
                    $state['users'][] = $user;
                }
                $state['title'] = $payload['title'] ?? 'No title';
                $message = [
                    'type' => 'system',
                    'id' => $payload['messageId'] ?? null,
                    'createdAt' => $meta['createdAt'],
                    'text' => $payload['user'] . ' created stream titled "' . $state['title'] . '"',
                ];
                $state['messages'][] = $message; 
                $this->setState($streamKey, $state);
                break;
            case 'stream:add-user':
                $newUser = $payload['newUser'];
                $state = $this->getState($streamKey);
                $streamState = $state;
                if (!in_array($newUser, $state['users'])) {
                    $state['users'][] = $newUser;
                }

                $message = [
                    'type' => 'system',
                    'id' => $payload['messageId'] ?? null,
                    'createdAt' => $meta['createdAt'],
                    'text' => $payload['user'] . ' added ' . $newUser . ' to the stream',
                ];
                $state['messages'][] = $message;
                $this->setState($streamKey, $state);

                //
                $userKey = 'user:' . $newUser;
                $state = $this->getState($userKey);
                $state['streams'][$streamId] = [
                    'title' => $streamState['title']
                ];
                $this->setState($userKey, $state);

                break;
            case 'stream:message':
                $state = $this->getState($streamKey);
                $messageId = $payload['messageId'] ?? null;
                $message = [
                    'type' => 'user',
                    'id' => $messageId,
                    'createdAt' => $meta['createdAt'],
                    'user' => $payload['user'],
                    'text' => $payload['text'],
                ];
                $state['messages'][] = $message;
                $this->setState($streamKey, $state);
                break;
    

        }
    }
}
