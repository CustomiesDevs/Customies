<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\permutations\traits;

use customiesdevs\customies\block\component\TransformationComponent;
use customiesdevs\customies\block\permutations\BlockPermutation;
use customiesdevs\customies\block\permutations\BlockPermutationsTrait;
use customiesdevs\customies\block\states\BlockState;
use pocketmine\block\Block;
use pocketmine\block\utils\AnyFacingTrait;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;

/**
 * Trait for blocks that can face any of 6 directions (block_face state).
 * - Used by ladders and item frames
 * - 6 directions - 'down', 'up', 'north', 'south', 'east' and 'west'.
 */
trait BlockFaceRotationTrait {
	use BlockPermutationsTrait;
	use AnyFacingTrait;

	protected function initStates(): void {
		$this->addState(new BlockState("minecraft:block_face",
			["down", "up", "north", "south", "east", "west"]
		));
	}

	protected function initPermutations(): void {
		$this->addPermutations([
			new BlockPermutation(
				"q.block_state('minecraft:block_face') == 'down'",
				new TransformationComponent(new Vector3(90, 0, 0))
			),
			new BlockPermutation(
				"q.block_state('minecraft:block_face') == 'up'",
				new TransformationComponent(new Vector3(-90, 0, 0))
			),
			new BlockPermutation(
				"q.block_state('minecraft:block_face') == 'north'",
				new TransformationComponent(new Vector3(0, 0, 0))
			),
			new BlockPermutation(
				"q.block_state('minecraft:block_face') == 'south'",
				new TransformationComponent(new Vector3(0, 180, 0))
			),
			new BlockPermutation(
				"q.block_state('minecraft:block_face') == 'west'",
				new TransformationComponent(new Vector3(0, 90, 0))
			),
			new BlockPermutation(
				"q.block_state('minecraft:block_face') == 'east'",
				new TransformationComponent(new Vector3(0, -90, 0))
			),
		]);
	}

	public function getCurrentStates(): array {
		return [$this->facing];
	}

	public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool {
		$this->facing = $face;
		return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
	}

	public function serializeState(BlockStateWriter $out): void {
		$out->writeString(
			"minecraft:block_face",
			match ($this->facing) {
				Facing::DOWN => "down",
				Facing::UP => "up",
				Facing::NORTH => "north",
				Facing::SOUTH => "south",
				Facing::WEST => "west",
				Facing::EAST => "east",
			}
		);
	}

	public function deserializeState(BlockStateReader $in): void {
		$this->facing = match ($in->readString("minecraft:block_face")) {
			"down" => Facing::UP,
			"up" => Facing::DOWN,
			"north" => Facing::NORTH,
			"south" => Facing::SOUTH,
			"west" => Facing::WEST,
			"east" => Facing::EAST,
		};
	}
}