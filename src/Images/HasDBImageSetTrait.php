<?php

namespace Catalyst\Images;

/**
 * Used by all models with several images that reside in a DB (examples, etc)
 */
trait HasDBImageSetTrait {
	use HasImageSetTrait; // basically extends

	/**
	 * Get information regarding the database structure of the image table
	 *
	 * @return array
	 */
	abstract public static function getImageDbInfo() : array;

	public function addImage(string $path, bool $nsfw, string $caption, string $captionInfo, int $sort) : DBImage {
		// ensure initialized
		if (is_null($this->images)) {
			$this->initializeImageSet();
		}

		return $this->images[] = new DBImage(
			-1,
			$this->getId(),
			static::getImageDbInfo(),
			static::getImageFolder(),
			$this->getToken(),
			$path,
			$nsfw,
			$caption,
			$captionInfo,
			$sort,
			true
		);
	}
}
