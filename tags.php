<?php

$tags = <<<IN
------General Type of Commission------
	'DIGITAL_ARTWORK', 'Digital Artwork', 'Artwork made on a computer'
	'TRADITIONAL_ARTWORK', 'Traditional Artwork', 'Hand drawn or painted'
	'ANIMATION', 'Animation', 'Animated artwork'
	'APPAREL', 'Apparel', 'Hoodies, clothing, kigurumi, etc.'
	'AUDIO', 'Audio', 'Any type of audio, whether it be a tape, song, theme, instrumental, etc.'
	'CRAFTING', 'Crafting', 'Crafted objects, like a plushie or similar'
	'FURSUIT', 'Fursuit', 'A costume similar to one of a mascot for a character'
	'GAME', 'Game', 'A custom-created game'
	'WRITING', 'Writing', 'Stories and other literature'
	'OTHER_TYPE', 'Other', 'Something not listed in the other options'
------Art Specifics------
	'3D_MODELING', '3D Modeling', 'Includes anything like 3D-Printing'
	'SCULPTING', 'Sculpting', 'Physically sculpting an object'
	'MANUFACTURING', 'Manufacturing', 'Includes anything like 3D-Printing, etching'
	'SKETCH', 'Sketch', 'A rough sketch'
	'LINEART', 'Lineart', 'Cleaned-up sketch: thick, definitive lines'
	'FLAT_COLOR', 'Flat Color', '\"Bucket-fill\" art, contains no shading'
	'CELL_SHADING', 'Cell shading', 'Basic shading'
	'SHADED', 'Shaded', 'Fully shaded with gradients and smoothing'
	'PAINTING', 'Painting', 'Either physically painting, or creating a similar effect digitally'
	'SCENERY', 'Scenery', 'Landscapes and the like'
	'YCH', 'YCH', 'Your Character Here - pre-made lineart which will be colored to fit a custom character'
	'ADOPTABLES', 'Adoptables', 'Pre-made characters'
	'HEADSHOT', 'Headshot', 'A piece of art detailing a character\'s head'
	'BADGE', 'Badge', 'Typically a bust of a character and their name, can be worn at conventions'
	'BUST', 'Bust', 'Shows the head, neck, and some chest/shoulders'
	'HALF_BODY', 'Half-body', 'Shows the character from the waist up'
	'FULL_BODY', 'Full-body', 'Shows the character\'s entire body'
	'CHIBI', 'Chibi', 'Small, cartoon-like characters with over sized heads'
	'COMIC', 'Comic', 'A set of pages containing one or more panels that tell a story'
	'ICONS', 'Icons', 'Art which is designed to be used as a profile picture'
	'PIXEL_ART', 'Pixel Art', 'Art constructed from a small number of pixels (squares)'
	'STICKERS', 'Stickers', 'A set of busts and half-bodies which show expressions'
	'REFERENCE_SHEETS', 'Reference Sheets', 'Art which depicts multiple views of a character, showing all their aspects and markings'
	'OTHER_ART', 'Other', 'Something not listed in the other options'
------Apparel, Cosplay, and Fursuit Specifics------
	'CUSTOM_T_SHIRTS', 'Custom T-Shirts', 'Custom-printed T-Shirts'
	'KIGURUMI', 'Kigurumi', 'A suit designed to look like a cartoon animal, typically used as pajamas'
	'OTHER_CLOTHING', 'Other Clothing', 'Sewed clothing, costumes, etc.'
	'JEWELRY', 'Jewelry', 'Fancy overpriced shiny stuff'
	'WIGS', 'Wigs', 'Custom-made hair pieces'
	'ARMOR', 'Armor', 'Suits of armor and other really cool stuff'
	'FURSUIT_ACCESSORIES', 'Fursuit Accessories', 'Harnesses, collars, etc.'
	'BODYSUIT', 'Bodysuit', 'The portion of the suit which covers the torso and legs'
	'CUSTOM', 'Custom', 'Suits custom-made to match a character'
	'PLANTIGRADE', 'Plantigrade', 'Flat, human-like legs'
	'DIGITIGRADE', 'Digitigrade', 'Legs with padding to look more like an animal'
	'HEAD', 'Head', 'The head of the costume'
	'HANDPAWS', 'Handpaws', 'The gloves of the costume'
	'TAIL', 'Tail', 'The tail of the suit'
	'FEETPAWS', 'Feetpaws', 'The shows/feet of the costume'
	'FULLSUIT', 'Fullsuit', 'Contains all the parts of the suit, including the body'
	'LEGS', 'Legs', 'Just the legs of the costume'
	'PARTIAL', 'Partial', 'Typically consists of the head, paws, and tail'
	'OTHER_APPAREL', 'Other', 'Something not listed in the other options'
