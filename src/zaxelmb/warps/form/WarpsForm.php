<?php

namespace zaxelmb\warps\form;

use pocketmine\player\Player;
use jojoe77777\FormAPI\SimpleForm;
use zaxelmb\warps\Loader;

class WarpsForm {
    private Loader $loader;

    public function __construct(Loader $loader) {
        $this->loader = $loader;
    }

    public function warpsForm(Player $player): void {
        $form = new SimpleForm(function (Player $player, ?int $data) {
            if ($data === null || $data === -1) {
                return;
            }
            
            $warps = $this->loader->getWarpManager()->getAllWarps();
            $warpNames = array_keys($warps);
            $warpName = $warpNames[$data];
            $warp = $this->loader->getWarpManager()->getWarp($warpName);
            if ($warp !== null) {
                $player->teleport($warp->getPosition());
                $player->sendMessage("§aYou have been successfully teleported to the warp $warpName");
            } else {
                $player->sendMessage("§cThe warp $warpName does not exist");
            }
        });

        $form->setTitle("§l§gWarps");
        $form->setContent("§aSelect a warp");

        $warps = $this->loader->getWarpManager()->getAllWarps();
        foreach ($warps as $warpName => $warp) {
            $worldName = $warp->getPosition()->getWorld()->getFolderName();
            $playerCount = $this->getPlayersInWorld($worldName);

            $form->addButton(ucfirst($warpName) . "\n §8(§c$playerCount §eplayers§8)");
        }

        $player->sendForm($form);
    }

    public function getPlayersInWorld(string $worldName): int {
        $count = 0;
        foreach ($this->loader->getServer()->getOnlinePlayers() as $player) {
            if ($player->getWorld()->getFolderName() === $worldName) {
                $count++;
            }
        }
        return $count;
    }
}
?>