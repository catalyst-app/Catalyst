<?php

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Page\{UniversalFunctions, Values};
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::TOS_GUIDE[0]);
define("PAGE_TITLE", Values::createTitle(Values::TOS_GUIDE[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", $_SESSION["user"]->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Terms of Service Guide");
?>
		<div class="row">
			<div class="col s12 m9 l10">
				<div class="section hide-on-med-and-up">
					<?= Values::createInlineTOC([
						["intro", "Introduction"],
						["general-terms", "General Terms"],
						["limits", "Limits"],
						["contact-preferences", "Contact Preferences"],
						["payment", "Payment"],
						["cancellation", "Cancellation"],
						["shipping", "Shipping"],
						["changes", "Changes"],
						["sharing-of-finished-work", "Sharing Of Finished Work"],
						["copyright", "Copyright"],
						["hall-of-fame", "Hall of Fame"],
					]) ?>
				</div>
				<div class="divider hide-on-med-and-up"></div>
				<div class="section" id="intro">
					<h4>Introduction</h4>

					<p class="flow-text">Welcome to our guide on creating personal terms of service!  We recommend all creators, of any kind, have a personal terms of service.  This is important as it ensures that the client have agreed upon a set of conditions, and it makes the client liable if they are not followed.</p>
					<p class="flow-text">There are a few things that we think every creator should have.  We've made sections for each of these: <a href="#general-terms">general terms</a>, <a href="#limits">limits</a>, <a href="#contact-preferences">contact preferences</a>, <a href="#payment">payment</a>, <a href="#cancellation">cancellation</a>, <a href="#shipping">shipping</a>, <a href="#sharing-of-finished-work">sharing of finished work</a>, <a href="#copyright">copyright</a>, and <a href="#changes">changes</a>.</p>
					<p class="flow-text">Have any suggestions?  This is a living document, feel free to contact us with any suggestions or feedback you might have!  If your your terms of service is stellar, we might even include it in our <a href="#hall-of-fame">hall of fame</a>!</p>
					<p class="flow-text">The information below is only provided as examples.  You are in no way required to use these, or only use items from this page when constructing your terms of service.  Under each section, you will see several examples, as well as an explaination.</p>
					<p class="flow-text">When a client commissions a creator, they agree to the creator's current terms of service.  This date is recorded in our system, and the client is not held to any future changes a creator makes unless otherwise agreed upon.  You may view a creator's revision history under their main terms of service page.</p>
					<p class="flow-text">If a client breaks the creator's terms of service, they should be reported to our staff accordingly.  We will look at the case and take reasonable actions, up to suspension of the account, depending on the severity.</p>
				</div>
				<div class="divider"></div>
				<div class="section" id="general-terms">
					<h4>General Terms</h4>

					<p class="flow-text">This section contains overall things which do not fit into any category, and usually the main points for working with an artist.</p>
					<p class="flow-text">Typically, we see notes about denying commissions, however we have seen creators describe many other things, such as deadline policies, etc.</p>
					
					<p class="flow-text no-bottom-margin">Examples we like:</p>
					<ul class="browser-default">
						<li class="flow-text no-margin">
							<p class="no-margin">Be prepared to provide clean references of your character(s), or be able to provide a detailed description of them.  Follow all rules in the commission information, as well as these terms of service.  I have a right to decline a commission for any reason, including you not following the rules provided by me.</p>
							<p class="right-align no-margin"><em>Adapted from <a href="https://noelanieternal.deviantart.com/">NoelaniEternal</a>'s ToS</em></p>
						</li>

						<li class="flow-text no-margin">
							<p class="no-margin">I hold the right to reject any commission, at any time for any reason (of which I may or may not provide.)</p>
							<p class="right-align no-margin"><em>Adapted from <a href="http://stealthnachos.weebly.com/terms-of-service.html">Stealthnachos</a>' ToS</em></p>
						</li>

						<li class="flow-text no-margin">
							<p class="no-margin">I reserve the rights to deny/cancel any services at my discretion.  Some common reasons include: I’m not comfortable drawing your idea or it’s too much of a grey zone, empty/suspicious accounts, unclear reference pics, disregarding my boundaries, rude/difficult clients, I myself get busy/sick etc, and stressing/pushing deadlines (this is a general killer for many artists and can affect the quality of the artwork!)</p>
							<p class="right-align no-margin"><em>Adapted from <a href="https://docs.google.com/document/d/1lk_aEoxVO1KroiscbitTNh8u1EAMXr_o63SHlErLPTA/edit">Hettie</a>'s ToS</em></p>
						</li>
					</ul>
				</div>
				<div class="divider"></div>
				<div class="section" id="limits">
					<h4>Limits</h4>

					<p class="flow-text">Creators should be clear and definitive in what they will and will not create.  Our attribute system does this to some extent, but artists should always clearly state things they are not willing to draw.  This helps prevent both unwanted quotes and rejection.</p>
					<p class="flow-text">Some common examples creators may wish to include are canon characters (those from TV shows/movies), as well as various scenarious like macro/micro, etc.  What is listed in a creator's ToS may not fully encompass all they will/will not do.</p>

					<p class="flow-text no-bottom-margin">Examples we like:</p>
					<ul class="browser-default">
						<li class="flow-text no-margin">
							<p class="no-margin">I only do SFW content. Risque, pinup, and very simple nudity is allowed, but I reserve the right to refuse any commission I don’t feel comfortable taking.</p>
							<p class="right-align no-margin"><em>Adapted from <a href="http://stealthnachos.weebly.com/terms-of-service.html">Stealthnachos</a>' ToS</em></p>
						</li>

						<li class="flow-text no-margin">
							<p class="no-margin">No copyrighted characters or OC's without their given permission.</p>
							<p class="right-align no-margin"><em>Adapted from <a href="https://docs.google.com/document/d/1lk_aEoxVO1KroiscbitTNh8u1EAMXr_o63SHlErLPTA/edit">Hettie</a>'s ToS</em></p>
						</li>
					</ul>
				</div>
				<div class="divider"></div>
				<div class="section" id="contact-preferences">
					<h4>Contact Preferences</h4>

					<p class="flow-text">Clients need to know creator's contact preferences.  If a creator prefers all communication be kept to a certain channel, or if clients should only contact them so often, they should mention it in their terms of service.  Additionally, creators should put information about circumstances which may impede communication in order to put both parties at ease.</p>

					<p class="flow-text no-bottom-margin">Examples we like:</p>
					<ul class="browser-default">
						<li class="flow-text no-margin">
							<p class="no-margin">Please be professional and polite when contacting me for any reason regarding commissions. Harassment, rudeness, or any other ill behavior may result in your commission being cancelled, and a refund will not be issued. Most commissions will not take more than a couple weeks. However, the completion time depends on many factors, including health, order of commission, complexity, work, school, or family issues. If any of these things occur, you will be notified ASAP.</p>
							<p class="right-align no-margin"><em>Adapted from <a href="https://noelanieternal.deviantart.com/">NoelaniEternal</a>'s ToS</em></p>
						</li>

						<li class="flow-text no-margin">
							<p class="no-margin">You may inquire about progress on your piece at any time.</p>
							<p class="right-align no-margin"><em>Adapted from <a href="http://stealthnachos.weebly.com/terms-of-service.html">Stealthnachos</a>' ToS</em></p>
						</li>
					</ul>
				</div>
				<div class="divider"></div>
				<div class="section" id="payment">
					<h4>Payment</h4>

					<p class="flow-text">Creators should detail exactly how they expect payment to work.  This includes every aspect, including methods, amounts, stages (if applicable), etc.</p>
					<p class="flow-text">Through Catalyst, the possibilities for payment are nearly endless!  We've seen creators go nearly every direction, and we encourage it!</p>

					<p class="flow-text no-bottom-margin">Here's a few spins we've seen people take:</p>
					<ul class="browser-default">
						<li class="flow-text no-margin"><strong>Full payment up front: </strong>the client must pay 100% of the entire price before the commission will be started.</li>
						<p class="flow-text no-top-margin">This is suitable for small projects, however is not recommended for larger ones as it requires the client to have the full payment ready.  It has the benefit that the creator immediately receives the funds, which can be helpful for assurance and emergencies.</p>
						
						<li class="flow-text no-margin"><strong>Deferred full payment: </strong>the client must pay 100% of the entire price by a certain stage (e.g. after sketching) before the commission will be completed.</li>
						<p class="flow-text no-top-margin">This is recommended for projects which start with small stages (such as sketching) so that, if the client fails to follow through, the creator is not out much.</p>
						
						<li class="flow-text no-margin"><strong>Full payment upon completion (not recommended): </strong>the client must pay the entire price once the work has been completed.</li>
						<p class="flow-text no-top-margin">This is not a good idea except for very, very, very few cases (yes, 3 "very"s, its that small).  Through this model, we find creators often get scammed.  A client can request a commission and then either waste the creator's time, or steal the piece entirely without paying.  Creators with this model face a serious risk of being scammed.</p>
						
						<li class="flow-text no-margin"><strong>Staged: </strong>The client must pay a certain percentage of the price by certain stages (e.g. 10% upon sketching, another 40% upon completion of coloring, etc.)</li>
						<p class="flow-text no-top-margin">This works well for somewhat-large projects, as it allows for both parties to have assurance.  The client knows the commission is being worked on, and the creator knows that the client has money.</p>
						
						<li class="flow-text no-margin"><strong>Half: </strong>The client must pay 50% of the price in order for the commission to be started, and the remaining 50% upon completion.</li>
						<p class="flow-text no-top-margin">This is similar to staged, however it requires less constant interaction.  It works similarly and shares the same benefits.</p>
						
						<li class="flow-text no-margin"><strong>Payment plan: </strong>The client makes an initial down payment, then a schedule of payments is negotiated.  From there, the client makes scheduled payments.  This is typically used for high-price items.</li>
						<p class="flow-text no-top-margin">This is recommended for large commissions, especially ones which may cost thousands of dollars.  It is unreasonable to ask the client to pay such an amount at once, however the commission may have too few stages for the staged method to be practical.  Through this method, the client and creator work together in order to agree on a payment plan, which may involve an initial down-payment and further monthly/weekly payments from there.</p>
					</ul>

					<p class="flow-text">There are tons of other possibilities as well!  Creators are welcome to take payments on whatever schedule you want, however, keep in mind that it should protect both parties.</p>

					<p class="flow-text">Additionally, you should include information about currency.  If you are in high-demand, you should note that only clients who pay first will have slots reserved.</p>

					<p class="flow-text">See our <a href="<?= ROOTDIR ?>FAQ/#protect">FAQ</a> for additional things you should keep in mind.</p>

					<p class="flow-text no-bottom-margin">Examples we like:</p>
					<ul class="browser-default">
						<li class="flow-text no-margin">
							<p class="no-margin">Claimed slots have to be paid upfront within 48 hours, via PayPal invoice only! No eCheck payments! They take too long and sometimes get declined! If you fail to pay in time I will scrap your commission and reopen the slot. Don’t claim a slot if you lack the funds!</p>
							<p class="no-margin">​YCH's​ must always be paid upfront within 48 hours after the winner is announced.  Withdrawing yourself from an ongoing auction will lead to being blacklisted.</p>
							<p class="right-align no-margin"><em>Adapted from <a href="https://docs.google.com/document/d/1lk_aEoxVO1KroiscbitTNh8u1EAMXr_o63SHlErLPTA/edit">Hettie</a>'s ToS</em></p>
						</li>

						<li class="flow-text no-margin">
							<p class="no-margin">All payments must be made in full upfront.  The only currencies accepted are DeviantART Points and USD.  Points will only be accepted via the Commission Widget, unless stated otherwise. USD will be accepted via PayPal.</p>
							<p class="right-align no-margin"><em>Adapted from <a href="https://noelanieternal.deviantart.com/">NoelaniEternal</a>'s ToS</em></p>
						</li>

						<li class="flow-text no-margin">
							<p class="no-margin">Payment upfront - your slot is secured once I have received full payment!</p>
							<p class="right-align no-margin"><em>Adapted from <a href="https://twitter.com/NaahvaDoesArt">Naahva</a>'s ToS</em></p>
						</li>
					</ul>
				</div>
				<div class="divider"></div>
				<div class="section" id="cancellation">
					<h4>Cancellation</h4>

					<p class="flow-text no-bottom-margin">Cancellations and refunds are almost as important to think about as payment!  As with payment, there are many routes creators can take, however they usually fall into the following categories:</p>
					
					<ul class="browser-default">
						<li class="flow-text"><strong>No refunds at any time: </strong>once the client has paid, the money is non-refundable.</li>
						<li class="flow-text"><strong>Refunds before starting: </strong>the money (or a portion thereof) may be refunded before the creator has started, however refunds are not offered after the creator has begun work.</li>
						<li class="flow-text"><strong>Incremental: </strong>depending upon the progress of the commission, certain amounts will be refunded.</li>
					</ul>

					<p class="flow-text no-bottom-margin">Examples we like:</p>
					<ul class="browser-default">
						<li class="flow-text no-margin">
							<p class="no-margin">If you want to cancel a commission before I've begun working on it, you'll be refunded 90% of the original cost.  If a commission is already in progress, you will be partially refunded depending on how much I've completed. No refunds on completed work or YCH's!</p>
							<p class="right-align no-margin"><em>Adapted from <a href="https://docs.google.com/document/d/1lk_aEoxVO1KroiscbitTNh8u1EAMXr_o63SHlErLPTA/edit">Hettie</a>'s ToS</em></p>
						</li>

						<li class="flow-text no-margin">
							<p class="no-margin">I have the right to cancel and refund the money to you for your commission at any time.  You have no right to cancel or demand a refund from the artist under any circumstances after payment is received.  If payment has not been made, the commissioner has the right to cancel the commission.</p>
							<p class="right-align no-margin"><em>Adapted from <a href="https://noelanieternal.deviantart.com/">NoelaniEternal</a>'s ToS</em></p>
						</li>
					</ul>
				</div>
				<div class="divider"></div>
				<div class="section" id="shipping">
					<h4>Shipping</h4>

					<p class="flow-text no-margin">Creators should be sure to cover anything related to shipping, if applicable.  If the work is digital, ensure the commissioner knows that they will not receive any physical items.</p>
					<p class="flow-text no-margin">Creators may also wish to denote types of shipping, related costs, etc.</p>

					<p class="flow-text no-bottom-margin">Examples we like:</p>
					<ul class="browser-default">
						<li class="flow-text no-margin">
							<p class="no-margin">This is a digital item and won't be shipped.</p>
							<p class="right-align no-margin"><em>Adapted from <a href="https://twitter.com/NaahvaDoesArt">Naahva</a>'s ToS</em></p>
						</li>

						<li class="flow-text no-margin">
							<p class="no-margin">Items currently only ship within the continental United States and Canada.  They will be shipped through bubble mailers via ground (estimate a week).  If you need faster delivery, please let me know before I being work.</p>
							<p class="right-align no-margin"><em>(Fauxil made this one up)</em></p>
						</li>
					</ul>
				</div>
				<div class="divider"></div>
				<div class="section" id="changes">
					<h4>Changes</h4>

					<p class="flow-text">Creators and clients should have a clear understanding on what changes can be made on finished pieces, and the process surrounding any of these changes.</p>

					<p class="flow-text">We recommend creators provide frequent WIPs (work-in-progress) to the client.  Therefore, changes can be made as soon as possible.</p>

					<p class="flow-text">If a creator accepts written references, they may wish to add additional restrictions regarding those.  Catalyst's Character feature makes it easy to compile artwork, descriptions, and errata in one place, and it is highly recommended that they be used.</p>

					<p class="flow-text no-bottom-margin">Examples we like:</p>
					<ul class="browser-default">
						<li class="flow-text no-margin">
							<p class="no-margin">I will keep you updated with WIPs for the sketch and line art during the process of the commission to make sure you are still satisfied with the project.  Major adjustments are only available during sketch phase. Minor adjustments are available during lineart phase.</p>
							<p class="right-align no-margin"><em>Adapted from <a href="https://docs.google.com/document/d/1d5yqWdobmAwc4rJh6cfvABh-CarNI6zzxh-QXP8fSr4/edit">bunnybb</a>'s ToS</em></p>
						</li>

						<li class="flow-text no-margin">
							<p class="no-margin">Please contact me about edits if you need them. If I forget something that was in the reference(s) provided, I will gladly change them without an extra charge.  You may be charged for additional edits (outside of mistakes on my part) at my discretion.</p>
							<p class="right-align no-margin"><em>Adapted from <a href="http://stealthnachos.weebly.com/terms-of-service.html">Stealthnachos</a>' ToS</em></p>
						</li>

						<li class="flow-text no-margin">
							<p class="no-margin">I'm not responsible for inaccuracies or for fixing a piece, if what you are requesting was not included in your description, or your ref-sheet, or if you didn't speak up during the rough-sketch or lineart-phase.</p>
							<p class="right-align no-margin"><em>Adapted from <a href="https://twitter.com/NaahvaDoesArt">Naahva</a>'s ToS</em></p>
						</li>

						<li class="flow-text no-margin">
							<p class="no-margin">Work In Progress (WIP) will only be given if the commissioner personally asks for it.  I will also provide WIPs if I need to clarify something with the commission.  I will do anything to make my customers happy, however, after a commission is completed, only small changes can be made to them, such as small coloring/marking mistakes.  Any large changes will come with a fee.  Large changes include outfit change, background change, or anything else that would require me to redraw something completely.  If your original description/reference was not clear enough, then you are not allowed to do minor changes without a fee.  Try to be as clear as possible when commissioning to avoid any fees.</p>
							<p class="right-align no-margin"><em>Adapted from <a href="https://noelanieternal.deviantart.com/">NoelaniEternal</a>'s ToS</em></p>
						</li>
					</ul>
				</div>
				<div class="divider"></div>
				<div class="section" id="sharing-of-finished-work">
					<h4>Sharing Of Finished Work</h4>
					<p class="flow-text">Protecting a creator's intellectual property is important both in terms of recognition and to prevent improper use of the artwork by the commissioner. While making a ToS, creators should think of a few key aspects: can their artwork be modified, where it can be shown, the conditions for being able to showcase the art, can a commissioner use the artwork for profit and the rights that the creator keeps over the artwork (the idea, setting, recreating the work, right to profit from the artwork). 
					<p class="flow-text">We recommend that creators and commissioners establish thorough boundaries ahead of time so that both the commissioner and artist knows what can or cannot done with the artwork and to prevent creating any disputes in the future. </p>
					<p class="flow-text no-bottom-margin">Examples we like:</p>
					<ul class="browser-default">
						<li class="flow-text no-margin">
							<p class="no-margin">I hold the rights to the produced artwork.
								You can not use or repost my art in any way, shape, or form without my permission unless you're the Commissioner. </p>
								<p class="no-margin">I may use the artwork for art collections, sale and presentation.
								If you want to make it private (I won't post it and/or  I won't use it for Sale) let me know.
								Not using it as Sales is free - but not posting it aka 'Make it Private' comes with a fee. </p>
							<p class="right-align no-margin"><em>Adapted from <a href="https://twitter.com/NaahvaDoesArt">Naahva</a>'s ToS</em></p>
						</li>

						<li class="flow-text no-margin">
							<p class="no-margin">When the artwork is finished you will be sent one larger resolution for personal use and one smaller resolution for public use.</p>
							<p class="right-align no-margin"><em>Adapted from <a href="https://docs.google.com/document/d/1lk_aEoxVO1KroiscbitTNh8u1EAMXr_o63SHlErLPTA/edit">Hettie</a>'s ToS</em></p>
						</li>

						<li class="flow-text no-margin">
							<p class="no-margin">You may upload the piece to any website so long as you give credit to me in the description.</p>
							<p class="no-margin"> I take private commissions (commissions I will not post or use for prints), but require a privacy fee of 40% to cover advertisement and possible revenue losses.</p>
							<p class="no-margin"> Under no circumstances will I remove art from my gallery if the privacy fee hasn’t been paid.	This does not give the user commercial rights, simply the right to keep the commission private or released only at their discretion. This does not apply to timed privacy up to a month. In the instance of a gift that you’d rather not have me post before a certain time, I do not charge the additional fee, as long as it’s within one calendar month from the time of completion. </p>
							<p class="right-align no-margin"><em>Adapted from <a href="http://stealthnachos.weebly.com/terms-of-service.html">Stealthnachos</a>' ToS</em></p>
						</li>
						<li class="flow-text no-margin">
							<p class="no-margin">Once completed I will link the files to you via dropbox unless you have provided me with a designated email address for the files to be sent to. </p>
							<p class="right-align no-margin"><em>Adapted from <a href="https://docs.google.com/document/d/1d5yqWdobmAwc4rJh6cfvABh-CarNI6zzxh-QXP8fSr4/edit">Bunnybb</a>'s TOS</em></p>
						</li>
					</ul>

				</div>
				<div class="divider"></div>
				<div class="section" id="copyright">
					<h4>Copyright</h4>
				</div>
				<div class="divider"></div>
				<div class="section" id="hall-of-fame">
					<h4>Hall of Fame</h4>
				</div>
			</div>
			<div class="col s12 m3 l2 hide-on-small-only">
				<?= Values::createTOC([
					["intro", "Introduction"],
					["general-terms", "General Terms"],
					["limits", "Limits"],
					["contact-preferences", "Contact Preferences"],
					["payment", "Payment"],
					["cancellation", "Cancellation"],
					["shipping", "Shipping"],
					["changes", "Changes"],
					["sharing-of-finished-work", "Sharing Of Finished Work"],
					["copyright", "Copyright"],
					["hall-of-fame", "Hall of Fame"],
				]) ?>
			</div>
		</div>
<?php
require_once Values::FOOTER_INC;


