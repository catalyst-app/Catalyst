<?php
header("Content-Type: application/javascript; charset=UTF-8", true);

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

define("NO_SESSION", true);

require_once REAL_ROOTDIR."src/initializer.php";
use \Catalyst\API\ErrorCodes;
use \Catalyst\{Controller, Secrets};
use \Catalyst\Database\Database;
use \Catalyst\Database\Query\AbstractQuery;
use \Catalyst\Form\FormRepository;
use \Catalyst\Page\{Resources, UniversalFunctions};
use \Catalyst\Tokens;

$forms = FormRepository::getAllForms();

// the following series of whitespace is dedicated to SINNERSCOUT for being cool and a patron and stuff:
/*
U+0020  SPACE   foo bar, size: depends on font, typically 1/4 em, often adjusted
U+00A0  NO-BREAK SPACE  foo bar, size: as a space, but often not adjusted
U+1680  OGHAM SPACE MARK	foo bar, size: unspecified; usually not really a space but a dash
U+180E  MONGOLIAN VOWEL SEPARATOR   foo᠎bar, size: no width
U+2000  EN QUAD foo bar, size: 1 en (= 1/2 em)
U+2001  EM QUAD foo bar, size: 1 em (nominally, the height of the font)
U+2002  EN SPACE	foo bar, size: 1 en (= 1/2 em)
U+2003  EM SPACE	foo bar, size: 1 em
U+2004  THREE-PER-EM SPACE  foo bar, size: 1/3 em
U+2005  FOUR-PER-EM SPACE   foo bar, size: 1/4 em
U+2006  SIX-PER-EM SPACE	foo bar, size: 1/6 em
U+2007  FIGURE SPACE	foo bar, size: “tabular width”, the width of digits
U+2008  PUNCTUATION SPACE   foo bar, size: the width of a period “.”
U+2009  THIN SPACE  foo bar, size: 1/5 em (or sometimes 1/6 em)
U+200A  HAIR SPACE  foo bar, size: narrower than thin space
U+200B  ZERO WIDTH SPACE	foo​bar, size: nominally no width, but may expand
U+202F  NARROW NO-BREAK SPACE   foo bar, size: narrower than no-break space (or space)
U+205F  MEDIUM MATHEMATICAL SPACE   foo bar, size: 4/18 em
U+3000  IDEOGRAPHIC SPACE   foo　bar, size: the width of ideographic (cjk) characters.
U+FEFF  ZERO WIDTH NO-BREAK SPACE   foo﻿bar, size: no width (the character is invisible)
*/

?>
// ~))))'> 
//
// What's this, a possum in the JavaScript?
// Impossumible!
//
// (yes spade im looking at you)

/* GENERAL FUNCTIONS */
var humanFileSize = function(size) {
	var i = Math.floor( Math.log(size) / Math.log(1024) );
	return ( size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'KB', 'MB', 'GB', 'TB'][i];
};


