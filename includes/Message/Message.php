<?php

namespace Catalyst\Message;

class Message {
	const USER = 0;
	const ARTIST = 1;

	public static function createMessageButton(int $type, string $uname, string $nick="", array $color=PAGE_COLOR) : string {
		switch ($type) {
			case self::USER:
				$typePath = 'User';
				break;
			case self::ARTIST:
				$typePath = 'Artist';
				break;
			default:
				throw new \InvalidArgumentException();
		}
		return '<p class="flow-text no-top-margin"><a href="'.ROOTDIR.'Message/'.$typePath.'/'.$uname.'" class="btn">message '.(empty($nick) ? $uname : $nick).'</a></p>';
	}
}
