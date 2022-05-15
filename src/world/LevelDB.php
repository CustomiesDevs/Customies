<?php
declare(strict_types=1);

namespace customies\world;

use customies\block\CustomiesBlockFactory;
use InvalidArgumentException;
use LevelDBWriteBatch;
use pocketmine\block\Block;
use pocketmine\block\BlockLegacyIds;
use pocketmine\nbt\LittleEndianNbtSerializer;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\TreeRoot;
use pocketmine\utils\BinaryStream;
use pocketmine\world\format\Chunk;
use pocketmine\world\format\io\ChunkData;
use pocketmine\world\format\io\exception\CorruptedChunkException;
use pocketmine\world\format\PalettedBlockArray;
use function array_flip;
use function array_map;
use function array_merge;
use function chr;
use function count;
use function file_get_contents;
use function json_decode;
use function str_repeat;
use const pocketmine\BEDROCK_DATA_PATH;
use const pocketmine\RESOURCE_PATH;

class LevelDB extends \pocketmine\world\format\io\leveldb\LevelDB {

	/**
	 * deserializePaletted is copied from pocketmine/world/format/io/leveldb/LevelDB.deserializePaletted() but changes
	 * the LegacyBlockIdToStringIdMap instance to support loading custom blocks from the world.
	 */
	protected function deserializePaletted(BinaryStream $stream): PalettedBlockArray {
		$bitsPerBlock = $stream->getByte() >> 1;

		try {
			$words = $stream->get(PalettedBlockArray::getExpectedWordArraySize($bitsPerBlock));
		} catch(InvalidArgumentException $e) {
			throw new CorruptedChunkException("Failed to deserialize paletted storage: " . $e->getMessage(), 0, $e);
		}
		$nbt = new LittleEndianNbtSerializer();
		$palette = [];
		$idMap = LegacyBlockIdToStringIdMap::getInstance();
		for($i = 0, $paletteSize = $stream->getLInt(); $i < $paletteSize; ++$i){
			$offset = $stream->getOffset();

			$tag = $nbt->read($stream->getBuffer(), $offset)->mustGetCompoundTag();
			$stream->setOffset($offset);

			$id = $idMap->stringToLegacy($tag->getString("name")) ?? BlockLegacyIds::INFO_UPDATE;
			$data = $tag->getShort("val");
			$palette[] = ($id << Block::INTERNAL_METADATA_BITS) | $data;
		}

		return PalettedBlockArray::fromData($bitsPerBlock, $words, $palette);
	}

	/**
	 * saveChunk is copied from pocketmine/world/format/io/leveldb/LevelDB.saveChunk() but changes the
	 * LegacyBlockIdToStringIdMap instance to support storing custom blocks in the world.
	 */
	public function saveChunk(int $chunkX, int $chunkZ, ChunkData $chunkData): void {
		$idMap = LegacyBlockIdToStringIdMap::getInstance();
		$index = \pocketmine\world\format\io\leveldb\LevelDB::chunkIndex($chunkX, $chunkZ);

		$write = new LevelDBWriteBatch();
		$write->put($index . self::TAG_VERSION, chr(self::CURRENT_LEVEL_CHUNK_VERSION));

		$chunk = $chunkData->getChunk();
		if($chunk->getTerrainDirtyFlag(Chunk::DIRTY_FLAG_BLOCKS)) {
			$subChunks = $chunk->getSubChunks();
			foreach($subChunks as $y => $subChunk){
				$key = $index . self::TAG_SUBCHUNK_PREFIX . chr($y);
				if($subChunk->isEmptyAuthoritative()) {
					$write->delete($key);
				} else {
					$subStream = new BinaryStream();
					$subStream->putByte(self::CURRENT_LEVEL_SUBCHUNK_VERSION);

					$layers = $subChunk->getBlockLayers();
					$subStream->putByte(count($layers));
					foreach($layers as $blocks){
						$subStream->putByte($blocks->getBitsPerBlock() << 1);
						$subStream->put($blocks->getWordArray());

						$palette = $blocks->getPalette();
						$subStream->putLInt(count($palette));
						$tags = [];
						foreach($palette as $p){
							$tags[] = new TreeRoot(CompoundTag::create()
								->setString("name", $idMap->legacyToString($p >> Block::INTERNAL_METADATA_BITS) ?? "minecraft:info_update")
								->setInt("oldid", $p >> Block::INTERNAL_METADATA_BITS) //PM only (debugging), vanilla doesn't have this
								->setShort("val", $p & Block::INTERNAL_METADATA_MASK));
						}

						$subStream->put((new LittleEndianNbtSerializer())->writeMultiple($tags));
					}

					$write->put($key, $subStream->getBuffer());
				}
			}
		}

		if($chunk->getTerrainDirtyFlag(Chunk::DIRTY_FLAG_BIOMES)) {
			$write->put($index . self::TAG_DATA_2D, str_repeat("\x00", 512) . $chunk->getBiomeIdArray());
		}

		$write->put($index . self::TAG_STATE_FINALISATION, chr(self::FINALISATION_DONE));

		$this->writeTags($chunkData->getTileNBT(), $index . self::TAG_BLOCK_ENTITY, $write);
		$this->writeTags($chunkData->getEntityNBT(), $index . self::TAG_ENTITY, $write);

		$write->delete($index . self::TAG_DATA_2D_LEGACY);
		$write->delete($index . self::TAG_LEGACY_TERRAIN);

		$this->db->write($write);
	}

	/**
	 * This method is copied from pocketmine/world/format/io/leveldb/LevelDB.writeTags() since it is private.
	 */
	private function writeTags(array $targets, string $index, \LevelDBWriteBatch $write): void {
		if(count($targets) > 0) {
			$nbt = new LittleEndianNbtSerializer();
			$write->put($index, $nbt->writeMultiple(array_map(fn(CompoundTag $tag) => new TreeRoot($tag), $targets)));
		} else {
			$write->delete($index);
		}
	}
}