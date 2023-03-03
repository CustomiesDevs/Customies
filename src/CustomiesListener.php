<?php
declare(strict_types=1);

namespace customiesdevs\customies;

use pocketmine\event\{Listener, server\DataPacketSendEvent};
use pocketmine\network\mcpe\protocol\{
    BiomeDefinitionListPacket, ItemComponentPacket, ResourcePackStackPacket, StartGamePacket,
    types\BlockPaletteEntry, types\Experiments, types\ItemTypeEntry
};

use customiesdevs\customies\{block\CustomiesBlockFactory, item\CustomiesItemFactory};

use function array_merge;

final class CustomiesListener implements Listener {

    /** @var ItemComponentPacket|null $cachedItemComponentPacket */
	private ?ItemComponentPacket $cachedItemComponentPacket = null;

	/** @var ItemTypeEntry[] $cachedItemTable */
	private array $cachedItemTable = [];

	/** @var BlockPaletteEntry[] $cachedBlockPalette */
	private array $cachedBlockPalette = [];

    /** @var Experiments $experiments */
	private Experiments $experiments;

	public function __construct() {
		$this->experiments = new Experiments([
			// "data_driven_items" is required for custom blocks to render in-game. With this disabled, they will be
			// shown as the UPDATE texture block.
			"data_driven_items" => true,
		], true);
	}

    /**
     * @param DataPacketSendEvent $event
     * @return void
     *
     * @noinspection PhpUnused
     */
	public function onDataPacketSend(DataPacketSendEvent $event): void {
		foreach($event->getPackets() as $packet){
			if($packet instanceof BiomeDefinitionListPacket) {
				// ItemComponentPacket needs to be sent after the BiomeDefinitionListPacket.

                /**
                 * Wait for the data to be needed before it is actually cached. Allows for all blocks and items to be
                 * registered before they are cached for the rest of the runtime.
                 */
				if($this->cachedItemComponentPacket === null)
                    $this->cachedItemComponentPacket = ItemComponentPacket::create(CustomiesItemFactory::getInstance()->getItemComponentEntries());
				foreach($event->getTargets() as $session)
                    $session->sendDataPacket($this->cachedItemComponentPacket);
			} elseif($packet instanceof StartGamePacket) {
				if(count($this->cachedItemTable) === 0) {
                    /**
                     * Wait for the data to be needed before it is actually cached. Allows for all blocks and items to be
                     * registered before they are cached for the rest of the runtime.
                     */

					$this->cachedItemTable = array_merge($packet->itemTable, CustomiesItemFactory::getInstance()->getItemTableEntries());
					$this->cachedBlockPalette = CustomiesBlockFactory::getInstance()->getBlockPaletteEntries();

				}

				$packet->levelSettings->experiments = $this->experiments;
				$packet->itemTable = $this->cachedItemTable;
				$packet->blockPalette = $this->cachedBlockPalette;

			} else if($packet instanceof ResourcePackStackPacket)
                $packet->experiments = $this->experiments;
		}
	}
}
