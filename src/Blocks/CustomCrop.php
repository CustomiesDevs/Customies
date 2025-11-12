<?php

namespace customiesdevs\customies\Blocks;

use customiesdevs\customies\Blocks\Material;
use customiesdevs\customies\Blocks\Permutations\BlockProperty;
use customiesdevs\customies\Blocks\Permutations\Permutable;
use customiesdevs\customies\Blocks\Permutations\Permutation;
use pocketmine\block\Crops;
use pocketmine\data\bedrock\block\convert\BlockStateReader;
use pocketmine\data\bedrock\block\convert\BlockStateWriter;
use pocketmine\data\runtime\RuntimeDataDescriber;
use pocketmine\item\Fertilizer;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;

class CustomCrop extends Crops implements Permutable
{

    protected int $age = 0;
    protected int $ageMax = 2;
    protected string $baseGeo = "geometry.crop";
    protected string $baseTex = "crop";

    public function getBlockProperties(): array
    {
        return [
            new BlockProperty("minecraft:age", range(0, $this->ageMax))
        ];
    }

    public function getPermutations(): array
    {
        $permutations = [];

        foreach (range(0, $this->ageMax) as $i) {
            $geometry = "{$this->baseGeo}_$i";
            $texture = "{$this->baseTex}_$i";
            $condition = "q.block_property('minecraft:age') == {$i}";

            $permutations[] = (new Permutation($condition))
                ->withComponent("minecraft:geometry", CompoundTag::create()
                    ->setString("identifier", $geometry))
                ->withComponent("minecraft:material_instances", CompoundTag::create()
                    ->setTag("mappings", CompoundTag::create())
                    ->setTag("materials", CompoundTag::create()
                        ->setTag("*", CompoundTag::create()
                            ->setString("texture", $texture)
                            ->setString("render_method", Material::RENDER_METHOD_ALPHA_TEST)
                            ->setByte("face_dimming", 1)
                            ->setByte("ambient_occlusion", 1)
                        )
                    )
                );
        }
        return $permutations;
    }

    public function getCurrentBlockProperties(): array
    {
        return ["minecraft:age" => $this->age];
    }

    public function serializeState(BlockStateWriter $out): void
    {
        $out->writeInt("minecraft:age", $this->age);
    }

    public function deserializeState(BlockStateReader $in): void
    {
        $this->age = $in->readInt("minecraft:age");
    }

    public function describeBlockOnlyState(RuntimeDataDescriber $w): void
    {
        if ($this->age < 0 || $this->age > $this->getMaxStage()) {
            $newStage = min($this->getStage(), $this->getMaxStage());
            $w->boundedIntAuto(0, $this->ageMax, $newStage);
            return;
        }
        $w->boundedIntAuto(0, $this->ageMax, $this->age);
    }

    protected function writeStateToMeta(): int
    {
        return $this->age;
    }

    public function getStage(): int
    {
        return $this->age;
    }

    public function setStage(int $stage): void
    {
        if ($stage < 0 || $stage > $this->getMaxStage()) {
            return;
        }
        $this->age = $stage;
    }

    public function getMaxStage(): int
    {
        return $this->ageMax;
    }

    public function setStageMax(int $max): void
    {
        $this->ageMax = $max;
    }

    public function setBaseGeometry(string $geo): void
    {
        $this->baseGeo = $geo;
    }

    public function setBaseTexture(string $texture): void
    {
        $this->baseTex = $texture;
    }

    public function withStage(int $stage): self
    {
        $clone = clone $this;

        if ($stage <= $this->getMaxStage()) {
            $clone->setStage($stage);
        }
        return $clone;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null, array &$returnedItems = []): bool
    {
        if ($item instanceof Fertilizer && $this->getStage() < $this->getMaxStage()) {
            // $this->getPosition()->getWorld()->addParticle($this->getPosition(), new particle);
            $rand = mt_rand(0, 5);

            if ($rand === 0) {
                $newStage = min($this->getStage() + 1, $this->getMaxStage());

                $newBlock = $this->withStage($newStage);

                $this->position->getWorld()->setBlock($this->position, $newBlock, false);
                return true;
            }
            $item->pop();
            $player->getInventory()->setItemInHand($item);
            return false;
        }
        return false;
    }

    public function onRandomTick(): void
    {
        if ($this->getStage() < $this->getMaxStage()) {
            $newStage = min($this->getStage() + 1, $this->getMaxStage());

            $newBlock = $this->withStage($newStage);

            $this->position->getWorld()->setBlock($this->position, $newBlock, false);
        }
    }
}