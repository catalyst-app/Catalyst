<?php

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/init.php";
use \Catalyst\Page\UniversalFunctions;
use \Catalyst\Page\Values;
use \Catalyst\User\User;

define("PAGE_KEYWORD", Values::MARKDOWN[0]);
define("PAGE_TITLE", Values::createTitle(Values::MARKDOWN[1], []));

if (User::isLoggedIn()) {
	define("PAGE_COLOR", User::getCurrentUser()->getColor());
} else {
	define("PAGE_COLOR", Values::DEFAULT_COLOR);
}

require_once Values::HEAD_INC;

echo UniversalFunctions::createHeading("Markdown");
?>
			<div class="row"><div class="col s12 m9 l10"><div class="section">
				<p class="flow-text">Catalyst uses a modified version of Markdown to render certian areas of the site, typically descriptions or bodies.</p>
				<p class="flow-text">Markdown allows for text to be easily formatted while still being clean and simple.</p>
			</div>
			<div class="divider hide-on-med-and-up"></div>
			<div class="section hide-on-med-and-up">
<?= Values::createInlineTOC([
	["playground", "Playground"],
	["headings", "Headings"],
	["basic-formatting", "Basic Formatting"],
	["escaping", "Escaping"],
	["superscripts", "Superscripts"],
	["dividers", "Dividers"],
	["links", "Links"],
	["images", "Images"],
	["lists", "Lists"],
	["checklists", "Checklists"],
	["tables", "Tables"],
	["emoji", "Emoji"],
	["containers", "Containters"],
]) ?>
			</div>
			<div class="divider"></div>
			<div class="section" id="playground">
				<h4 style="margin-top: -80px; padding-top: 80px;">Playground</h4>
				<p class="flow-text">Feel free to use the box below and see how it will look!  Output will appear&nbsp;<span class="hide-on-med-and-up">below it</span><span class="hide-on-small-only">to the right</span>.</p>
				<div class="row">
					<div class="col s12 m6">
						<div class="input-field col s12">
							<textarea id="playground" class="markdown-field materialize-textarea">
<?= htmlspecialchars(<<<MD
#### Sample text

Wow, markdown is **really** cool!  I can do almost *anything* with it!
MD
) ?> 
</textarea>
							<label for="playground">Playground</label>
						</div>
					</div>
					<div class="col s12 m6 markdown-target markdown-preview raw-markdown" data-field="playground">
