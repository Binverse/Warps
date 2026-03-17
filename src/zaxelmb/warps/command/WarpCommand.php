<?php

namespace zaxelmb\warps\command;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;
use pocketmine\player\Player;
use zaxelmb\warps\form\WarpsForm;
use zaxelmb\warps\manager\Warp;
use zaxelmb\warps\Loader;

class WarpCommand extends Command {
    private Loader $loader;

    public function __construct(Loader $loader) {
        parent::__construct("warp", "Warps command made by zAxelMB", "/warp help");
        $this->setPermission("warp.command");
        $this->loader = $loader;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "Use this command in-game");
            return false;
        }

        if (!$this->testPermission($sender)) {
            $sender->sendMessage(TextFormat::RED . "You do not have permission to use this command");
            return false;
        }

        if (count($args) < 1) {
            $form = new WarpsForm($this->loader);
            $form->warpsForm($sender);
            return true;
        }

        $subCommand = strtolower($args[0]);
        if($sender->hasPermission("warp.manage")) {
        switch ($subCommand) {
            case "add":
                if (count($args) < 2) {
                    $sender->sendMessage(TextFormat::RED . "Usage: /warp add <name>");
                    return false;
                }

                $warpName = strtolower($args[1]);
                $position = $sender->getPosition();
                $warp = new Warp($warpName, $position);

                if ($this->loader->getWarpManager()->getWarp($warpName) !== null) {
                    $sender->sendMessage(TextFormat::RED . "The warp $warpName already exists.");
                    return false;
                }

                $this->loader->getWarpManager()->addWarp($warp);
                $sender->sendMessage(TextFormat::GREEN . "The warp $warpName has been created successfully!");
                return true;

            case "remove":
                if (count($args) < 2) {
                    $sender->sendMessage(TextFormat::RED . "Usage: /warp remove <name>");
                    return false;
                }

                $warpName = strtolower($args[1]);
                if ($this->loader->getWarpManager()->getWarp($warpName) === null) {
                    $sender->sendMessage(TextFormat::RED . "warp $warpName does not exist");
                    return false;
                }

                $this->loader->getWarpManager()->removeWarp($warpName);
                $sender->sendMessage(TextFormat::GREEN . "The warp $warpName has been successfully removed!");
                return true;

            case "list":
                $warps = $this->loader->getWarpManager()->getAllWarps();
                if (empty($warps)) {
                    $sender->sendMessage(TextFormat::RED . "No warps available.");
                } else {
                    $warpNames = array_keys($warps);
                    $formattedList = implode("\n", $warpNames);
                    $sender->sendMessage("§bList of §gWarps:\n§f" . $formattedList);
                }
                return true;

            case "help":
                $sender->sendMessage("§7---------------------------------------------------------------\n" .
                    "\n§l§gWarps:\n§r" .
                    "§gVersion: 0.0.1\n" .
                    "§gAuthor: zAxelMB\n" .
                    "\n§e/warp add <name> §b- Add a warp at your current location\n" .
                    "§e/warp remove <name> §b- Remove the specified warp\n" .
                    "§e/warp list §b- Show all warps\n".
                    "§e/warp help §b- Displays all SubCommands and their functions\n" .
                    "\n§7---------------------------------------------------------------"
                    );
                return true;

            default:
                $sender->sendMessage(TextFormat::RED . "Invalid SubCommand, use §e/warp help");
                return false;
        }
    } else {
      $sender->sendMessage(TextFormat::RED . "You do not have permission to use this command");
    }
    }
}
?>