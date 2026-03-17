<?php

namespace zaxelmb\warps\manager;

use pocketmine\utils\Config;
use zaxelmb\warps\manager\Warp;

class WarpManager {
    private string $directory;
    private array $warps = [];

    public function __construct(string $dataFolder) {
        $this->directory = $dataFolder . "warps/";
        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0777, true);
        }
        $this->loadWarps();
    }
    
    private function loadWarps(): void {
        foreach (glob($this->directory . "*.json") as $file) {
            $warp = Warp::fromFile($file);
            if ($warp !== null) {
                $this->warps[$warp->getName()] = $warp;
            }
        }
    }

    public function addWarp(Warp $warp): void {
        $this->warps[$warp->getName()] = $warp;
        $warp->saveToFile($this->directory);
    }

    public function getWarp(string $name): ?Warp {
        return $this->warps[$name] ?? null;
    }

    public function removeWarp(string $name): void {
        unset($this->warps[$name]);
        $filePath = $this->directory . $name . ".json";
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function getAllWarps(): array {
        return $this->warps;
    }
}
?>