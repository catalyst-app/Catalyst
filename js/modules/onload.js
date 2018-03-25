<?php
header("Content-Type: application/javascript; charset=UTF-8");

define("ROOTDIR", "../../");
define("REAL_ROOTDIR", "../../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Form\FormRepository;
use \Catalyst\API\ErrorCodes;

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
// Impossible!
//
// (yes spade im looking at you)

/* GENERAL FUNCTIONS */
var humanFileSize = function(size) {
	var i = Math.floor( Math.log(size) / Math.log(1024) );
	return ( size / Math.pow(1024, i) ).toFixed(2) * 1 + ' ' + ['B', 'KB', 'MB', 'GB', 'TB'][i];
};


(function($){
	$(function(){
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

		/* MARKDOWN */
		$(document).on("input", ".markdown-field", function() {
			$(".markdown-target[data-field="+$(this).attr("id")+"]").removeClass("rendered-markdown").addClass("raw-markdown").text($(this).val());
			renderMarkdownArea($(".markdown-target[data-field="+$(this).attr("id")+"]"));
		});

		$(document).on("click", ".markdown-rendered-checkbox", function(e) { e.preventDefault(); if (e.stopPropogation()) { e.stopPropogation(); } return false; });

		/* EASTER EGGS */
		try {
			cheet('b u l g e', function () {
				$(".brand-logo").html("OwO what's this?");
			});
		} catch(e) {} // cheet doesn't like to be defined, and its not critical

		/* FORMS */
		$(document).on("input", ".marked-invalid", function(e) {
			$(this).removeClass("invalid").removeClass("invalid");
		});
		<?php foreach ($forms as $form): ?>
			<?= $form->getAllJs(); ?>
		<?php endforeach; ?>

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
				event.preventDefault();
			}
		});
		$(document).on("keydown", "textarea", function(e) {
			if ((e.keyCode == 10 || e.keyCode == 13) && e.ctrlKey) {
				$(document.activeElement.form).submit();
			}
		});

		// toish: "Put it in a closure":tm:
		function materializeOnload() {
			if ($ === undefined || Materialize === undefined) {
				console.log("Deferring materialize onload for 100ms (even though I'm not happy about it...)");
				setTimeout(materializeOnload, 100);
				return;
			}
			$(".button-collapse").sideNav();
			$(".modal").modal();
			$('select').attr("required", false).material_select();
			$('.pushpin').pushpin({
				top: 200,
				offset: 72
			});
			$('.psuedo-required, .psuedo-required input').attr("required", false);
			$(".raw-markdown").each(function() {renderMarkdownArea(this);});
			$(".raw-emoji").each(function() {$(this).html(twemoji.parse($(this).html())).removeClass("raw-emoji");});
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
			$('textarea').trigger('autoresize');
		}

		materializeOnload();
	});
})(jQuery);
