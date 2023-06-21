<?php

namespace customiesdevs\customies\commands;

use customiesdevs\customies\item\CustomiesItemFactory;
use pocketmine\command\CommandSender;
use pocketmine\command\defaults\VanillaCommand;
use pocketmine\lang\Translatable;
use pocketmine\permission\DefaultPermissions;

class GiveItem extends VanillaCommand{
    public function __construct()
    {
        parent::__construct("cgiveitem", "give item from customies plugin", "/give <pseudo> <item> <count>", ["cgi"]);

        $this->setPermission(DefaultPermissions::ROOT_OPERATOR);
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if(count($args) < 2){
            $sender->sendMessage("§cUsage: /cgi <pseudo> <item> <count>");
            return;
        }
        $player = $sender->getServer()->getPlayerByPrefix($args[0]);
        if($player === null){
            $sender->sendMessage("§cPlayer not found");
            return;
        }
        try {
            CustomiesItemFactory::getInstance()->get($args[1]);
        }catch (\Exception $exception){
            $sender->sendMessage("§cItem not found");
            return;
        }
        if (isset($args[2])) {
            if (is_numeric($args[2])) {
                $player->getInventory()->addItem(CustomiesItemFactory::getInstance()->get($args[1], $args[2]));
                $sender->sendMessage("§aItem given");
            } else {
                $sender->sendMessage("§cCount must be a number");
            }
        } else {
            $player->getInventory()->addItem(CustomiesItemFactory::getInstance()->get($args[1]));
        }
    }
}