<?= htmlspecialchars(<<<MD
#### Sample text

Wow, markdown is **really** cool!  I can do almost *anything* with it!
MD
) ?> 
					</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section" id="headings">
				<h4 style="margin-top: -80px; padding-top: 80px;">Headings</h4>
				<p class="flow-text">You may use one to six hash marks (<span class="code">#</span>) at the beginning of a line to denote a heading.</p>
				<p class="flow-text">A single hash will yield the largest heading, and six will create the smallest.</p>
				<p class="flow-text">It is recommended to use no less than three due to mobile device limitations.</p>
				<p class="flow-text">Additionally, you may use <span class="code">===</span> or <span class="code">---</span> to create headings, as seen below.</p>
				<div class="row">
					<div class="col s12 m6"><div class="code-block"><p>
<?= implode("&nbsp;</p><p>", explode("\n",htmlspecialchars(<<<MD
# Largest
## Larger
### Large
#### Medium
##### Small
###### Smallest

Big ole heading
===

Smaller heading
---
MD
))) ?> 
</p></div></div>
					<div class="raw-markdown markdown-preview col s12 m6">
<?= htmlspecialchars(<<<MD
# Largest
## Larger
### Large
#### Medium
##### Small
###### Smallest

Big ole heading
===

Smaller heading
---
MD
) ?> 
</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section" id="basic-formatting">
				<h4 style="margin-top: -80px; padding-top: 80px;">Basic Formatting</h4>
				<p class="flow-text">To make your text <strong>bold</strong> place two asterisks (<span class="code">*</span>) around the text you want to embolden. Alternatively, you can use two underscores (<span class="code">_</span>) in place of the asterisks.  You can use this to highlight important information, in a sentence such as payment deadlines, instructions, or small headers.</p>
				<p class="flow-text">To use <i>italic</i> text, place a single asterisk around the text.  Italics are typically used for emphasis, however to a lesser magnitude than bolding.</p>
				<p class="flow-text">You can <u>underline</u> text with a single underscore on each side.  This too shows emphasis or, in some cases, titles.</p>
				<p class="flow-text">Strikethrough is easy too!  Just add two tildes (<span class="code">~</span>) on each side!  Strikethrough is typically used to show where incorrect or outdated information was replaced.</p>
				<p class="flow-text">Additionally, you can combine any of these.  For example, if you wish to use both bold and underline together, you can use <span class="code">__*</span>.</p>
				<div class="row">
					<div class="col s12 m6"><div class="code-block"><p>
<?= implode("&nbsp;</p><p>", explode("\n",htmlspecialchars(<<<MD
Please make all payments **within ~~three days~~ one week** of commissioning.  If they aren't recieved, your commision *will* be __canceled__.

~~_*__And, why not, let's try them all at once!__*_~~
MD
))) ?> 
</p></div></div>
					<div class="raw-markdown markdown-preview col s12 m6">
<?= htmlspecialchars(<<<MD
Please make all payments **within ~~three days~~ one week** of commissioning.  If they aren't recieved, your commision *will* be __canceled__.

~~_*__And, why not, let's try them all at once!__*_~~
MD
) ?> 
</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section" id="escaping">
				<h4 style="margin-top: -80px; padding-top: 80px;">Escaping</h4>
				<p class="flow-text">Sometimes, you may want to use asterisks and other special characters in your text.  This can cause issues with markdown, as it might think you are trying to format something.  If you experience this, you can place a backslash (<span class="code">\</span>, above the enter key) before the character.</p>
				<p class="flow-text">In Markdown, multiple line breaks are treated as one.  In order to use multiple line breaks, use a backslash (<span class="code">\</span>) before the newline.</p>
				<div class="row">
					<div class="col s12 m6"><div class="code-block"><p>
<?= implode("&nbsp;</p><p>", explode("\n",htmlspecialchars(<<<MD
I\_can\*use\_all\*the\_characters\*I\_want!

No


backslash


here

compared to

With
\
\
backslash
MD
))) ?> 
</p></div></div>
				<div class="raw-markdown markdown-preview col s12 m6">
<?= htmlspecialchars(<<<MD
I\_can\*use\_all\*the\_characters\*I\_want!

No


backslash


here

compared to

With
\
\
backslash
MD
) ?> 
</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section" id="escaping">
				<h4 style="margin-top: -80px; padding-top: 80px;">Superscripts</h4>
				<p class="flow-text">A superscript is possible too!  To raise your text to the <sup>next <sup>level</sup></sup>, just add a caret (<span class="code">^</span>) around the word or group of letters!</p>
				<div class="row">
					<div class="col s12 m6"><div class="code-block"><p>
<?= implode("&nbsp;</p><p>", explode("\n",htmlspecialchars(<<<MD
^Sample^ ^text^

Wh^oo^p
MD
))) ?> 
</p></div></div>
				<div class="raw-markdown markdown-preview col s12 m6">
<?= htmlspecialchars(<<<MD
^Sample^ ^text^

Wh^oo^p
MD
) ?> 
</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section" id="dividers">
				<h4 style="margin-top: -80px; padding-top: 80px;">Dividers</h4>
				<p class="flow-text">To create a divider in your text, use three hyphens (<span class="code">-</span>).  A divider is a horizontal line across the page which can be used to differentiate between different sections of content.</p>
				<p>Note: due to the heading also created with 3 hyphens, it is important that there are 2 lines before it.</p>
				<div class="row">
					<div class="col s12 m6"><div class="code-block"><p>
<?= implode("&nbsp;</p><p>", explode("\n",htmlspecialchars(<<<MD
Something neat

---

More stuff
MD
))) ?> 
</p></div></div>
				<div class="raw-markdown markdown-preview col s12 m6">
<?= htmlspecialchars(<<<MD
Something neat

---

More stuff
MD
) ?> 
</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section" id="links">
				<h4 style="margin-top: -80px; padding-top: 80px;">Links</h4>
				<p class="flow-text">You can create a clickable link with or without a representative title as shown below.</p>
				<p class="flow-text">On Catalyst, all links are shown in bold, colored text.  This cannot be changed, however you may apply additional formatting such as italics.</p>
				<div class="row">
					<div class="col s12 m6"><div class="code-block"><p>
<?= implode("&nbsp;</p><p>", explode("\n",htmlspecialchars(<<<MD
https://catalystapp.co

[The Best Site!](https://catalystapp.co)

*For more information, check out the [About](https://catalystapp.co) page.*
MD
))) ?> 
</p></div></div>
				<div class="raw-markdown markdown-preview col s12 m6">
<?= htmlspecialchars(<<<MD
https://catalystapp.co

[The Best Site!](https://catalystapp.co/)

*For more information, check out the [About](https://catalystapp.co) page.*
MD
) ?> 
</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section" id="images">
				<h4 style="margin-top: -80px; padding-top: 80px;">Images</h4>
				<p class="flow-text">You may use images in a similar manner to links, prepending it with an exclamation point.  However, images are special: they do not need a title.</p>
				<p class="flow-text">If you add images, please ensure they are scaled to a decent size, or they may look bad/break your page on mobile browsers.</p>
				<div class="row">
					<div class="col s12 m6"><div class="code-block"><p>
<?= implode("&nbsp;</p><p>", explode("\n",htmlspecialchars(<<<MD
Below is a sample image:
![](http://via.placeholder.com/200x150)
MD
))) ?> 
</p></div></div>
				<div class="raw-markdown markdown-preview col s12 m6">
<?= htmlspecialchars(<<<MD
Below is a sample image:
![](http://via.placeholder.com/200x150)
MD
) ?> 
</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section" id="lists">
				<h4 style="margin-top: -80px; padding-top: 80px;">Lists</h4>
				<p class="flow-text">Lists make things neat!</p>
				<p class="flow-text"><strong>Ordered Lists</strong> can begin with any number, however must increment by one. Each line of text must be separated by a newline, and the number must be the first thing on the line.</p>
				<div class="row">
					<div class="col s12 m6"><div class="code-block"><p>
<?= implode("&nbsp;</p><p>", explode("\n",htmlspecialchars(<<<MD
1. This is the first item of the list.
2. This is the second item of the list.

And another wonky one:

5. This is the first item of the list.
6. This is the second item of the list.
MD
))) ?> 
</p></div></div>
				<div class="raw-markdown markdown-preview col s12 m6">
<?= htmlspecialchars(<<<MD
1. This is the first item of the list.
2. This is the second item of the list.

And another wonky one:

5. This is the first item of the list.
6. This is the second item of the list.
MD
) ?> 
</div>
				</div>
				<p class="flow-text"><strong>Unordered Lists</strong> can be used if something does not need to be enumerated.  Simply replace the numerals with dashes or stars (but don't mix them!).</p>
				<p class="flow-text">These can be used almost anywhere you can think of!</p>
				<div class="row">
					<div class="col s12 m6"><div class="code-block"><p>
<?= implode("&nbsp;</p><p>", explode("\n",htmlspecialchars(<<<MD
Who needs order?  
* Not
* This
* List

Make sure not to:
* Mix and match
* Take breaks between lines
* Add spaces, unless you want to nest your lists
MD
))) ?> 
</p></div></div>
				<div class="raw-markdown markdown-preview col s12 m6">
<?= htmlspecialchars(<<<MD
Who needs order?  
* Not
* This
* List

Make sure not to:
* Mix and match
* Take breaks between lines
* Add spaces, unless you want to nest your lists
MD
) ?> 
</div>
				</div>
				<p class="flow-text"><strong>Nested Lists</strong> are now easier than ever! Just prepend at least four (4) spaces for each level.</p>
				<p class="flow-text">Let’s try it with some of the lists above:</p>
				<p class="flow-text">Note: leading spaces are represented with dots (<span class="code">·</span>) in this example.</p>
				<div class="row">
					<div class="col s12 m6"><div class="code-block"><p>
<?= implode("&nbsp;</p><p>", explode("\n",htmlspecialchars(<<<MD
1. This is the first item of the list.
····* Here's a subitem!
····* And another
2. This is the second item of the list.
····1. And this is the first subitem of this group.
····2. This is the second
MD
))) ?> 
</p></div></div>
				<div class="raw-markdown markdown-preview col s12 m6">
<?= htmlspecialchars(<<<MD
1. This is the first item of the list.
    * Here's a subitem!
    * And another
2. This is the second item of the list.
    1. And this is the first subitem of this group.
    2. This is the second
MD
) ?> 
</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section" id="checklists">
				<h4 style="margin-top: -80px; padding-top: 80px;">Checklists</h4>
				<p class="flow-text">To show checkboxes, for example to show that you have or have not completed something/reached a goal, use the following bracket syntax:</p>
				<p class="flow-text">For an empty checkbox, start your line with a pair of brackets containing only a space: <span class="code">[ ]</span>.  For a checked checkbox, mark it with an x: <span class="code">[x]</span>.</p>
				<p class="flow-text">These can be parts of lists, tables, and any other features we support!</p>
				<div class="row">
					<div class="col s12 m6"><div class="code-block"><p>
<?= implode("&nbsp;</p><p>", explode("\n",htmlspecialchars(<<<MD
[x] Write checkbox example
[x] Create markdown playground above
[ ] Remember to remove these notes
MD
))) ?> 
</p></div></div>
				<div class="raw-markdown markdown-preview col s12 m6">
<?= htmlspecialchars(<<<MD
[x] Write checkbox example
[x] Create markdown playground above
[ ] Remember to remove these notes
MD
) ?> 
</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section" id="tables">
				<h4 style="margin-top: -80px; padding-top: 80px;">Tables</h4>
				<p class="flow-text">Tables are useful for presenting information in an organized way.</p>
				<p class="flow-text">The markdown syntax for them may seem a bit intimidating, but it's not too complicated. Use vertical bars (<span class="code">|</span>, above the enter key, <strong>not a capital <span class="code">I</span></strong>) to separate your columns.</p>
				<p class="flow-text">Your header row should have at least 3 hyphens in the next row (no other text!), and then you just a new line for a new row. Colons can be used to customize alignment. Note that markdown works the same in tables, so feel free to bold, italic, strikethrough, etc.</p>
				<p class="flow-text">Please be wary of mobile users, don't make your tables too wide!</p>
				<p class="flow-text">However, you don't need to spend the time to make the raw Markdown aligned, it works so long as the basic syntax is there (please note, the row below the header must have at least three characters in each column)!</p>
				<p class="flow-text">You may find <a href="https://www.tablesgenerator.com/markdown_tables">this</a> table generator useful.</p>
				<div class="row">
					<div class="col s12 m6"><div class="code-block"><p>
<?= implode("&nbsp;</p><p>", explode("\n",htmlspecialchars(<<<MD
| tables       | are           | neat                          |
|--------------|:-------------:|------------------------------:|
| column 1 is  | left-aligned  | **markdown** *still* _works_  |
| column 2 is  | centered      | exactly the same way          |
| column 3 is  | right-aligned | ~~notice my buldge owo~~      |

---

| Step | Name | Thinks to think about |
|:--|:--|:--|
| One | Create header row | Think of header items? |
| Two | Create second row | Alignment? |
| Three | Body | What should be used as filler content? *oh wait* |
MD
))) ?> 
</p></div></div>
				<div class="raw-markdown markdown-preview col s12 m6">
<?= htmlspecialchars(<<<MD
| tables       | are           | neat                          |
|--------------|:-------------:|------------------------------:|
| column 1 is  | left-aligned  | **markdown** *still* _works_  |
| column 2 is  | centered      | exactly the same way          |
| column 3 is  | right-aligned | ~~notice my buldge owo~~      |

---

| Step | Name | Thinks to think about |
|:--|:--|:--|
| One | Create header row | Think of header items? |
| Two | Create second row | Alignment? |
| Three | Body | What should be used as filler content? *oh wait* |
MD
) ?> 
</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section" id="emoji">
				<h4 style="margin-top: -80px; padding-top: 80px;">Emoji</h4>
				<p class="flow-text">Apparently these are useful for something?  Anyways, we support them.</p>
				<p class="flow-text">For our emoji, we use a limited set of <a href="https://github.com/twitter/twemoji">twemoji</a>.  To see all the emoji we support, go <a href="Emoji">here</a>.</p>
				<p class="flow-text">To use emoji, place a colon (<span class="code">:</span>) on each side of the identifier.  The list of identifiers are <a href="Emoji">here</a>.</p>
				<div class="row">
					<div class="col s12 m6"><div class="code-block"><p>
<?= implode("&nbsp;</p><p>", explode("\n",htmlspecialchars(<<<MD
:fox_face: foxes are cool
MD
))) ?> 
</p></div></div>
				<div class="raw-markdown markdown-preview col s12 m6">
<?= htmlspecialchars(<<<MD
:fox_face: foxes are cool
MD
) ?> 
</div>
				</div>
			</div>
			<div class="divider"></div>
			<div class="section" id="containers">
				<h4 style="margin-top: -80px; padding-top: 80px;">Containers</h4>
				<p class="flow-text">We have created several custom containers for your use.  These are wrapped in at least three colons (<span class="code">:</span>) above and below the content.  The top set should have a token following it (i.e. <span class="code">::: token</span>).</p>
				<p class="flow-text">To nest, the outermost levels must have more colons than the insides.  For example, if you had a parent and child, the parent would use four colons and the child three.</p>
				<p class="flow-text"><strong>Collapsible boxes</strong> provide a way to create "drawers" of information, either opened or closed (default).</p>
				<p class="flow-text">You may use the <span class="code">box label</span> or <span class="code">box-open label</span> token to denote one such container.</p>
				<div class="row">
					<div class="col s12 m6"><div class="code-block"><p>
<?= implode("&nbsp;</p><p>", explode("\n",htmlspecialchars(<<<MD
::::box-open My box!
:::box Click me!
##### All your usual markdown works here!

**So cool!**
:::
::::
MD
))) ?> 
</p></div></div>
				<div class="raw-markdown markdown-preview col s12 m6">
<?= htmlspecialchars(<<<MD
::::box-open My box!
:::box Click me!
##### All your usual markdown works here!

**So cool!**
:::
::::
MD
) ?> 
</div>
				</div>
				<p class="flow-text"><strong><span class="red-text">C</span><span class="orange-text">o</span><span class="yellow-text">l</span><span class="green-text">o</span><span class="indigo-text">r</span></strong> can add a unique splash to your text!</p>
				<p class="flow-text">Use the <span class="code">backcolor #abcdef</span> token to change the background color or the <span class="code">textcolor #abcdef</span> token for the text color.</p>
				<p class="flow-text"><span class="code">#abcdef</span> is a 6 character hexadecimal color.  These values can be picked from our <a target="_blank" href="https://www.materialui.co/colors">recommended palette</a> (these blend nicely with the site) or <a target="_blank" href="https://www.webpagefx.com/web-design/color-picker/">all colors</a>.</p>
				<p class="flow-text">It is recommended <u>not</u> to use a background color, or a light tint if needed, due to the rest of the site's design.</p>
				<p class="flow-text">Try to use a sane color for this, or you may get a result like you see below.</p>
				<div class="row">
					<div class="col s12 m6"><div class="code-block"><p>
<?= implode("&nbsp;</p><p>", explode("\n",htmlspecialchars(<<<MD
::::textcolor #2f0
:::backcolor #ff0
#### *I'm a sparklefox!!  YAY!!1!*
:::
::::
MD
))) ?> 
</p></div></div>
				<div class="raw-markdown markdown-preview col s12 m6">
<?= htmlspecialchars(<<<MD
::::textcolor #2f0
:::backcolor #ff0
#### *I'm a sparklefox!!  YAY!!1!*
:::
::::
MD
) ?> 
</div>
				</div>
			</div>
			</div>
			<div class="col s12 m3 l2 hide-on-small-only">
<?= Values::createTOC([
	["playground", "Playground"],
	["headings", "Headings"],
	["basic-formatting", "Basic Formatting"],
	["escaping", "Escaping"],
	["superscripts", "Superscripts"],
	["dividers", "Dividers"],
	["links", "Links"],
	["images", "Images"],
	["lists", "Lists"],
	["checklists", "Checklists"],
	["tables", "Tables"],
	["emoji", "Emoji"],
	["containers", "Containters"],
]) ?>
			</div>
		</div>
<?php
require_once Values::FOOTER_INC;
 
 
