<?php

namespace Catalyst\Images;

use \Exception;

/**
 * Used by all models with several images (examples, etc)
 */
trait HasImageSetTrait {
	/**
	 * The object's images
	 * @var Image[]|null
	 */
	protected $images=null;

	/**
	 * Used to prefill image set from DB or similar
	 */
	abstract public function initializeImageSet() : void;

	/**
	 * Get the object's Image
	 * 
	 * @return Image[]
	 */
	public function getImageSet() : array {
		if (is_null($this->images)) {
			$this->initializeImageSet();
			if (is_null($this->images)) {
				throw new Exception("Image set for ".__CLASS__." is not properly initializing");
			}
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
