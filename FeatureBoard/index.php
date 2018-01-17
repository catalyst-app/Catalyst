<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\Database\FeatureBoard\Groups;
use \Catalyst\Database\FeatureBoard\Item;
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\Page\Values;
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::FEATURE_BOARD[0]);
define("PAGE_TITLE", Values::createTitle(Values::FEATURE_BOARD[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Feature Board");
?>
		<div class="section">
			<p class="flow-text">Welcome to Catalyst's feature board!  This is where you can see what's going on with development and even contribute!</p>
			<p class="flow-text">If you would like to submit an idea, <strong>please</strong> read all existing items and make sure that it has not already been submitted!</p>
		</div>
<?php foreach (Groups::get() as list($key, $name)): ?>
		<div class="section">
			<h4><?= $name ?></h4>

			<table class="bordered highlight">
				<thead>
					<tr>
						<th>Status</th>
						<th>Name</th>
						<th>Author</th>
						<th>Created</th>
						<th>Vote</th>
					</tr>
				</thead>
				<tbody>
<?php foreach (Item::getForGroup($key) as $item): ?>
					<tr data-id="<?= $item["ID"] ?>" data-url="<?= ROOTDIR ?>FeatureBoard/<?= $item["AUTOGEN_URL"] ?>" class="feature-item <?= in_array($item["STATUS_KEY"], ["COMPLETED", "DENIED"]) ? "grey-text" : "" ?>">
						<td><?= $item["STATUS"] ?></td>
						<td><?= $item["NAME"] ?></td>
						<td><a href="<?= ROOTDIR ?>User/<?= ($user = new User($item["AUTHOR_ID"]))->getUsername() ?>"><?= $user->getNickname() ?></a></td>
						<td><?= (new DateTime($item["CREATED_TS"]))->format("F jS, Y") ?></td>
						<td>
<?php $votes = Item::getVotes($item["ID"]); ?>
<?php if (User::isLoggedIn() && !Item::hasVoted($item["ID"], $_SESSION["user"]->getId())): ?>
							<strong><a href="#" class="green-text vote-btn vote-yes"><?= $votes["YES"] ?></a>&nbsp;:&nbsp;<a href="#" class="grey-text vote-btn vote-maybe"><?= $votes["MAYBE"] ?></a>&nbsp;:&nbsp;<a href="#" class="red-text vote-btn vote-no"><?= $votes["NO"] ?></a>&nbsp;|&nbsp;<a href="#" class="grey-text vote-btn vote-irrelevant"><?= $votes["IRRELEVANT"] ?></a></strong>
<?php else: ?>
							<strong><span class="green-text"><?= $votes["YES"] ?></span>&nbsp;:&nbsp;<span class="grey-text"><?= $votes["MAYBE"] ?></span>&nbsp;:&nbsp;<span class="red-text"><?= $votes["NO"] ?></span>&nbsp;|&nbsp;<span class="grey-text"><?= $votes["IRRELEVANT"] ?></span></strong>
<?php endif; ?>
						</td>
					</tr>
<?php endforeach; ?>
				</tbody>
			</table>
		</div>
<?php endforeach; ?>
<?php
require_once Values::FOOTER_INC;
