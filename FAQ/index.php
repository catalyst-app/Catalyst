<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\Page\Values;
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::FAQ[0]);
define("PAGE_TITLE", Values::createTitle(Values::FAQ[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColorHex());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("FAQ");
?>
			<div class="row"><div class="col s12 m9 l10">
				<div class="section hide-on-med-and-up">
<?= Values::createInlineTOC([
	["glossary", "Important definitions"],
	["getting-started", "How do I get started?"],
	["rules", "What rules are in place?"],
	["users", "Who can use Catalyst?"],
	["cost", "What does it cost?"],
	["difference", "What sets us apart?"],
	["security", "Is my information protected?"],
	["suggestions", "How can I suggest features?"],
	["payment", "How do artists get paid?"],
	["protect", "How do we protect artists?"],
	["scam", "What if I get scammed?"],
	["color", "Why can I change the site's colors?"],
	["markdown", "How can I format text?"],
]) ?>
				</div>
				<div class="divider hide-on-med-and-up"></div>
				<div class="section" id="important-definitions">
					<h4>Important definitions</h4>
					
					<p class="flow-text"><strong>User:</strong> anyone who makes an account on our platform</p>
					<p class="flow-text"><strong>Character:</strong> a collection of information and images (reference sheets, art, etc.) about a character which can be easily shared with artists</p>
					<p class="flow-text"><strong>Artist:</strong> a special "second profile" in which a user can advertise commission information</p>
					<p class="flow-text"><strong>Commission:</strong> something which is custom-created for a customer based on their requirements</p>
					<p class="flow-text"><strong>Commission Type:</strong> a type of commission the artist offers.  This can vary from artist to artist, however typically is separated by style (traditional, digital, etc.), type (headshot, bust), or both!</p>
					<p class="flow-text"><strong>Commission Modifier:</strong> a list of preset things which may add to a commission price.  Can be things such as NSFW, backgrounds, multiple characters, etc.</p>
					<p class="flow-text"><strong>Artist's Terms of Service:</strong> a set of rules you must follow in order to commission an artist (may cover things such as commercial use and refunds)</p>
				</div>
				<div class="divider"></div>
				<div class="section" id="getting-started">
					<h4>How do I get started?</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="rules">
					<h4>What rules are in place?</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="users">
					<h4>Who can use Catalyst?</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="cost">
					<h4>What does it cost?</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="difference">
					<h4>What sets us apart?</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="security">
					<h4>Is my information protected?</h4>
					
					<p class="flow-text">We care about your security.  We've seen many similar websites be attacked and lots of important user information breached.  As such, we take extreme precautions about what data we store and how we store it.</p>

					<p class="flow-text">Here are some of the things that we do to ensure your security and privacy.</p>
					<ul class="browser-default">
						<li class="flow-text">Every connection to our site is made with the newest SSL and TLS technology</li>
						<ul>
							<li>Our certificates, as of January 16, 2018, are issued by LetsEncrypt.  We score an A+ rating on Qualys SSL Server Test, scoring a 100% in Certificate integrity.</li>
							<li>Additionally, we use HPKP (HTTP Public Key Pinning) and HSTS (HTTP Strict Transport Security) to ensure that your connection is always secure, and always to us.</li>
							<li>We also employ Diffie-Hellman (DH) Parameters and SNI in order to keep your connection secure.</li>
						</ul>
						<li class="flow-text">User passwords are never stored</li>
						<ul>
							<li>We use per-user salts - if two users have the same password, the hashes are different</li>
							<li>Additionally, we require all passwords be 8 characters long, and that they have no maximum length.  This encourages the use of ridiculously long random passwords.</li>
							<li>We store passwords after they are hashed with the bcrypt algorithm and a high work factor (currently 14).  If the user is to use a password of only 8 characters, the password would take (by current estimates) billions of years to crack.</li>
						</ul>
						<li class="flow-text">Two Factor Authentication</li>
						<ul>
							<li>We allow our users to protect their accounts using TOTP one-time password authentication.</li>
							<li>This is opt-in based, and our verification tests these 6-digit tokens for validity within a +-1 minute window.</li>
							<li>Each token lasts for 30 seconds</li>
						</ul>
						<li class="flow-text">Binding of database queries</li>
						<ul>
							<li>Following the lessons of <a href="https://xkcd.com/327/">Bobby Tables</a>, we bind all parameters using a database abstraction library.</li>
							<li>This prevents any possibility of SQL injection.</li>
						</ul>
						<li class="flow-text">Few outside dependencies on the server</li>
						<ul>
							<li>We use no external libraries for the backend except for the popular <a href="https://github.com/PHPMailer/PHPMailer">PHPMailer</a> and <a href="http://phpqrcode.sourceforge.net/">phpqrcode</a>.</li>
							<li>PHPMailer is used in at least 25% of all websites (based on Wordpress usage) and much more than that, and is only invoked when we need to send user emails.</li>
							<li>phpqrcode is small and used for generating 2FA QR codes</li>
							<li>Other than PHPMailer and phpqrcode, all of our code is home-grown.</li>
						</ul>
						<li class="flow-text">Randomization of uploaded information</li>
						<ul>
							<li>All uploaded information is stored with random paths.</li>
							<li>This prevents malicious users from taking the path to one file and determining a pattern to get others</li>
						</ul>
						<li class="flow-text">Open-sourced on GitHub</li>
						<ul>
							<li>All of our code is on GitHub where anyone can view it for transparency reasons</li>
						</ul>
						<li class="flow-text">And much more!</li>
					</ul>
				</div>
				<div class="divider"></div>
				<div class="section" id="suggest">
					<h4>How can I suggest features?</h4>
					
					<p class="flow-text">Please see our <a href="<?= ROOTDIR ?>FeatureBoard">feature board</a> for more information</p>
				</div>
				<div class="divider"></div>
				<div class="section" id="payment">
					<h4>How do artists get paid?</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="protect">
					<h4>How do we protect artists?</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="scam">
					<h4>What if I get scammed?</h4>
					
				</div>
				<div class="divider"></div>
				<div class="section" id="color">
					<h4>Why can I change the site's colors?</h4>
					
					<p class="flow-text">We believe that you should be able to make our platform your own.  Therefore, you may choose your own color theme which will be seen around the site and when anyone views your profile.  You can customize the colors of characters, user profiles, and artist's pages.</p>
				</div>
				<div class="divider"></div>
				<div class="section" id="markdown">
					<h4>How can I format text?</h4>
					
					<p class="flow-text">Catalyst uses a modified version of Markdown for formatting.  Please see <a href="<?= ROOTDIR ?>Markdown">this page</a> for help.</p>
				</div>
			</div>
			<div class="col s12 m3 l2 hide-on-small-only">
<?= Values::createTOC([
	["glossary", "Important definitions"],
	["getting-started", "How do I get started?"],
	["rules", "What rules are in place?"],
	["users", "Who can use Catalyst?"],
	["cost", "What does it cost?"],
	["difference", "What sets us apart?"],
	["security", "Is my information protected?"],
	["suggestions", "How can I suggest features?"],
	["payment", "How do artists get paid?"],
	["protect", "How do we protect artists?"],
	["scam", "What if I get scammed?"],
	["color", "Why can I change the site's colors?"],
	["markdown", "How can I format text?"],
]) ?>
			</div>
		</div>
<?php
require_once Values::FOOTER_INC;
