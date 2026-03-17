<?php

namespace zaxelmb\warps\manager;

use pocketmine\world\Position;
use pocketmine\Server;
use pocketmine\utils\Config;

class Warp {
    private string $name;
    private Position $position;

    public function __construct(string $name, Position $position) {
        $this->name = $name;
        $this->position = $position;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getPosition(): Position {
        return $this->position;
    }

    public static function fromFile(string $filePath): ?Warp {
        if (!file_exists($filePath)) {
            return null;
        }

        $data = json_decode(file_get_contents($filePath), true);
        if ($data === null) {
            return null;
        }

        if (isset($data['name'], $data['x'], $data['y'], $data['z'], $data['world'])) {
            $world = Server::getInstance()->getWorldManager()->getWorldByName($data['world']);
            if ($world === null) {
                return null;
            }
            
            $position = new Position($data['x'], $data['y'], $data['z'], $world);

            return new Warp($data['name'], $position);
        }

        return null;
    }
    public function saveToFile(string $filePath): void {
    $directory = dirname($filePath);
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
    if (is_dir($filePath)) {
        $filePath = rtrim($filePath, '/') . '/' . $this->getName() . '.json';
    }

    $data = [
        'name' => $this->getName(),
        'x' => $this->getPosition()->getX(),
        'y' => $this->getPosition()->getY(),
        'z' => $this->getPosition()->getZ(),
        'world' => $this->getPosition()->getWorld()->getFolderName()
    ];
    
    file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
}
}
?>