(function($){
	$(function(){
		// toish: "Put it in a closure":tm:
		function materializeOnload() {
			if (typeof $ == "undefined" || typeof M == "undefined") {
				window.log(<?= json_encode(basename(__FILE__)) ?>, "materializeOnload - deferring for 100ms (even though I'm not happy about it...)");
				setTimeout(materializeOnload, 100);
				return;
			}

			if (window.devMode) {
				console.time("materializeOnload");
			}

			window.log(<?= json_encode(basename(__FILE__)) ?>, "materializeOnload - invoked");
			// its bullshit that they removed the old toast function API
			window.M["escapeToast"] = function(a) {
				M.toast({html: $("<span></span>").text(a).html()});
			};
			window.Materialize = window.M; // legacy
			M.FormSelect.init(
				document.querySelectorAll("select"),
				{
					dropdownOptions: {
						container: document.body,
						belowOrigin: true
					}
				}
			);
			// $('select').attr("required", false);
			$(".sidenav").sidenav();
			$(".modal").modal();
			$('.pushpin').pushpin({
				top: 200,
				offset: 72
			});
			$('.tooltipped').tooltip();
			$(".collapsible").collapsible();
			$(".dropdown-trigger").dropdown();
			$('.psuedo-required, .psuedo-required input').attr("required", false);
			$(".raw-markdown, .raw-inline-markdown").each(function() {renderMarkdownArea(this);});
			$(".raw-emoji").each(function() {$(this).html(twemoji.parse($(this).html())).removeClass("raw-emoji");});
			$('.fixed-action-btn:not(.horizontal)').floatingActionButton();
			$('.fixed-action-btn.horizontal').floatingActionButton({
				direction: 'left'
			});
			$(".totp-preview").each(function(a, b) {
				setInterval(function() {
					$(b).text(
						totp($(b).attr("data-secret"), Math.floor(new Date().getTime()/30000)-1)+
						","+
						totp($(b).attr("data-secret"), Math.floor(new Date().getTime()/30000))+
						","+
						totp($(b).attr("data-secret"), Math.floor(new Date().getTime()/30000)+1)
					);
				}, 1000);
			});
			M.Datepicker.init(document.querySelectorAll('.datepicker'), {
				showClearBtn: true
			});
			M.Timepicker.init(document.querySelectorAll('.timepicker'), {
				showClearBtn: true
			});
			if (/<?= Tokens::COMMISSION_TYPE_TOKEN_REGEX ?>/.test(window.location.hash.substr(1))) {
				if ($("#commission-type-info-modal-"+window.location.hash.substr(1)).length) {
					M.Modal.getInstance($("#commission-type-info-modal-"+window.location.hash.substr(1))[0]).open();
				}
			}

			if (window.devMode) {
				console.timeEnd("materializeOnload");
			}
		}

		materializeOnload();

		/* GENERIC FUNCTIONS */
		jQuery.fn.swapWith = function(to) {
			return this.each(function() {
				var copy_to = $(to).clone(true);
				var copy_from = $(this).clone(true);
				$(to).replaceWith(copy_from);
				$(this).replaceWith(copy_to);
			});
		};

		/* IMAGE "DRAWERS" */
		$(document).on("mousewheel", ".horizontal-scrollable-container", function(e, delta) {
			var orig = $(this).scrollLeft();
			$(this).scrollLeft(this.scrollLeft + (e.originalEvent.deltaY * 1));
			if ($(this).scrollLeft() != orig) {
				e.preventDefault();
			}
		});

		/* EASTER EGGS */
		<?php if (Controller::isAprilFools()): ?>
			cheet('b u l g e', function () {
				$(".brand-logo").html("OwO what's this?");
				window.log("Easter egg", "smh my head if you found that you probably are in here already");
			});
		<?php endif; ?>

		/* FORMS */
		if (window.devMode) {
			console.time("Legacy forms definition");
		}
		$(document).on("change", ":checkbox", function(e) {
			var labelSpan = $(this).next();
			// restore original text (if applicable)
			$(labelSpan).text($(labelSpan).attr("data-original"));
		});
		// $(document).on("input", ".marked-invalid", function(e) {
		// 	if ($(this).attr("type") == "checkbox") {
		// 		return; // should be caught in :checkbox.change
		// 	}
		// 	$(this).removeClass("marked-invalid").removeClass("invalid");
		// });
		<?php foreach ($forms as $form): ?>
			<?= $form->getAllJs(); ?>
		<?php endforeach; ?>
		if (window.devMode) {
			console.timeEnd("Legacy forms definition");
		}

		/* IMAGE UPLOADING WITH NSFW, CAPTIONS, and INFO */
		<?php require_once __DIR__.DIRECTORY_SEPARATOR.'image_upload_arranger.js'; ?>

		/* FILE PICKER */
		<?php require_once __DIR__.DIRECTORY_SEPARATOR.'file_picker.js'; ?>

		/* SUBFORM THING */
		<?php require_once __DIR__.DIRECTORY_SEPARATOR.'subform_entry_field.js'; ?>

		/* COLOR PICKER */
		<?php require_once __DIR__.DIRECTORY_SEPARATOR.'color_picker.js'; ?>

		/* TOGGLEABLE BUTTONS */
		<?php require_once __DIR__.DIRECTORY_SEPARATOR.'toggle_buttons.js'; ?>

		/* COMMISSION TYPES QUICK ACTIONS */
		<?php require_once __DIR__.DIRECTORY_SEPARATOR.'commission_types_quick_actions.js'; ?>

		/* COMMISSION TYPES REARRANGER */
		<?php require_once __DIR__.DIRECTORY_SEPARATOR.'commission_types_reorder.js'; ?>

		/* ARTIST PAGE LINK PREVIEW */
		$(document).on("input", "#create-artist-page-form-input-url, #edit-artist-page-form-input-url", function() {
			$("#artist-page-url-sample").text($("#artist-page-url-sample").attr("data-base")+($(this).val() != "" ? $(this).val()+"/" : ""));
		});

		/* ARTIST TOS PICKER */
		$(document).on("change", "#terms-of-service-picker", function() {
			$(".artist-tos").addClass("hide");
			$("#"+$("#terms-of-service-picker").val()).removeClass("hide");
		});

		/* SOCIAL MEDIA */
		<?php require_once __DIR__.DIRECTORY_SEPARATOR.'social_media.js'; ?>

		/* NEWS */
		$(document).on("click", "#hide-news-button", function() {
			window.log(<?= json_encode(basename(__FILE__)) ?>, ".on click #hide-new-button - news will be hidden, cookie set");
			var now = new Date();
			var time = now.getTime();
			time += 1000 * 60 * 60 * 24 * 365; // a year
			now.setTime(time);
			document.cookie="last_news="+$("#hide-news-button").attr("data-cookie-val")+"; expires="+(now.toGMTString())+"; path=/";
			$("#hide-news-button").parent().parent().remove();
		});

		/* FORM SUBMISSION KEYS */
		$(document).on("keydown", function(event) {
			if (event.which === 8 && $("form").length != 0 && $(event.target).parents("form").length == 0) {
				window.log(<?= json_encode(basename(__FILE__)) ?>, ".on keydown - recieved a backspace event, however there are (unfocused) forms on the page.  Suppressing...");
				event.preventDefault();
			}
		});
		$(document).on("keydown", "textarea", function(e) {
			if ((e.keyCode == 10 || e.keyCode == 13) && (e.ctrlKey || e.metaKey)) {
				window.log(<?= json_encode(basename(__FILE__)) ?>, ".on keydown in textarea - ctrl+enter recieved, attempting form submission");
				$(document.activeElement.form).submit();
			}
		});

		/* ENCRPYTION */
		<?php require_once __DIR__.DIRECTORY_SEPARATOR.'encryption.js'; ?>

		/* COMMISSION TYPE ACTIONS */
		<?php require_once __DIR__.DIRECTORY_SEPARATOR.'commission_type_client_actions.js'; ?>

		/* IMAGE COMPRESSION FALLBACKS */
		<?php require_once __DIR__.DIRECTORY_SEPARATOR.'webp_background_image_fallback.js'; ?>

		/* ABOUT PAGE INTERACTIVE TERMS */
		<?php require_once __DIR__.DIRECTORY_SEPARATOR.'about_page_interactive_terms.js'; ?>

		if (window.devMode) {
			$(document).on("click", ".brand-logo", function(e) {
				e.preventDefault();

				$("head").append($("<link></link>").attr("rel", "stylesheet").attr("href", $("html").attr("data-rootdir")+"css/color-"+prompt("Input color: ")+".css?"+Date.now()));

				return false;
			});
		}
	});
})(jQuery);