------Writing and Story Specifics------
	'POETRY', 'Poetry', 'A poem such as free-verse, haiku, limerick, etc'
	'SHORT_STORY', 'Short Story', 'A short story, typically only a chapter'
	'MULTI_CHAPTER', 'Multi-Chapter', 'A multiple-chapter fiction'
	'CHARACTER_SUMMARY', 'Character Summary', 'A quick biography for a character, typically for a ref-sheet text'
	'FAN_FICTION', 'FanFiction', 'Writing of characters in a TV show/movie/etc.'
	'OTHER_WRITING', 'Other', 'Something not covered by the other options'
------Animation Specifics------
	'VIDEO_INTRO', 'Video Intro', 'An introduction for a video, typically YouTube'
	'SHORT', 'Short', '<30s animation'
	'MEDIUM', 'Medium', '30s-2m animation'
	'LONG', 'Long', '2m+ animation'
	'OTHER_ANIMATION', 'Other', 'Something not covered by the other options'
------Genders------
	'MALE', 'Male', 'A male character'
	'FEMALE', 'Female', 'A female character'
	'INTERSEX', 'Intersex', 'Shares both male and female parts'
	'TRANSGENDER', 'Transgender', 'Someone who either feels they have no gender (genderless) or do not fit the one they were born with'
	'OTHER_GENDER', 'Other', 'Something not listed in the other options'
------Miscellaneous------
	'HUMAN', 'Human', 'Human characters'
	'ANTHROPOMORPHIC', 'Anthropomorphic', 'Anthropomorphic or human-like characters'
	'FERAL', 'Feral', 'Characters which are natural, do not stand on 2 legs, etc'
	'SOLO', 'Solo', 'The art only involves one character'
	'COUPLE', 'Couple', 'The art involves 2 characters, likely as a couple'
	'MULTIPLE_CHARACTERS', 'Multiple Characters', 'The art may involve 3 or more characters'
------Niche Art------
	'BABYFUR', 'Babyfur', 'Adult-baby characters (The character must be over 18 for NSFW art)'
	'BONDAGE', 'Bondage', 'Anything including restraining a character'
	'CANDY_GORE', 'Candy Gore', 'This includes any type of gore except the blood-and-guts kind, such as fruit gore or crystal gore'
	'CUB', 'Cub', 'Characters who are underage (Catalyst does not allow NSFW art of minors)'
	'FAT_OBESE', 'Fat/Obese', 'Fat and obese characters'
	'GORE', 'Gore', 'Anything which involves mutilation/gashes of a character, or anything similar to that, including death'
	'HYPER', 'Hyper', 'Any character with certain body parts much larger than their normal size'
	'HYPNOSIS', 'Hypnosis', 'Any form of mind control, including corruption, falls under this category'
	'INFLATION', 'Inflation', 'Forced feeding or otherwise inflating a character with a substance'
	'LATEX_RUBBER', 'Latex/Rubber', 'Art in which latex or rubber may be drawn'
	'MACRO_MICRO', 'Macro/Micro', 'Characters who are either giants (macro) or tiny (micro)'
	'MUSCLES', 'Muscles', 'Characters with defined or exaggerated muscles'
	'ODOR_MUSK', 'Odor/Musk', 'Any strong odors, including in the context of feet, pits, or musk in general'
	'PAWS', 'Paws', 'Anything in which a main aspect are feet'
	'PLUSHES', 'Plushes', 'The drawing of a character to look like a plush'
	'SCAT', 'Scat', 'Anything with poop'
	'SADISM_MASOCHISM', 'Sadism/Masochism', 'Giving or receiving pain or otherwise asserting dominance over another character'
	'TRANSFORMATION', 'Transformation', 'A character being transformed into another species or similar, may be through magic or other means'
	'URINE', 'Urine', 'Typical relating to watersports, this involves anything with urine'
	'VORE', 'Vore', 'Consuming of another character through any means'
