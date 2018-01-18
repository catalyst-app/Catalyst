<?php

define("ROOTDIR", "../../".((isset($_GET["levels"]) && $_GET["levels"] == "/") ? "../" : ""));
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\Database\FeatureBoard\Comment;
use \Catalyst\Database\FeatureBoard\Groups;
use \Catalyst\Database\FeatureBoard\Item;
use \Catalyst\Form\FormHTML;
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\Page\Values;
use \Catalyst\User\User;

if (!isset($_GET["q"])) {
	header("Location: ".ROOTDIR."FeatureBoard");
	die("Redirecting...");
}

$item = Item::getFromUrl($_GET["q"]);

define("PAGE_KEYWORD", Values::FEATURE[0]);
define("PAGE_TITLE", Values::createTitle(Values::FEATURE[1], ["name" => (!is_null($item) ? $item["NAME"] : "Invalid")]));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Feature");
?>

<?php if (!is_null($item)): ?>
			<div class="section">
				<h4><?= htmlspecialchars($item["NAME"]) ?></h4>
				<p class="flow-text no-margin"><i><?= htmlspecialchars($item["GROUP"]) ?></i></p>
<?php
if (!is_null($item["AUTHOR_ID"])) {
	$author = new User($item["AUTHOR_ID"]);
}
?>
				<h5>Submitted by <?= is_null($item["AUTHOR_ID"]) ? "N/A" : '<a href="'.ROOTDIR.'User/'.$author->getUsername().'">'.htmlspecialchars($author->getNickname()).'</a>' ?> on <?= (new DateTime($item["CREATED_TS"]))->format("F jS, Y") ?></h5>
<?php if (!is_null($item["ESTIMATED_MANHOURS"])): ?>
				<h5>Estimated manhours: <?= $item["ESTIMATED_MANHOURS"] ?></h5>
<?php endif; ?>
				<h5>Status: <?= htmlspecialchars($item["STATUS"]) ?></h5>
				<p class="no-top-margin"><?= htmlspecialchars($item["STATUS_DESCRIPTION"]) ?></p>
<?php $comments = Item::getVotes($item["ID"]); ?>
				<h5>Vote: <span class="green-text"><?= $comments["YES"] ?> Yes</span>, <?= $comments["MAYBE"] ?> Maybe, <span class="red-text"><?= $comments["NO"] ?> No</span>, <?= $comments["IRRELEVANT"] ?> Irrelevant</h5>
				<p class="no-top-margin">Please go to the <a href="<?= ROOTDIR ?>FeatureBoard">board</a> to vote</p>
				<div class="row">
					<h5>Introduction</h5>
					<div class="raw-markdown"><?= htmlspecialchars($item["INTRODUCTION"]) ?></div>
					<h5>Proposal</h5>
					<div class="raw-markdown"><?= htmlspecialchars($item["PROPOSAL"]) ?></div>
					<h5>Acknowledgement</h5>
					<div class="raw-markdown"><?= htmlspecialchars($item["ACKNOWLEDGEMENT"]) ?></div>
					<h5>Future Scope</h5>
					<div class="raw-markdown"><?= htmlspecialchars($item["FUTURE_SCOPE"]) ?></div>
<?php if (!is_null($item["DEVELOPER_NOTE"])): ?>
					<h5>Developer Note</h5>
					<div class="raw-markdown"><?= htmlspecialchars($item["DEVELOPER_NOTE"]) ?></div>
<?php endif; ?>
				<h5>Comments</h5>
<?php
$comments = Comment::get($item["ID"]);
?>
<?php if (count($comments)): ?>
<?php foreach ($comments as $comment): ?>
					<div class="raw-markdown"><?= htmlspecialchars($comment["BODY"]) ?></div>
					<p class="no-top-margin">By <i><?= htmlspecialchars($comment["NICK"]) ?></i></p>
<?php endforeach; ?>
<?php else: ?>
					<p class="flow-text">None yet</p>
<?php endif; ?>
<?php if (User::isLoggedIn()): ?>
					<div class="meta-feature-id" data-id="<?= $item["ID"] ?>"></div>
					<h5>New Comment</h5>
					<?= FormHTML::generateForm(Comment::getFormStructure()) ?>
<?php endif; ?>
				</div>
			</div>
<?php else: ?>
			<div class="section">
				<p class="flow-text">Feature not found.  <a href="<?= ROOTDIR ?>FeatureBoard">Go back</a>?</p>
			</div>
<?php endif; ?>

<?php
require_once Values::FOOTER_INC;
