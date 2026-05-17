<?php
declare(strict_types=1);

namespace customiesdevs\customies\util;

use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\Tag;
use function array_keys;
use function array_map;
use function count;
use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function is_string;
use function range;

final class NBT {

	/**
	 * Attempts to return the correct NBT Tag for the provided PHP value.
	 * Supported conversions:
	 * - array      → ListTag or CompoundTag
	 * - bool       → ByteTag
	 * - float      → FloatTag
	 * - int        → IntTag
	 * - string     → StringTag
	 * - Tag        → Returned as-is
	 *
	 * @param mixed $type The value to convert into an NBT Tag
	 * @return Tag|null Returns the corresponding Tag instance, or null if the
	 *                  type cannot be converted.
	 */
	public static function getTagType($type): ?Tag {
		return match (true){
			$type instanceof Tag => $type,
			is_array($type) => self::getArrayTag($type),
			is_bool($type) => new ByteTag($type ? 1 : 0),
			is_float($type) => new FloatTag($type),
			is_int($type) => new IntTag($type),
			is_string($type) => new StringTag($type),
			default => null,
		};
	}

	/**
	 * Creates an NBT Tag from an array.
	 * - If the array uses sequential numeric keys (0..n), a ListTag is created.
	 * - Otherwise, a CompoundTag is created with each key mapped to a Tag.
	 * @param array $array The array to convert into an NBT Tag
	 * @return Tag Returns either a ListTag or CompoundTag depending on the array structure
	 * @throws \InvalidArgumentException If any value cannot be converted to a Tag
	 */
	private static function getArrayTag(array $array): Tag {
		if(array_keys($array) === range(0, count($array) - 1)){
			return new ListTag(array_map(function($value){
				$tag = self::getTagType($value);
				if($tag === null) {
					throw new \InvalidArgumentException("Cannot convert value of type " . get_debug_type($value) . " to NBT Tag");
				}
				return $tag;
			}, $array));
		}
		$tag = CompoundTag::create();
		foreach($array as $key => $value){
			$valueTag = self::getTagType($value);
			if($valueTag === null) {
				throw new \InvalidArgumentException("Cannot convert value of type " . get_debug_type($value) . " for key '$key' to NBT Tag");
			}
			$tag->setTag((string) $key, $valueTag);
		}
		return $tag;
	}

	public static function sortCompoundTag(CompoundTag $tag, array $order): CompoundTag {
		$sorted = CompoundTag::create();
		foreach($order as $key){
			$existing = $tag->getTag($key);
			if($existing !== null){
				$sorted->setTag($key, $existing);
			}
		}
		foreach($tag->getValue() as $key => $value){
			if($sorted->getTag((string) $key) === null){
				$sorted->setTag((string) $key, $value);
			}
		}
		return $sorted;
	}
}