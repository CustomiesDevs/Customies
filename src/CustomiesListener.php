<?php
declare(strict_types=1);

namespace customies;

use customies\block\CustomiesBlockFactory;
use customies\item\CustomiesItemFactory;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\BiomeDefinitionListPacket;
use pocketmine\network\mcpe\protocol\ItemComponentPacket;
use pocketmine\network\mcpe\protocol\ResourcePackStackPacket;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\network\mcpe\protocol\types\BoolGameRule;
use pocketmine\network\mcpe\protocol\types\Experiments;
use function array_merge;

class CustomiesListener implements Listener {

	private ?ItemComponentPacket $cachedItemComponentPacket = null;
	private Experiments $experiments;

	public function __construct() {
		$this->experiments = new Experiments([
			"data_driven_items" => true,
			"experimental_molang_features" => true,
			"scripting" => true
		], true);
	}

	public function onDataPacketSend(DataPacketSendEvent $event): void {
		foreach($event->getPackets() as $packet){
			if($packet instanceof BiomeDefinitionListPacket) {
				if($this->cachedItemComponentPacket === null) {
					$this->cachedItemComponentPacket = ItemComponentPacket::create(CustomiesItemFactory::getCachedItemProperties());
				}
				foreach($event->getTargets() as $session){
					$session->sendDataPacket($this->cachedItemComponentPacket);
				}
//            }
//            if ($packet instanceof AvailableActorIdentifiersPacket) {
//                /** @var CompoundTag $root */
//                $root = $packet->identifiers->getRoot();
//                /** @var ListTag $vanilla */
//                $vanilla = $root->getTag("idlist")->getValue();
				/*$nbt = CompoundTag::create()->setTag("idlist", new ListTag(array_merge($vanilla->getValue(), CustomiesEntityFactory::getAvailableActorIdentifiers())));
				$packet->identifiers = new CacheableNbt($nbt);*/
			} elseif($packet instanceof StartGamePacket) {
				$packet->levelSettings->gameRules["experimentalgameplay"] = new BoolGameRule(true, false);
				$packet->levelSettings->experiments = $this->experiments;
				$packet->itemTable = array_merge($packet->itemTable, CustomiesItemFactory::getItemTableEntries());
				$packet->blockPalette = CustomiesBlockFactory::getBlockPaletteEntries();
			} else if($packet instanceof ResourcePackStackPacket) {
				$packet->experiments = $this->experiments;
			}
		}
	}
}