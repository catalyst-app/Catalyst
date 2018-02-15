<?php

define("ROOTDIR", "../../".((isset($_GET["levels"]) && $_GET["levels"] == "/") ? "../" : ""));
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Artist\Artist;
use \Catalyst\CommissionType\CommissionType;
use \Catalyst\Database\CommissionType\EditCommissionType;
use \Catalyst\Form\FormHTML;
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::EDIT_COMMISSION_TYPE_IMAGES);
define("PAGE_TITLE", Values::createTitle(Values::EDIT_COMMISSION_TYPE_IMAGES[1], []));

if (User::isLoggedIn() && $_SESSION["user"]->isArtist()) {
	define("PAGE_COLOR", $_SESSION["user"]->getArtistPage()->getColorHex());
} else if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColorHex());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Edit Commission Type Images");

$typeId = CommissionType::getIdFromToken($_GET["q"]);

if (FormHTML::testAjaxSubmissionFailed()): ?>
	<?= FormHTML::getAjaxSubmissionHtml(); ?>
<?php elseif (!User::isLoggedIn()): ?>
	<?= User::getNotLoggedInHTML() ?>
<?php elseif (!$_SESSION["user"]->isArtist()): ?>
		<div class="section">
			<p class="flow-text">You do not have an artist page.</p>
			<p class="flow-text">You may create one <a href="<?= ROOTDIR ?>Artist/New">here</a>.</p>
		</div>
<?php elseif ($typeId == -1): ?>
		<div class="section">
			<p class="flow-text">This commission type does not exist.</p>
		</div>
<?php elseif (($type = (new CommissionType($typeId)))->getArtistPageId() != $_SESSION["user"]->getArtistPageId()): ?>
		<div class="section">
			<p class="flow-text">This commission type is not owned by you.</p>
		</div>
<?php else: ?>
		<div class="section">
			<div class="row commission-type-images edit-cards">
<?php if ($type->getImages()[0][0] == "default.png"): ?>
				<p class="col s12 flow-text">There are no images of this commission type yet!</p>
<?php else: ?>
				<input type="hidden" class="token-input" value="<?= $type->getToken() ?>">
				<p class="col s12 flow-text">Drag the images to rearrange</p>
<?php
$images = $type->getImages();

$i=0;

$images = array_map(function($in) use ($type, $i) {
$i++;
return UniversalFunctions::renderImageCardRawHTML(ROOTDIR.\Catalyst\Form\FileUpload::FOLDERS[\Catalyst\Form\FileUpload::COMMISSION_TYPE_IMAGE]."/".$in[0], $in[2], 
	implode("", [
		'<div class="row no-margin">',
		'<input type="hidden" class="path-input" id="path'.$i.'" value="'.str_replace($type->getToken(), "", $in[0]).'">',
		'<div class="input-field col s12">',
		'<input type="text" id="caption'.$i.'" maxlength="255" class="caption-input" value="'.htmlspecialchars($in[1]).'">',
		'<label for="caption'.$i.'">Caption</label>',
		'</div>',
		'<div class="switch col s12 small-bottom-margin">',
		'<label>',
		'SFW',
		'<input type="checkbox" class="nsfw-input"'.($in[2] ? ' checked="checked"' : '').'>',
		'<span class="lever"></span>',
		'NSFW',
		'</label>',
		'</div>',
		'<input type="hidden" class="primary-input" id="primary'.$i.'" value="'.($in[3] ? "true" : "false").'">',
		'<div class="col s12 m6 small-padding">',
		'<div class="col s12 btn make-primary-button"'.($in[3] ? ' disabled="disabled"' : '').'>primary</div>',
		'</div>',
		'<div class="col s12 m6 small-padding">',
		'<div class="col s12 btn delete-button red darken-1">delete</div>',
		'</div>',
		'</div>',
	])
);
}, $images);

$bisected = [[],[]];
$trisected = [[],[],[]];
for ($i=0; $i < count($images); $i++) { 
$bisected[$i%2][] = $images[$i];
$trisected[$i%3][] = $images[$i];
}

?>
				<div class="col s12 m6 hide-on-large-only">
					<?= implode("", $bisected[0]) ?>
				</div>
				<div class="col s12 m6 hide-on-large-only">
					<?= implode("", $bisected[1]) ?>
				</div>
				<div class="col l4 hide-on-med-and-down">
					<?= implode("", $trisected[0]) ?>
				</div>
				<div class="col l4 hide-on-med-and-down">
					<?= implode("", $trisected[1]) ?>
				</div>
				<div class="col l4 hide-on-med-and-down">
					<?= implode("", $trisected[2]) ?>
				</div>
			</div>
			<br>
			<div class="divider"></div>
			<div class="row">
				<br>
				<div id="editctimg-submit-wrapper">
					<div id="editctimg-btn" class="btn waves-effect waves-light submitter col s12 m4 l2">
						save
					</div>
				</div>
				<div id="editctimg-progress-wrapper" class="hide">
					<div class="progress">
						<div class="indeterminate"></div>
					</div>
				</div>
<?php endif; ?>
			</div>
		</div>
<?php
endif;

require_once Values::FOOTER_INC;
