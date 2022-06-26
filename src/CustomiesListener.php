<?php
declare(strict_types=1);

namespace customiesdevs\customies;

use customiesdevs\customies\block\CustomiesBlockFactory;
use customiesdevs\customies\item\CustomiesItemFactory;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\BiomeDefinitionListPacket;
use pocketmine\network\mcpe\protocol\ItemComponentPacket;
use pocketmine\network\mcpe\protocol\ResourcePackStackPacket;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\network\mcpe\protocol\types\BlockPaletteEntry;
use pocketmine\network\mcpe\protocol\types\Experiments;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use function array_merge;

final class CustomiesListener implements Listener {

	private ?ItemComponentPacket $cachedItemComponentPacket = null;
	/**
	 * @var ItemTypeEntry[]
	 */
	private array $cachedItemTable = [];
	/**
	 * @var BlockPaletteEntry[]
	 */
	private array $cachedBlockPalette = [];
	private Experiments $experiments;

	public function __construct() {
		$this->experiments = new Experiments([
			// "data_driven_items" is required for custom blocks to render in-game. With this disabled, they will be
			// shown as the UPDATE texture block.
			"data_driven_items" => true,
		], true);
	}

	public function onDataPacketSend(DataPacketSendEvent $event): void {
		foreach($event->getPackets() as $packet){
			if($packet instanceof BiomeDefinitionListPacket) {
				// ItemComponentPacket needs to be sent after the BiomeDefinitionListPacket.
				if($this->cachedItemComponentPacket === null) {
					// Wait for the data to be needed before it is actually cached. Allows for all blocks and items to be
					// registered before they are cached for the rest of the runtime.
					$this->cachedItemComponentPacket = ItemComponentPacket::create(CustomiesItemFactory::getInstance()->getItemComponentEntries());
				}
				foreach($event->getTargets() as $session){
					$session->sendDataPacket($this->cachedItemComponentPacket);
				}
			} elseif($packet instanceof StartGamePacket) {
				if(count($this->cachedItemTable) === 0) {
					// Wait for the data to be needed before it is actually cached. Allows for all blocks and items to be
					// registered before they are cached for the rest of the runtime.
					$this->cachedItemTable = array_merge($packet->itemTable, CustomiesItemFactory::getInstance()->getItemTableEntries());
					$this->cachedBlockPalette = CustomiesBlockFactory::getInstance()->getBlockPaletteEntries();
				}
				$packet->levelSettings->experiments = $this->experiments;
				$packet->itemTable = $this->cachedItemTable;
				$packet->blockPalette = $this->cachedBlockPalette;
			} else if($packet instanceof ResourcePackStackPacket) {
				$packet->experiments = $this->experiments;
			}
		}
	}
}