------Maturity Rating------
	'SAFE', 'Safe', 'A piece with no sexual content or lightly suggestive at worst'
	'MATURE', 'Mature', 'No genitals, however moderately suggestive is allowed'
	'EXPLICIT', 'Explicit', 'Genitals, explicititly sexual situations, fringe fetishes, including violence and gore'
------Types of Species That Fit This Commission------
	'AQUATIC', 'Aquatic', 'Aquatic'
	'AVIANS', 'Avians', 'Birds and other flying animals'
	'BEAR', 'Bear', 'Bears'
	'BUGS', 'Bugs', 'Bugs, such as insects'
	'CANON', 'Canon', 'Canon characters, such as those from cartoons, Pokemon, etc.'
	'FELINES', 'Felines', 'Domesticated cats'
	'CENTAURS', 'Centaurs', 'A character which has a body of a horse'
	'DEER', 'Deer', 'antler bois'
	'DOGS', 'Dogs', 'Any domesticated dog, including examples like German Shepards, Beagles, etc.'
	'DRAGONS', 'Dragons', 'Dragons'
	'EQUESTRIANS', 'Equestrians', 'Horses and similar'
	'FOXES', 'Foxes', 'Foxes, including Fennecs'
	'HYENAS', 'Hyenas', 'Hyenas'
	'MACHINES', 'Machines', 'Aeromorphs and other vehicle-related characters'
	'MUSTELIDS', 'Mustelids', 'Includes weasels, otters, etc.'
	'REPTILES', 'Reptiles', 'Reptiles like lizards, dinosaurs, etc.'
	'RODENTS', 'Rodents', 'Rats, mice, etc.'
	'SERGALS', 'Sergals', 'The cheese-shaped fantasy animal'
	'WOLVES', 'Wolves', 'Wolves, including derivatives like dingoes'
	'ORIGINAL_SPECIES', 'Original Species', 'Other original species such as Dutch Angel Dragons, Protogens, etc.'
	'OTHER_SPECIES', 'Other', 'Something not listed in the other options'
	'OTHER_CANINE', 'Other Canine', 'Other animals from the canine family'
------Art and Craft Styles------
	'ABSTRACT', 'Abstract', 'Expresses the character in a non-standard way'
	'KEMONO', 'Kemono', '\"Anime-style\" art, originated in Japan'
	'REALISTIC', 'Realistic', 'Shows the character as though it was real'
	'SEMI_REALISTIC', 'Semi-realistic', 'Realistic but with a few cartoony elements'
	'SURREAL', 'Surreal', 'Shows a surreal representation of the character'
	'CARTOONY', 'Cartoony', 'Typically contains non-realistic qualities like large, flat eyes, flat colors, etc.'
	'OTHER_ARTS', 'Other', 'Something not listed in the other options'
IN;

$in = array_map("trim",explode("\n",trim($tags)));

echo "TRUNCATE `commission_type_attribute_groups`;\n";
echo "TRUNCATE `commission_type_attributes`;\n";

$i = -1;
$j = 0;
foreach ($in as $value) {
	if (strpos($value,"-") === 0) {
		echo 'INSERT INTO `commission_type_attribute_groups` (`ID`, `LABEL`) VALUES('.++$i.", '".trim($value, "-")."');\n";
		$j = 0;
	} else {
		echo 'INSERT INTO `commission_type_attributes` (`SET_KEY`, `NAME`, `DESCRIPTION`, `GROUP_ID`, `SORT`) VALUES('.$value.', '.$i.', '.$j++.');'."\n";
	}
}
