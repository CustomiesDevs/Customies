<?php
declare(strict_types=1);

namespace customies\item;

use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\Tag;

trait ItemComponentsTrait
{

    /**
     * Tag for storing item components
     * @var CompoundTag
     */
    private $componentTag;

    /**
     * @param string $texture
     * @param int $maxStackSize
     */
    protected function initComponentTag(string $texture, int $maxStackSize)
    {
        //this initializes the Compound Tag format, The format must be exactly like this or it will not work
        $this->componentTag = CompoundTag::create()
            ->setTag("components", CompoundTag::create()
                ->setTag("item_properties", CompoundTag::create()
	                ->setTag("minecraft:icon", CompoundTag::create()
		                ->setString("texture", $texture)
	                )
                    ->setInt("max_stack_size", $maxStackSize)
                )
            );
    }

    /**
     * @param string $key
     * @param $value
     * This field is used to add simple properties to the item, most usage will be done here, In the case
     * of a resource pack file, I believe all fields under the "components" property in a resource pack .json
     * would go here.
     */
    public function addProperty(string $key, $value)
    {
        $propertiesTag = $this->componentTag->getTag("components")->getTag("item_properties");
        $type = self::getType($value);
        if ($type !== null) $propertiesTag->setTag($key, $type);
    }

    /**
     * @param string $key
     * @param CompoundTag $tag
     * This adds major components to an item, In a resource pack sense, this is where the major fields such as
     * "minecraft:icon" go, it only accepts CompoundTags.
     */
    public function addComponent(string $key, CompoundTag $tag){
        $componentsTag = $this->componentTag->getTag("components");
        $componentsTag->setTag($key, $tag);
    }

    /**
     * @return CompoundTag
     */
    public function getComponents(): CompoundTag{
        return $this->componentTag;
    }

    /**
     * @param $type
     * @return Tag|null
     * This will most likely need to be updated to make it better in the future, but this should work for now
     */
    public static function getType($type): ?Tag
    {
        switch (true) {
            case is_bool($type):
                return new ByteTag((int)$type);
            case is_float($type):
                return new FloatTag($type);
            case is_int($type):
                return new IntTag($type);
            case is_string($type):
                return new StringTag($type);
            case is_array($type):
                $v = [];
                foreach ($type as $item){
                    $v[] = new FloatTag($item);
                }
                return new ListTag($v);
        }
        if ($type instanceof CompoundTag){
            return $type;
        }
        return null;
    }
}