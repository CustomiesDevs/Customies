<?php
declare(strict_types=1);

namespace customiesdevs\customies\block\properties;

use pocketmine\nbt\tag\ByteTag;

final class Material {

	public const TARGET_ALL = "*";
	public const TARGET_SIDES = "sides";
	public const TARGET_UP = "up";
	public const TARGET_DOWN = "down";
	public const TARGET_NORTH = "north";
	public const TARGET_EAST = "east";
	public const TARGET_SOUTH = "south";
	public const TARGET_WEST = "west";

	/** Enables face shading/dimming based on lighting direction */
	public const FACE_DIMMING = 1;
	/** Enables randomized UV rotation */
	public const RANDOMIZE_UV_ROTATION = 2;
	/** Indicates support for texture variation */
	public const SUPPORTS_TEXTURE_VARIATION = 4;

	/**
	 * Packed material flags stored as a bitmask.
	 * Example:
	 *  `self::FACE_DIMMING` | `self::RANDOMIZE_UV_ROTATION` = `0x03`
	 * @var int
	 * @see self::FACE_DIMMING
	 * @see self::RANDOMIZE_UV_ROTATION
	 * @see self::SUPPORTS_TEXTURE_VARIATION
	 */
	private int $packed_bools;

	/**
	 * @param string $target
	 * Targeted face for the material.
	 * Valid values:
	 *  - "*"
	 *  - "sides"
	 *  - "up"
	 *  - "down"
	 *  - "north"
	 *  - "east"
	 *  - "south"
	 *  - "west"
	 * @param string $texture
	 * Texture identifier used by this material instance.
	 * @param RenderMethod $renderMethod
	 * Render method controlling how the block is drawn
	 * (opaque, blend, alpha_test, etc).
	 * @param TintMethod $tintMethod
	 * Tint logic applied to the texture (biome-based, none, etc).
	 * @param float $ambientOcclusion
	 * Ambient occlusion strength. Typically `1.0`.
	 * @param bool $face_dimming
	 * Whether face dimming/shading is enabled for the block.
	 * @param bool $isotropic
	 * Whether randomized UV rotation is enabled for the block.
	 */
	public function __construct(
		private readonly string $target,
		private readonly string $texture,
		private readonly RenderMethod $renderMethod = RenderMethod::OPAQUE,
		private readonly TintMethod $tintMethod = TintMethod::NONE,
		private readonly float $ambientOcclusion = 1.0,
		private readonly bool $face_dimming = true,
		private readonly bool $isotropic = false,
	) {
		$this->packed_bools = self::SUPPORTS_TEXTURE_VARIATION
			| ($face_dimming ? self::FACE_DIMMING : 0)
			| ($isotropic ? self::RANDOMIZE_UV_ROTATION : 0);
	}

	/**
	 * Returns the targeted face for the material.
	 * @return string The targeted face for the material.
	 */
	public function getTarget(): string {
		return $this->target;
	}

	/**
	 * Returns the material flag bitmask.
	 * @return int Bitmask composed of FLAG_* constants.
	 */
	public function getBitSet(): int {
		return $this->packed_bools;
	}

	/**
	 * Creates a Material instance from a decoded material definition.
	 * @param string $target Targeted face for the material.
	 * @param array{
	 *   texture: string,
	 *   render_method?: string,
	 *   tint_method?: string,
	 *   ambient_occlusion?: float|int,
	 *   face_dimming?: bool,
	 *   isotropic?: bool
	 * } $data
	 * @return self
	 * @throws \InvalidArgumentException if required fields are missing or invalid.
	 */
	public static function fromArray(string $target, array $data): self {
		if(!isset($data['texture'])){
			throw new \InvalidArgumentException('Material texture is required');
		}
		return new self(
			$target,
			$data["texture"],
			RenderMethod::tryFrom($data["render_method"] ?? "") ?? RenderMethod::OPAQUE,
			TintMethod::tryFrom($data["tint_method"] ?? "") ?? TintMethod::NONE,
			(float) ($data["ambient_occlusion"] ?? 1.0),
			$data["face_dimming"] ?? true,
			$data["isotropic"] ?? false
		);
	}

	/**
	 * Converts the material into Bedrock-compatible NBT format.
	 * @return array{
	 *   ambient_occlusion: float,
	 *   packed_bools: ByteTag,
	 *   render_method: string,
	 *   texture: string,
	 *   tint_method: string,
	 * }
	 */
	public function toArray(): array {
		return [
			"texture" => $this->texture,
			"render_method" => $this->renderMethod->value,
			"tint_method" => $this->tintMethod->value,
			"ambient_occlusion" => $this->ambientOcclusion,
			"packed_bools" => new ByteTag($this->packed_bools)
		];
	}

	/**
	 * Validates an array of materials.
	 * @param Material[] $materials
	 * @throws \InvalidArgumentException if the array is empty or contains invalid entries.
	 */
	public static function validMaterials(array $materials): void{
		if($materials === []){
			throw new \InvalidArgumentException('At least one material must be defined');
		}
		foreach($materials as $material){
			if(!$material instanceof Material){
				throw new \InvalidArgumentException('All materials must be instances of ' . Material::class);
			}
		}
	}
}