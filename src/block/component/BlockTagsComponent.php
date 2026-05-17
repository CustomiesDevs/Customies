<?php

namespace customiesdevs\customies\block\component;

final class BlockTagsComponent implements BlockComponent {

	/** @var string[] */
	private array $blockTags;

	/**
	 * Replaces tag:* components
	 * @param string[] $blockTags
	 * @link [BlockTags](https://wiki.bedrock.dev/blocks/vanilla-block-tags)
	 */
	public function __construct(array $blockTags = []) {
		$this->blockTags = $blockTags;
	}

	public function getName(): string {
		return 'minecraft:tags';
	}

	/**
	 * @return string[]
	 */
	public function getValue(): array {
		return $this->blockTags;
	}
}