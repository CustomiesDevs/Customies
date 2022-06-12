# Customies

A PocketMine-MP plugin that implements support for custom blocks, items and entities.
> It is important to note that custom blocks will not work properly with other plugins that also modify the runtime block
> mappings, such as [INether](https://github.com/ipad54/INether) and [VanillaX](https://github.com/CLADevs/VanillaX).

## Important Contributors

| Name                                              | Contribution                                                                                 |
|---------------------------------------------------|----------------------------------------------------------------------------------------------|
| [TwistedAsylumMC](https://github.com/TwistedAsylumMC)         | Helped research and develop the first versions of Customies as well as maintain the code      |
| [DenielW](https://github.com/DenielWorld)         | Helped research and develop the first versions of Customies                                  |
| [Unickorn](https://github.com/Unickorn)           | Maintained the code during the PM4 betas and kept it up to date                              |
| [JackNoordhuis](https://github.com/JackNoordhuis) | Suggested the idea of using async workers and helped write the code which made them function |
| [ScarceityPvP](https://github.com/ScarceityPvP)   | Helped develop the item components implementation and block-related bug fixes                |

## Usage

### Custom Blocks

Registering a custom block can either be done with or without a model using the `CustomiesBlockFactory` class. Without a
model all you need to do is register
the block with the same parameters you would use to construct a Block normally.

```php
use customiesdevs\customies\block\CustomiesBlockFactory;
use pocketmine\block\BlockBreakInfo;

// ...

public function onEnable(): void {
	CustomiesBlockFactory::getInstance()->registerBlock(Block::class, "customies:example_block", "Example Block", new BlockBreakInfo(1));
}

// ...
```

If your block contains a different model, you can provide a `Model` as the 4th argument. A model requires an array of
materials, a texture, an origin and a size.

- Materials: Array of materials that define how the texture is applied to specific faces
- Texture: Name of the texture to apply to the model
- Origin: The origin point of the selection box. `Vector3(0, 0, 0)` is the top right corner of the block
- Size: The size of the block in pixels. This must be between `Vector3(0, 0, 0)` and `Vector3(16, 16, 16)` as the client
  does not support blocks being larger than this

```php
use customiesdevs\customies\block\CustomiesBlockFactory;
use customiesdevs\customies\block\Material;
use customiesdevs\customies\block\Model;
use pocketmine\block\BlockBreakInfo;
use pocketmine\math\Vector3;

// ...

public function onEnable(): void {
	$material = new Material(Material::TARGET_ALL, "example", Material::RENDER_METHOD_ALPHA_TEST);
	$model = new Model([$material], "geometry.example", new Vector3(-8, 0, -8), new Vector3(16, 16, 16));
	CustomiesBlockFactory::getInstance()->registerBlock(Block::class, "customies:example_block", "Example Block", new BlockBreakInfo(1));
}

// ...
```

If you want register your block into the creative tab, add this array behind the model variable:

You can find the different categories and groups on the [Microsoft documentation](https://docs.microsoft.com/en-us/minecraft/creator/reference/content/blockreference/examples/blockcomponents/minecraftblock_creative_category)
```php
$creative = new CreativeInventoryInfo(CreativeInventoryInfo::CATEGORY_ALL, CreativeInventoryInfo::GROUP_CONCRETE);
CustomiesBlockFactory::getInstance()->registerBlock(Block::class, "customies:example_block", "Example Block", new BlockBreakInfo(1),$creative);
```

```php
$block = CustomiesBlockFactory::getInstance()->get("customies:example_block");
```

> More information about materials and the different properties can be found
> on [docs.microsoft.com](https://docs.microsoft.com/en-us/minecraft/creator/reference/content/blockreference).

### Custom Entities

Registering a custom entity is as simple as registering a normal entity. All you need to do is use
the `CustomiesEntityFactory` class to register the entity, and then spawn the entity in the same way as you would
normally.

```php
use customiesdevs\customies\entity\CustomiesEntityFactory;

// ...

public function onEnable(): void {
	CustomiesEntityFactory::getInstance()->registerEntity(ExampleEntity::class, "customies:example_entity");
}

// ...
```

```php
$entity = new ExampleEntity(new Location(...));
$entity->spawnToAll();
```

> If you want to provide your own creation func, `registerEntity` accepts an optional 3rd parameter to provide your own
> creation func with the same signature as normal (`Closure(World $world, CompoundTag $nbt) : Entity`)

### Custom Items

Registering a custom item is as simple as registering a normal item, but the ID is calculated for you. All you need to
do is use the `CustomiesItemFactory` class to register the item, and fetch it as you would with a vanilla item.

```php
use customiesdevs\customies\item\CustomiesItemFactory;

// ...

public function onEnable(): void {
	CustomiesItemFactory::getInstance()->registerItem(Item::class, "customies:example_item", "Example Item");
}

// ...
```

```php
$item = CustomiesItemFactory::getInstance()->get("customies:example_item", 64);
```

Custom items can also have components which are used to change the behaviour of items client side, such as making it
edible or have durability etc. To get started with components, you need to implement the `ItemComponents` interface, use
the `ItemComponentsTrait` and call the `initComponent` method in the constructor of your class.

```php
use customiesdevs\customies\item\ItemComponents;
use customiesdevs\customies\item\ItemComponentsTrait;
use pocketmine\item\Item;

class ExampleItem extends Item implements ItemComponents {
	use ItemComponentsTrait;

	public function __construct() {
		$this->initComponent("example_item", 64);
	}
}
```

Now that you have an item with components, you can add either components or properties using the `addComponent`
and `addProperty` methods.

```php
// ...

$this->addComponent("minecraft:armor", ["protection" => 4]);
$this->addProperty("allow_off_hand", true);

// ...
```

> More information about all the different item components and properties can be found
> on [docs.microsoft.com](https://docs.microsoft.com/en-us/minecraft/creator/reference/content/itemreference).
