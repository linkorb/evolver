<?php

namespace Evolver\StateStore;

use PDO;

class PdoStateStore implements StateStoreInterface
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getState(string $key): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM state WHERE `key`=:key");
        $stmt->execute([
            'key' => $key,
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return [];
        }
        return json_decode($row['data'], true);
    }

    public function setState(string $key, array $state): void
    {
        // echo "UPSERTING $key\n";
        // print_r($state);
        $stmt = $this->pdo->prepare("INSERT INTO state(`key`, `data`) values(:key, :data) ON DUPLICATE KEY UPDATE `data`=:data");
        $stmt->execute([
            'key' => $key,
            'data' => json_encode($state, JSON_UNESCAPED_SLASHES),
        ]);
    }
}
