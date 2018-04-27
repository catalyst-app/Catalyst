<?php

namespace Catalyst\Images;

/**
 * Used by all models with several images (examples, etc)
 */
trait HasImageSetTrait {
	/**
	 * The object's images
	 * @var Image[]|null
	 */
	protected $images=null;

	abstract public function initializeImageSet() : void;

	/**
	 * Get the object's Image
	 * 
	 * @return Image[]
	 */
	public function getImageSet() : array {
		if (is_null($this->images)) {
			$this->initializeImageSet();
		}
		return $this->images;
	}

	/**
	 * Set the object's images
	 * 
	 * @param Image[] $images
	 */
	public function setImageSet(array $images) : void {
		$this->images = $images;
	}
}
