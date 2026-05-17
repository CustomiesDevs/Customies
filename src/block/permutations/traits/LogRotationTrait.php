<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\permutations\traits;

use customiesdevs\customies\block\component\TransformationComponent;
use customiesdevs\customies\block\permutations\BlockPermutation;
use customiesdevs\customies\block\permutations\BlockPermutationsTrait;
use customiesdevs\customies\block\states\BlockState;
use pocketmine\block\Block;
use pocketmine\block\utils\PillarRotationTrait;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\item\Item;
use pocketmine\math\Axis;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

/**
 * Block rotation identical to how vanilla logs rotate.
 * - Used by logs and basalt
 * - 3 axis-aligned directions
 */
trait LogRotationTrait {
	use BlockPermutationsTrait;
	use PillarRotationTrait;

	protected function initStates(): void {
		$this->addState(new BlockState("minecraft:block_face",
			["down", "up","north", "south", "east", "west"]
		));
	}

	protected function initPermutations(): void {
		$this->addPermutations([
			new BlockPermutation(
				"q.block_state('minecraft:block_face') == 'west' || q.block_state('minecraft:block_face') == 'east'",
				new TransformationComponent(new Vector3(0, 0, 90))
			),
			new BlockPermutation(
				"q.block_state('minecraft:block_face') == 'down' || q.block_state('minecraft:block_face') == 'up'",
				new TransformationComponent(new Vector3(0, 0, 0))
			),
			new BlockPermutation(
				"q.block_state('minecraft:block_face') == 'north' || q.block_state('minecraft:block_face') == 'south'",
				new TransformationComponent(new Vector3(90, 0, 0))
			)
		]);
	}

	public function getCurrentStates(): array {
		return [$this->axis];
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool {
		$this->axis = match($face) {
			0, 1 => Axis::Y,  // down, up
			2, 3 => Axis::Z,  // north, south
			4, 5 => Axis::X,  // west, east
			default => Axis::Y
		};
		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function serializeState(BlockStateWriter $out): void {
		$rotation = match($this->axis) {
			Axis::X => "east",
			Axis::Y => "up",
			Axis::Z => "north",
			default => "down"
		};
		$out->writeString("minecraft:block_face", $rotation);
	}

	public function deserializeState(BlockStateReader $in): void {
		$this->axis = match($in->readString("minecraft:block_face")) {
			"east", "west" => Axis::X,
			"up", "down" => Axis::Y,
			"north", "south" => Axis::Z,
			default => Axis::Y
		};
	}
}