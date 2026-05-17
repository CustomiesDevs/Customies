<?php
declare(strict_types=1);

namespace customiesdevs\customies\item\component;

final class ItemTagsComponent implements ItemComponent {

	/** @var string[] */
	private array $tags = [];

	/**
	 * Determines which tags are included on a given item.
	 * @param string[] $tags An array that can contain multiple item tags.
	 */
	public function __construct(array $tags = []) {
		foreach($tags as $tag){
			if(!is_string($tag)){
				throw new \InvalidArgumentException('All tags must be strings');
			}
		}
		$this->tags = $tags;
	}

	public function getName(): string {
		return 'minecraft:tags';
	}

	public function getValue(): array {
		return [
			"tags" => $this->tags
		];
	}

	public function getPropertyMapping(): ?array {
		return null;
	}
}