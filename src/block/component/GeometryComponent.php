<?php

namespace customiesdevs\customies\block\component;

use customiesdevs\customies\block\properties\CullingShape;

final class GeometryComponent implements BlockComponent {

	/** A geometry that uses cross-shaped planes to render the block, similar to the geometry used for vanilla flowers and other non-solid blocks. */
	public const GEOMETRY_CROSS = "minecraft:geometry.cross";
	/** A geometry that uses the full cube shape to render the block, similar to the geometry used for vanilla solid blocks. */
	public const GEOMETRY_FULL_BLOCK = "minecraft:geometry.full_block";

	private string $identifier;
	private array $boneVisibility = [];
	private string $culling;
	private string $cullingLayer;
	private string $cullingShape;
	private array|bool $uvLock;

	/**
	 * The description identifier of the geometry to use to render this block. This identifier must either match an existing geometry identifier in any of the loaded resource packs or be one of the currently supported Vanilla identifiers: "minecraft:geometry.full_block" or "minecraft:geometry.cross".
	 * @param string $identifier The description identifier of the geometry to use to render this block. This identifier must either match an existing geometry identifier in any of the loaded resource packs or be one of the currently supported Vanilla identifiers: "minecraft:geometry.full_block" or "minecraft:geometry.cross".
	 * @param array $boneVisibility An optional list of true/false values that define the visibility of individual bones in the geometry file. In order to set up 'bone_visibility', the geometry file name must be entered as an identifier. After the identifier has been specified, bone_visibility can be defined based on the names of the bones in the specified geometry file on a true/false basis. Note that all bones default to 'true,' so bones should only be defined if they are being set to 'false.' Including bones set to 'true' will work the same as the default.
	 * @param string $culling An optional identifer of a culling definition. The culling definition is used to determine which faces of the block should be culled when rendering. The culling definition can be used to optimize rendering performance by reducing the number of faces that need to be rendered. This identifier must match an existing culling definition in any of the currently loaded resource packs.
	 * @param string $cullingLayer [Experimental] - A string that allows culling rule to group multiple blocks together when comparing them. When using the minecraft namespace, the only allowed culling layer identifiers are : "minecraft:culling_layer.undefined" or "minecraft:culling_layer.leaves". Additionally, the feature is currently only usable behind the "upcoming creator features" toggle. When using no namespaces or a custom one, the names must start and end with an alpha-numeric character.
	 * @param CullingShape|string $cullingShape Voxel shapes work together with culling rules and will not function if there is no culling rule defined for the block.
	 * @param array|bool $uvLock A Boolean locking UV orientation of all bones in the geometry, or an array of strings locking UV orientation of specific bones in the geometry. For performance reasons it is recommended to use the Boolean. Note that for cubes using Box UVs, rather than Per-face UVs, 'uv_lock' is only supported if the cube faces are square.
	 */
	public function __construct(
		string $identifier = self::GEOMETRY_FULL_BLOCK, 
		array $boneVisibility = [], 
		string $culling = "", 
		string $cullingLayer = "minecraft:culling_layer.undefined",
		CullingShape|string $cullingShape = CullingShape::SHAPE_EMPTY,
		array|bool $uvLock = false
	) {
		$this->identifier = $identifier;
		$this->culling = $culling;
		$this->cullingLayer = $cullingLayer;
		$this->cullingShape = is_string($cullingShape) ? $cullingShape : $cullingShape->value;
		$this->uvLock = $uvLock;
		$this->boneVisibility = $boneVisibility;
	}

	public function getName(): string {
		return 'minecraft:geometry';
	}

	public function getValue(): array {
		return [
			"bone_visibility" => $this->boneVisibility,
			"culling" => $this->culling,
			"culling_layer" => $this->cullingLayer,
			"culling_shape" => $this->cullingShape,
			"identifier" => $this->identifier,
			"uv_lock" => $this->uvLock,
			// no reason as to why these 4 exist, but its what minecraft is outputting
			"ignoreGeometryForIsSolid" => false,
			"isV1Fullblock" => false,
			"needsLegacyTopRotation" => false,
			"useBlockTypeLightAbsorption" => false
		];
	}
}