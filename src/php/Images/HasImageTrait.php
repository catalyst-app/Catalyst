<?php

namespace Catalyst\Images;

use \Exception;

/**
 * Used by all models with an image
 */
trait HasImageTrait {
	/**
	 * The object's image
	 * @var null|Image
	 */
	protected $image=null;

	/**
	 * Get the image's folder
	 */
	abstract public function getImageFolder() : string;

	/**
	 * Initialize the image
	 */
	abstract public function initializeImage() : void;

	/**
	 * Get the object's Image
	 * 
	 * @return Image
	 */
	public function getImage() : Image {
		if (is_null($this->image)) {
			$this->initializeImage();
			if (is_null($this->image)) {
				throw new Exception("Image  for ".__CLASS__." is not properly initializing");
			}
		}
		return $this->image;
	}

	/**
	 * Set the object's image
	 * 
	 * @param Image $image
	 */
	public function setImage(Image $image) : void {
		$this->image = $image;
	}
}
