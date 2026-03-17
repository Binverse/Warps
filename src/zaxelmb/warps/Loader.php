<?php

namespace zaxelmb\warps;

use zaxelmb\warps\command\WarpCommand;
use zaxelmb\warps\manager\Warp;
use zaxelmb\warps\manager\WarpManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\Server;

class Loader extends PluginBase {
  
  private static Loader $instance;
  private WarpManager $warpManager;
  
  public function onEnable(): void {
      self::$instance = $this;
      $this->warpManager = new WarpManager($this->getDataFolder());
  $this->getServer()->getCommandMap()->register("warp", new WarpCommand($this));
    $this->getLogger()->info("Warps plugin enabled");
  }
  public function onDisable(): void {
    $this->getLogger()->info("Warps plugin disabled");
  }
  public static function getInstance(): Loader {
        return self::$instance;
    }
    public function getWarpManager(): WarpManager {
    return $this->warpManager;
}
}
?>