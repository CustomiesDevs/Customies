<?php
declare(strict_types=1);

namespace customiesdevs\customies;

use customiesdevs\customies\block\CustomiesBlockFactory;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\ResourcePackStackPacket;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\network\mcpe\protocol\types\BlockPaletteEntry;
use pocketmine\network\mcpe\protocol\types\Experiments;
use function count;

final class CustomiesListener implements Listener {
	/** @var BlockPaletteEntry[] */
	private array $cachedBlockPalette = [];
	private Experiments $experiments;

	public function __construct() {
		$this->experiments = new Experiments([
			"data_driven_items" => true,
		], true);
	}

	public function onDataPacketSend(DataPacketSendEvent $event): void {
		foreach($event->getPackets() as $packet){
			if($packet instanceof StartGamePacket) {
				if(count($this->cachedBlockPalette) === 0) {
					$this->cachedBlockPalette = CustomiesBlockFactory::getInstance()->getBlockPaletteEntries();
				}
				$packet->levelSettings->experiments = $this->experiments;
				$packet->blockPalette = $this->cachedBlockPalette;
			} elseif($packet instanceof ResourcePackStackPacket) {
				$packet->experiments = $this->experiments;
			}
		}
	}

    public function onPlayerCreation(PlayerCreationEvent $event): void
    {
        $event->setPlayerClass(CustomiesPlayer::class);
    }
}
