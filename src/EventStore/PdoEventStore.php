<?php

namespace Evolver\EventStore;

use Evolver\Event;
use PDO;

class PdoEventStore implements EventStoreInterface
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function addEvent(Event $event)
    {
        $stmt = $this->pdo->prepare("INSERT INTO event(type, payload, meta) values(:type, :payload, :meta)");
        $stmt->execute([
            'type' => $event->getType(),
            'payload' => json_encode($event->getPayload(), JSON_UNESCAPED_SLASHES),
            'meta' => json_encode($event->getMeta(), JSON_UNESCAPED_SLASHES),
        ]);
        // print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getEvent(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM event WHERE id=:id");
        $stmt->execute([
            'id' => $id,
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $event = new Event(
            $row['type'],
            json_decode($row['payload'], true),
            json_decode($row['meta'], true)
        );
        return $event;

    }

    public function getMaxEventId(): int
    {
        $stmt = $this->pdo->prepare("SELECT max(id) as m FROM event");
        $stmt->execute([]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['m'];
    }
}