window.addEventListener('load', () => {
	if (window.devMode) {
		console.time("Webcomponent form definitions");
	}

	if (!window.hasOwnProperty("formInputHandlers")) {
		window.formInputHandlers = {};
	}

	var toRegister = {
		"form-label-helper-span": FormLabelHelperSpan,
		"form-label": FormLabel,

		"select-field": SelectField,
		"text-field": TextField,
		"password-field": PasswordField,
		"email-field": EmailField,
		"confirm-password-field": ConfirmPasswordField,
		"captcha-field": CaptchaField,
		"hidden-input-field": HiddenInputField,
		"markdown-field": MarkdownField,
		"number-field": NumberField,
		"static-html-field": StaticHTMLField,

		"placeholder-confirm-field": window.formInputHandlers["Catalyst\\Form\\Field\\ConfirmField"]
	};

	for (var element in toRegister) {
		if (window.devMode) {
			console.time("Webcomponent registration of "+element);
		}

		window.log("Form component registration", "Registering "+element);

		window.formInputHandlers["Catalyst\\Form\\Field\\"+toRegister[element].prototype.constructor.name] = toRegister[element];

		window.customElements.define(element, toRegister[element]);

		if (window.devMode) {
			console.timeEnd("Webcomponent registration of "+element);
		}
	}

	if (window.devMode) {
		console.timeEnd("Webcomponent form definitions");
	}
}, {passive: true});

<?php if (Controller::isDevelMode()): ?>
	<?php
	$overallTime = microtime(true)-EXEC_START_TIME;
	$dbDuration = Database::getTotalQueryTime();
	?>
	console.log("''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''");
	console.log("''Main JS generation debug: ''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''");
	console.log("''''''Overall: <?= $overallTime ?>s <?= str_repeat("'", 83-strlen($overallTime)) ?>");
	console.log("''''''Database queries: <?= AbstractQuery::getTotalQueries() ?> <?= str_repeat("'", 75-strlen(AbstractQuery::getTotalQueries())) ?>");
	console.log("''''''Database queries time usage: <?= $dbDuration ?>s <?= str_repeat("'", 63-strlen($dbDuration)) ?>");
	console.log("''''''Memory: <?= UniversalFunctions::humanize(memory_get_peak_usage()) ?>s <?= str_repeat("'", 84-strlen(UniversalFunctions::humanize(memory_get_peak_usage()))) ?>");
	console.log("''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''''");
<?php endif; ?>
