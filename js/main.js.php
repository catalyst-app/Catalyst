<?php
header("Content-Type: application/javascript; charset=UTF-8");

define("ROOTDIR", "../");
define("REAL_ROOTDIR", "../");

require_once REAL_ROOTDIR."includes/Controller.php";
use \Catalyst\Form\FormRepository;
use \Catalyst\API\ErrorCodes;

$forms = FormRepository::getAllForms();

$errors = ErrorCodes::getAssoc();


use \Catalyst\Color;
use \Catalyst\Database\Artist\EditArtist;
use \Catalyst\Database\Artist\NewArtist;
// the following series of whitespace is dedicated to SINNERSCOUT for being cool and a patron and stuff:
/*
U+0020	SPACE	foo bar, size: depends on font, typically 1/4 em, often adjusted
U+00A0	NO-BREAK SPACE	foo bar, size: as a space, but often not adjusted
U+1680	OGHAM SPACE MARK	foo bar, size: unspecified; usually not really a space but a dash
U+180E	MONGOLIAN VOWEL SEPARATOR	foo᠎bar, size: no width
U+2000	EN QUAD	foo bar, size: 1 en (= 1/2 em)
U+2001	EM QUAD	foo bar, size: 1 em (nominally, the height of the font)
U+2002	EN SPACE	foo bar, size: 1 en (= 1/2 em)
U+2003	EM SPACE	foo bar, size: 1 em
U+2004	THREE-PER-EM SPACE	foo bar, size: 1/3 em
U+2005	FOUR-PER-EM SPACE	foo bar, size: 1/4 em
U+2006	SIX-PER-EM SPACE	foo bar, size: 1/6 em
U+2007	FIGURE SPACE	foo bar, size: “tabular width”, the width of digits
U+2008	PUNCTUATION SPACE	foo bar, size: the width of a period “.”
U+2009	THIN SPACE	foo bar, size: 1/5 em (or sometimes 1/6 em)
U+200A	HAIR SPACE	foo bar, size: narrower than thin space
U+200B	ZERO WIDTH SPACE	foo​bar, size: nominally no width, but may expand
U+202F	NARROW NO-BREAK SPACE	foo bar, size: narrower than no-break space (or space)
U+205F	MEDIUM MATHEMATICAL SPACE	foo bar, size: 4/18 em
U+3000	IDEOGRAPHIC SPACE	foo　bar, size: the width of ideographic (cjk) characters.
U+FEFF	ZERO WIDTH NO-BREAK SPACE	foo﻿bar, size: no width (the character is invisible)
*/
use \Catalyst\Database\CommissionType\EditCommissionType;
use \Catalyst\Database\CommissionType\NewCommissionType;
use \Catalyst\Database\FeatureBoard\Comment;
use \Catalyst\Database\FeatureBoard\NewFeature;
use \Catalyst\Form\FormJS;

use \Catalyst\Form\Field\MultipleImageWithNsfwCaptionAndInfoField;

?>
// ~))))'> 
//
// What's this, a possum in the JavaScript?
// Impossible!
//
// (yes spade im looking at you)

// https://tc39.github.io/ecma262/#sec-array.prototype.includes
if (!Array.prototype.includes) {
  Object.defineProperty(Array.prototype, 'includes', {
	value: function(searchElement, fromIndex) {

	  if (this == null) {
		throw new TypeError('"this" is null or not defined');
	  }

	  // 1. Let O be ? ToObject(this value).
	  var o = Object(this);

	  // 2. Let len be ? ToLength(? Get(O, "length")).
	  var len = o.length >>> 0;

	  // 3. If len is 0, return false.
	  if (len === 0) {
		return false;
	  }

	  // 4. Let n be ? ToInteger(fromIndex).
	  //	(If fromIndex is undefined, this step produces the value 0.)
	  var n = fromIndex | 0;

	  // 5. If n ≥ 0, then
	  //  a. Let k be n.
	  // 6. Else n < 0,
	  //  a. Let k be len + n.
	  //  b. If k < 0, let k be 0.
	  var k = Math.max(n >= 0 ? n : len - Math.abs(n), 0);

	  function sameValueZero(x, y) {
		return x === y || (typeof x === 'number' && typeof y === 'number' && isNaN(x) && isNaN(y));
	  }

	  // 7. Repeat, while k < len
	  while (k < len) {
		// a. Let elementK be the result of ? Get(O, ! ToString(k)).
		// b. If SameValueZero(searchElement, elementK) is true, return true.
		if (sameValueZero(o[k], searchElement)) {
		  return true;
		}
		// c. Increase k by 1. 
		k++;
	  }

	  // 8. Return false
	  return false;
	}
  });
}

window.onerror = function(message, url, lineNumber) {  
	var data = new FormData();
	data.append("message", message);
	data.append("url", url);
	data.append("lineNumber", lineNumber);
	$.ajax($("html").attr("data-rootdir")+"api/internal/js_error/", {
		data: data,
		processData: false,
		contentType: false,
		method: "POST"
	}).done(function(response) {
		Materialize.toast("An error occured", 4000);
	}).fail(function(response) {
		Materialize.toast("An error occured", 4000);
	});
	return false;
};

/* GENERAL FUNCTIONS */
var markInputInvalid = function(e, a) {
	if ($(e).hasClass("g-recaptcha")) {
		markCaptchaInvalid(a);
	} else {
		$(e).addClass("invalid").addClass("marked-invalid").focus();
		$("label[for="+$(e).attr("id")+"]").attr("data-error", a);
		$("label[for="+$(e).attr("id")+"]").addClass("active");
	}
};
var markCaptchaInvalid = function(a) {
	$(".g-recaptcha").attr("data-error", a).addClass("invalid").removeClass("valid");
	grecaptcha.reset();
};
var markCaptchaValid = function() {
	$(".g-recaptcha").addClass("valid").removeClass("invalid");
};
var showErrorMessageForCode = function(c) {
	switch (c) {
<?php foreach ($errors as $code => $message): ?>
		case <?= $code ?>:
			Materialize.toast(<?= json_encode($message) ?>, 4000);
			break;
<?php endforeach; ?>
		default:
			Materialize.toast("An unknown error occured", 4000);
	}
};
// deprecated
var uploadIndicator = function(f, e) {
	$("#"+f+"-progress-wrapper .indeterminate").removeClass("indeterminate").addClass("determinate");
	$("#"+f+"-progress-wrapper .determinate").css("width", ((e.loaded*100)/e.total)+"%");
	if (e.loaded == e.total) {
		$("#"+f+"-progress-wrapper .determinate").addClass("indeterminate").removeClass("determinate").attr("style", "");
	}
};
var updateUploadIndicator = function(f, e) {
	$(f+" .indeterminate").removeClass("indeterminate").addClass("determinate");
	$(f+" .determinate").css("width", ((e.loaded*100)/e.total)+"%");
	if (e.loaded == e.total) {
		$(f+" .determinate").addClass("indeterminate").removeClass("determinate").attr("style", "");
	}
};
var rgb2hex = function(rgb){
	var rgb = rgb.match(/(\d+)/g);
	return (dec2twoDigitHex(rgb[0])+dec2twoDigitHex(rgb[1])+dec2twoDigitHex(rgb[2])).toLowerCase();
};
var dec2twoDigitHex = function(c) {
	var out = parseInt(c,10).toString(16);
	if (out.length == 1) {
		return "0"+out;
	}
	return out;
};

/* MARKDOWN */
var renderMarkdownArea = function(area) {
	$(area).html(md.render($(area).html())).removeClass('raw-markdown').addClass('rendered-markdown');
	$(area).find('.collapsible').collapsible();
};

/* IMAGE ARRANGEMENT */
var getCardState = function() {
	var columns = $(".edit-cards > div.col:visible");
	var maxCards = $(columns[0]).find(".card").length;

	var cards = [];
	cardAggregator:
	for (var i = 0; i < maxCards; i++) {
		for (var j = 0; j < columns.length; j++) {
			var jQitem = $(columns[j]).find(".card:eq("+i+")");
			if (jQitem.length == 0) {
				break cardAggregator;
			} else {
				cards.push(jQitem[0]);
			}
		}
	}

	return cards;
};

/* TOTP */
function totp(K,t) {
  function sha1(C){
	function L(x,b){return x<<b|x>>>32-b;}
	var l=C.length,D=C.concat([1<<31]),V=0x67452301,W=0x88888888,
		Y=271733878,X=Y^W,Z=0xC3D2E1F0;W^=V;
	do D.push(0);while(D.length+1&15);D.push(32*l);
	while (D.length){
	  var E=D.splice(0,16),a=V,b=W,c=X,d=Y,e=Z,f,k,i=12;
	  function I(x){var t=L(a,5)+f+e+k+E[x];e=d;d=c;c=L(b,30);b=a;a=t;}
	  for(;++i<77;)E.push(L(E[i]^E[i-5]^E[i-11]^E[i-13],1));
	  k=0x5A827999;for(i=0;i<20;I(i++))f=b&c|~b&d;
	  k=0x6ED9EBA1;for(;i<40;I(i++))f=b^c^d;
	  k=0x8F1BBCDC;for(;i<60;I(i++))f=b&c|b&d|c&d;
	  k=0xCA62C1D6;for(;i<80;I(i++))f=b^c^d;
	  V+=a;W+=b;X+=c;Y+=d;Z+=e;}
	return[V,W,X,Y,Z];
  }
  var k=[],l=[],i=0,j=0,c=0;
  for (;i<K.length;){
	c=c*32+'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567'.
	  indexOf(K.charAt(i++).toUpperCase());
	if((j+=5)>31)k.push(Math.floor(c/(1<<(j-=32)))),c&=31;}
  j&&k.push(c<<(32-j));
  for(i=0;i<16;++i)l.push(0x6A6A6A6A^(k[i]=k[i]^0x5C5C5C5C));
  var s=sha1(k.concat(sha1(l.concat([0,t])))),o=s[4]&0xF;
  var out=""+(((s[o>>2]<<8*(o&3)|(o&3?s[(o>>2)+1]>>>8*(4-o&3):0))&-1>>>1)%1000000);
  while ((out).length < 6) {
  	out = "0"+out;
  }
  return out;
}

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
		} catch(e) {} // cheet doesn't like to be defined

		/* FORMS */
		$(document).on("input", ".marked-invalid", function(e) {
			$(this).removeClass("invalid").removeClass("invalid");
		});
<?php foreach ($forms as $form): ?>
	<?= $form->getAllJs(); ?>
<?php endforeach; ?>

		/* IMAGE UPLOADING WITH NSFW, CAPTIONS, and INFO */
		$(document).on("change", "input[type=file].<?= MultipleImageWithNsfwCaptionAndInfoField::INPUT_CLASS ?>", function(e) {
			var existingRows = [];
			var inputRows = [];
			var inputRowsFullId = [];
			
			for (var i = 0; i < $(this)[0].files.length; i++) {
				var file = $(this)[0].files[i];
				inputRows.push(""<?= MultipleImageWithNsfwCaptionAndInfoField::EL_ID_SUFFIX_EXPR ?>);
				inputRowsFullId.push($(this).attr("data-extra-info-prefix")+<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::ROW_ID_SUFFIX) ?><?= MultipleImageWithNsfwCaptionAndInfoField::EL_ID_SUFFIX_EXPR ?>);
			}

			for (var i = 0; i < $(<?= json_encode(".".MultipleImageWithNsfwCaptionAndInfoField::ROW_CLASS) ?>+'[data-input='+$(this).attr("id")+']').length; i++) {
				existingRows.push($($(<?= json_encode(".".MultipleImageWithNsfwCaptionAndInfoField::ROW_CLASS) ?>+'[data-input='+$(this).attr("id")+']')[i]).attr("id"));
			}

			var toRemove = [];
			var toAdd = [];
			
			for (var i = 0; i < existingRows.length; i++) {
				if (!(inputRowsFullId.includes(existingRows[i]))) {
					toRemove.push(existingRows[i]);
				}
			}
			for (var i = 0; i < inputRowsFullId.length; i++) {
				if (!(existingRows.includes(inputRowsFullId[i]))) {
					toAdd.push([inputRows[i], inputRowsFullId[i]]);
				}
			}

			console.table({
				existingRows: existingRows,
				inputRows: inputRows,
				inputRowsFullId: inputRowsFullId,
				toAdd: toAdd,
				toRemove: toRemove
			});

			for (var i = toRemove.length - 1; i >= 0; i--) {
				$("#"+toRemove[i]).remove();
			}

			for (var i = toAdd.length - 1; i >= 0; i--) {
				var row = $("<div></div>");
				row.addClass(<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::ROW_CLASS) ?>);
				row.attr("id", toAdd[i][1]);
				row.attr("data-input", $(this).attr("id"));

				$("#"+$(this).attr("data-extra-info-prefix")+<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::ROW_CONTAINER_ID_SUFFIX) ?>).append(row);
			}
		});

		/* IMAGE ARRANGEMENT */
		$(document).on("click", ".edit-cards .make-primary-button", function() {
			$(".edit-cards .primary-input").val("false");
			$(".edit-cards .make-primary-button").attr("disabled", false);
			$(this).parent().parent().find(".primary-input").val("true");
			$(this).parent().parent().find(".make-primary-button").attr("disabled", "disabled");
		});

		$(document).on("click", ".edit-cards .delete-button", function() {
			if ($(this).hasClass("confirm")) {
				if ($(".edit-cards .card:visible").length == 1) {
					$(this).parent().parent().parent().parent().remove();
					return;
				}
				var oldEl;
				if ($(".edit-cards div.hide-on-large-only:visible").length == 0) {
					switch ($(".edit-cards .card:visible").length % 3) {
						case 0:
							oldEl = $(".edit-cards > div.col.l4:eq(2) .card:last");
							break;
						case 1:
							oldEl = $(".edit-cards > div.col.l4:eq(0) .card:last");
							break;
						case 2:
							oldEl = $(".edit-cards > div.col.l4:eq(1) .card:last");
							break;
					}
				} else {
					var oldEl;
					switch ($(".edit-cards .card:visible").length % 2) {
						case 0:
							oldEl = $(".edit-cards > div.col.m6:eq(1) .card:last");
							break;
						case 1:
							oldEl = $(".edit-cards > div.col.m6:eq(0) .card:last");
							break;
					}
				}
				$(this).parent().parent().parent().parent().addClass("old-card-to-be-removed");
				$($(this).parent().parent().parent().parent()).swapWith($(oldEl));
				$(".old-card-to-be-removed").remove();
			} else {
				$(this).addClass("confirm").text("confirm");
			}
		});

		/* FILE PICKER */
		$(document).on("click", ".file-input-field, .file-input-field *", function(e) {
			$(this).find("input[type=file]").focus().trigger("click");
			if (e.stopPropogation) e.stopPropogation();
			if (e.stopImmediatePropagation) e.stopImmediatePropagation();
		});
		$(document).on('change', '.file-input-field .file-input-path', function () {
			if ($(this).val().length == 0 && $(this).attr("data-required") == "yes") {
				$(this).addClass('invalid').removeClass('valid');
			} else if ($(this).val().length == 0) {
				$(this).removeClass('valid');
			} else {
				$(this).addClass('valid').removeClass('invalid');
			}
		});
		$(document).on('change', '.file-input-field input[type="file"]', function () {
			var file_field = $(this).closest('.file-input-field');
			var path_input = file_field.find('input.file-input-path');
			var files = $(this)[0].files;
			var file_names = [];
			for (var i = 0; i < files.length; i++) {
				file_names.push(files[i].name);
			}
			path_input.val(file_names.join(", "));
			path_input.trigger('change');
		});

		/* COLOR PICKER */
		var initializeColorPicker = function() {
			var colors = <?= json_encode(Color::COLOR_BY_HEX) ?>;

			$(".color-swatch").hide();

			var swatches = $(".color-swatch");

			for (var i = 0; i < Object.keys(colors).length; i++) {
				$(swatches[i]).data("sub-colors", colors[Object.keys(colors)[i]]).show().css({
					backgroundColor: "#"+Object.keys(colors)[i]
				}, 100);
			}
		}

		$(document).on("click", ".color-field", function(e) {
			$(".color-picker-modal").attr("data-for", $(this).attr("data-for")).modal("open");
			initializeColorPicker();
		});

		$(document).on("click", ".color-swatch", function(e) {
			var swatches = $(".color-swatch");

			colors = $(this).data("sub-colors");

			$(".color-swatch").hide().data("sub-colors", null);

			$("input#"+$(this).closest(".modal").attr("data-for")).val(rgb2hex($(this).css("backgroundColor")));
			$(".chosen-color[data-for="+$(this).closest(".modal").attr("data-for")+"]").css({
				backgroundColor: $(this).css("backgroundColor")
			}, 200);

			if (Array.isArray(colors)) {
				for (var i = 0; i < colors.length; i++) {
					$(swatches[i]).show().css({
						backgroundColor: "#"+colors[i]
					}, 200);
				}
			} else {
				$(".color-picker-modal").modal("close");
			}
		});

		/* NEW ARTIST PAGE */
		<?php
		echo FormJS::generateFormHandler(NewArtist::getFormStructure());
		?>
		$(document).on("input", "input#newartist-url", function() {
			$("#newartist-url-sample").text($("#newartist-url-sample").attr("data-base")+($(this).val() != "" ? $(this).val()+"/" : ""));
		});

		/* EDIT ARTIST PAGE */
		<?php
		echo FormJS::generateFormHandler(EditArtist::getFormStructure());
		?>
		$(document).on("input", "input#editartist-url", function() {
			$("#editartist-url-sample").text($("#editartist-url-sample").attr("data-base")+($(this).val() != "" ? $(this).val()+"/" : ""));
		});

		/* DELETE ARTIST PAGE */
		$(document).on("click", ".confirm-artist-page-deletion-btn", function() {
			$.ajax("delete.php", {
				data: new FormData(), // no data needed
				processData: false,
				contentType: false,
				method: "POST"
			}).done(function(response) {
				Materialize.toast("Deleted", 4000);
				window.location = $("html").attr("data-rootdir");
			}).fail(function(response) {
				alert("Unknown error.");
				window.location="";
			});
		});

		/* DELETE CHARACTER */
		$(document).on("click", ".confirm-character-deletion-btn", function() {
			var data = new FormData();
			data.append("token", $(".token-input").val());

			$.ajax($("html").attr("data-rootdir")+"Character/delete.php", {
				data: data,
				processData: false,
				contentType: false,
				method: "POST"
			}).done(function(response) {
				Materialize.toast("Saved", 4000);
				window.location = $("html").attr("data-rootdir")+"Character/";
			}).fail(function(response) {
				alert("Unknown error.");
				window.location="";
			});
		});

		/* ADD SOCIAL MEDIA */
		var addSocialMediaChip = function(data) {
			$(".modal").modal("close");
			$(".social-chips > div").append($(data.html).html());
		};

		/* REMOVE SOCIAL MEDIA */
		$(document).on("click", ".social-chips .chip i", function(e) {
			var id = $(this).parent().attr("data-id");
			try {
				$(this).parent().tooltip('remove');
				$(this).parent().velocity("finish").velocity("stop");
				if ($(this).parent().parent()[0].tagName.toLowerCase() == "a") {
					$(this).parent().parent().fadeOut(400, function() {
						$(this).remove();
					});
				} else {
					$(this).parent().fadeOut(400, function() {
						$(this).remove();
					});
				}
			} catch (e) {}

			var data = new FormData();
			data.append("id", id);
			data.append("rootdir", $("html").attr("data-rootdir"));
			if ($("#social-dest-type").length !== 1) {
				data.append("dest", "");
			} else {
				data.append("dest", $("#social-dest-type").val());
			}

			$.ajax($("html").attr("data-rootdir") + "api\/internal\/social_media\/delete\/", {
				data: data,
				processData: false,
				contentType: false,
				method: "POST"
			}).done(function(response) {
				console.log(response);
				var data = JSON.parse(response);
				Materialize.toast("Removed", 4000);
			}).fail(function(response) {
				console.log(response);
				var data = JSON.parse(response.responseText);
				showErrorMessageForCode(data.error_code);
			});

			if (e.stopPropogation) e.stopPropogation();
			if (e.stopImmediatePropagation) e.stopImmediatePropagation();
			e.preventDefault();
			return false;
		});

		/* MOVE SOCIAL MEDIA */
		try {
			new Draggable.Sortable(document.querySelector('.social-chips-editable.social-chips > div'), {
				draggable: '.social-chips-editable.social-chips > div > a, .social-chips-editable.social-chips > div > .chip',
				appendTo: '.social-chips-editable.social-chips'
			}).on("sortable:stop", function() {
				setTimeout(function() {
					var result = [];
					for (var i = 0; i < $(".social-chips .chip:not(.draggable-mirror,.draggable-source--is-dragging)").length; i++) {
						result.push($($(".social-chips .chip:not(.draggable-mirror,.draggable-source--is-dragging)")[i]).attr("data-id"));
					}
					
					var data = new FormData();
					data.append("rootdir", $("html").attr("data-rootdir"));
					if ($("#social-dest-type").length !== 1) {
						data.append("dest", "");
					} else {
						data.append("dest", $("#social-dest-type").val());
					}
					data.append("order", JSON.stringify(result));

					$.ajax($("html").attr("data-rootdir") + "api\/internal\/social_media\/order\/", {
						data: data,
						processData: false,
						contentType: false,
						method: "POST"
					}).done(function(response) {
						console.log(response);
						var data = JSON.parse(response);
						Materialize.toast("Saved", 4000);
					}).fail(function(response) {
						console.log(response);
						var data = JSON.parse(response.responseText);
						showErrorMessageForCode(data.error_code);
					});
				}, 100);
			});
		} catch(e) {}

		/* EDIT COMMISSION TYPES */
		<?php
		echo FormJS::generateFormHandler(EditCommissionType::getFormStructure());
		?> 

		/* EDIT COMMISSION TYPE IMAGES */
		try {
			new Draggable.Swappable($(".edit-cards.commission-type-images")[0], {
				draggable: '.commission-type-images .card',
				handle: '.card-image'
			});
		} catch(e) {}

		$(document).on("click", "#editctimg-btn", function() {
			var cards = getCardState().map(function(e) {
				return [
					$(e).find("input[type=hidden].path-input").val(),
					$(e).find("input.caption-input").val().substring(0, 255),
					$(e).find("input.nsfw-input").is(":checked"),
					($(e).find("input.primary-input").val() == "true" ? true : false)
				];
			});

			var data = new FormData();
			data.append("body", JSON.stringify(cards));
			data.append("token", $(".token-input").val());

			$.ajax($("html").attr("data-rootdir")+"Artist/EditCommissionTypeImages/handler.php", {
				data: data,
				processData: false,
				contentType: false,
				method: "POST"
			}).done(function(response) {
				Materialize.toast("Saved", 4000);
				window.location = $("html").attr("data-rootdir")+"Artist/EditCommissionTypes/";
			}).fail(function(response) {
				alert("Unknown error.");
				window.location="";
			});
		}); 

		/* NEW COMMISSION TYPES */
		<?php
		echo FormJS::generateFormHandler(NewCommissionType::getFormStructure());
		?> 
		$(document).on("click", '#add-commission-type-mod-container-btn', function(e) {
			var d = Date.now();
			$("#modifier-container-container").append(
				$('<div></div>').addClass("modifier-container").append(
					$('<div></div>').addClass("right").addClass("right-align").append(
						$("<i></i>").addClass("material-icons").addClass("small").addClass("remove-modifier-container")
							.text("clear")
					).append(
						$("<p></p>").addClass("no-top-margin").append(
							$("<input>").addClass("filled-in").attr("type", "checkbox").attr("id", d)
						).append(
							$("<label></label>").attr("for", d).text("Multiple")
						)
					)
				)
			);
			$("#modifier-container-container").append(
				$('<div></div>').addClass("divider").addClass("col").addClass("s12")
			);
		});
		$(document).on("click", '.remove-modifier-container', function(e) {
			$(this).parent().parent().next().remove();
			$(this).parent().parent().remove();
			var d = Date.now();
			if ($('.modifier-container').length == 0) {
				$("#modifier-container-container").append(
					$('<div></div>').addClass("modifier-container").append(
						$('<div></div>').addClass("right").addClass("right-align").append(
							$("<i></i>").addClass("material-icons").addClass("small").addClass("remove-modifier-container")
								.text("clear")
						).append(
							$("<p></p>").addClass("no-top-margin").append(
								$("<input>").addClass("filled-in").attr("type", "checkbox").attr("id", d)
							).append(
								$("<label></label>").attr("for", d).text("Multiple")
							)
						)
					)
				);
				$("#modifier-container-container").append(
					$('<div></div>').addClass("divider").addClass("col").addClass("s12")
				);
			}
		});
		$(document).on("click", '.remove-modifier', function(e) {
			$(this).parent().remove();
		});
		$(document).on("click", '#new-commission-type-add-modifier-btn', function(e) {
			if (!$("#add-commission-type-mod-name").val().match(/^.{2,60}$/)) {
				markInputInvalid($("#add-commission-type-mod-name"));
				return;
			}
			if (!$("#add-commission-type-mod-cost").val().match(/^.{2,64}$/)) {
				markInputInvalid($("#add-commission-type-mod-cost"));
				return;
			}
			if (!$("#add-commission-type-mod-usd-cost").val().match(/^\d{1,4}(|\.\d{1,2})$/)) {
				markInputInvalid($("#add-commission-type-mod-usd-cost"));
				return;
			}

			$(".modifier-container:last").append(
				$("<div></div>").addClass("btn").addClass("commission-type-mod").text($("#add-commission-type-mod-name").val()+" (+"+$("#add-commission-type-mod-cost").val()+")").attr("data-name", $("#add-commission-type-mod-name").val()).attr("data-cost", $("#add-commission-type-mod-cost").val()).attr("data-cost-usd", $("#add-commission-type-mod-usd-cost").val()).append(
					$("<i></i>").addClass("material-icons").addClass("remove-modifier").addClass("right").text("clear")
				)
			);
			$("#add-commission-type-mod-name").val('');
			$("#add-commission-type-mod-cost").val('');
			$("#add-commission-type-mod-usd-cost").val('');
		});
		try {
			new Draggable.Droppable(document.getElementById('modifier-container-container'), {
				draggable: '.btn',
				droppable: '.modifier-container'
			}).on("droppable:over", function() {
				$(".modifier-container").removeClass("draggable-droppable--occupied");
			});
		} catch(e) {}
		$(document).on("click", ".toggle-btn", function(e) {
			if ($(this).hasClass("off")) {
				$(this).removeClass("off").addClass("on");
			} else {
				$(this).removeClass("on").addClass("off");
			}
		});
		$(document).on("click", '#add-commission-type-payment-type-btn', function(e) {
			if (!$("#add-commission-type-payment-type").val().match(/^.{2,64}$/)) {
				markInputInvalid($("#add-commission-type-payment-type"));
				return;
			}
			if (!$("#add-commission-type-payment-type-addr").val().match(/^.{2,64}$/)) {
				markInputInvalid($("#add-commission-type-payment-type-addr"));
				return;
			}
			if (!$("#add-commission-type-payment-type-instructions").val().match(/^.+$/)) {
				markInputInvalid($("#add-commission-type-payment-type-instructions"));
				return;
			}

			$("#payment-type-container").append(
				$("<div></div>").addClass("col").addClass("s12").addClass("payment-type").attr("data-type", $("#add-commission-type-payment-type").val()).attr("data-addr", $("#add-commission-type-payment-type-addr").val()).attr("data-instructions", $("#add-commission-type-payment-type-instructions").val()).append(
					$("<div></div>").addClass("left").append(
						$("<p></p>").addClass("no-margin").text($("#add-commission-type-payment-type-addr").val()).prepend(
							$("<strong></strong>").text($("#add-commission-type-payment-type").val()+": ")
						)
					).append(
						$("<p></p>").addClass("no-top-margin").text($("#add-commission-type-payment-type-instructions").val())
					)
				).append(
					$("<i></i>").addClass("material-icons").addClass("small").addClass("right").addClass("remove-payment-type")
						.text("clear")
				)
			);
			$("#add-commission-type-payment-type").val('');
			$("#add-commission-type-payment-type-addr").val('');
			$("#add-commission-type-payment-type-instructions").val('');
		});
		$(document).on("click", ".remove-payment-type", function(e) {
			$(this).parent().remove();
		});
		$(document).on("click", '#add-commission-type-stage-btn', function(e) {
			if (!$("#add-commission-type-stage").val().match(/^.{2,255}$/)) {
				markInputInvalid($("#add-commission-type-stage"));
				return;
			}

			$("#stage-container").append(
				$("<div></div>").addClass("col").addClass("s12").addClass("stage").attr("data-stage", $("#add-commission-type-stage").val()).append(
					$("<div></div>").addClass("left").append(
						$("<p></p>").addClass("no-margin").text($("#add-commission-type-stage").val())
					)
				).append(
					$("<i></i>").addClass("material-icons").addClass("small").addClass("right").addClass("remove-stage")
						.text("clear")
				)
			);
			$("#add-commission-type-stage").val('');
		});
		$(document).on("click", ".remove-stage", function(e) {
			$(this).parent().remove();
		});
		try {
			new Draggable.Sortable(document.getElementById('stage-container'), {
				draggable: '.stage',
				appendTo: '#stage-container',
				handle: 'p'
			});
		} catch(e) {}
		$(document).on("click", ".attr-invert-btn", function(e) {
			e.preventDefault();
			$($(this).parent().next().children()).each(function(i, a) {
				if ($(a).hasClass("off")) {
					$(a).addClass("on").removeClass("off");
				} else {
					$(a).addClass("off").removeClass("on");
				}
			});
		});

		/* DELETE COMMISSION TYPES */
		$(document).on("click", ".delete-commission-type-button", function(e) {
			e.preventDefault();
			if ($(this).text() == "Are you sure?") {
				var data = new FormData();
				data.append("id", $(this).parent().parent().parent().attr("data-id"));
				var el = this;
				$.ajax("delete.php", {
					data: data,
					processData: false,
					contentType: false,
					method: "POST"
				}).done(function(response) {
					Materialize.toast("Deleted", 4000);
					$(el).parent().parent().parent().remove();
				}).fail(function(response) {
					alert("Unknown error.");
					window.location="";
				});
			} else {
				$(this).text("Are you sure?");
			}
		});

		/* ORDER COMMISSION TYPES */
		try {
			new Draggable.Sortable(document.getElementsByClassName('commission-types-rearrangeable')[0], {
				draggable: '.commission-types-rearrangeable > div:not(.frozen)',
				appendTo: '.commission-types-rearrangeable',
				handle: '.commission-type-handle, .img-strict-circle'
			});
		} catch(e) {}
		$(document).on("click", "#save-commission-type-order", function() {
			var result = [];
			for (var i = 0; i < $(".commission-type:not(.new-commission-type)").length; i++) {
				result.push($($(".commission-type:not(.new-commission-type)")[i]).attr("data-id"));
			}
			
			var data = new FormData();
			data.append("order", JSON.stringify(result));

			$.ajax("handler.php", {
				data: data,
				processData: false,
				contentType: false,
				method: "POST"
			}).done(function(response) {
				Materialize.toast("Saved", 4000);
				window.location = '../Dashboard/';
			}).fail(function(response) {
				alert("Unknown error.");
				window.location="";
			});
		});

		/* SHOW COMMISSION TYPES */
		$(document).on("click", ".commission-type-collapsible-header", function(e) {
			if ($(this).parent().find(".commission-type-collapsible-body").hasClass("collapsible-hidden")) {
				$(this).parent().find(".commission-type-collapsible-body").slideDown().removeClass("collapsible-hidden").addClass("collapsible-visible");
				$(this).find("i.material-icons").text("arrow_drop_up");
			} else {
				$(this).parent().find(".commission-type-collapsible-body").slideUp().addClass("collapsible-hidden").removeClass("collapsible-visible");
				$(this).find("i.material-icons").text("arrow_drop_down");
			}
		});

		/* WISHLIST FUNCTIONS */
		$(document).on("click", ".wishlist-remove-btn", function(e) {
			$(this).addClass("wishlist-add-btn").removeClass("wishlist-remove-btn").text("Add to wishlist");
			var data = new FormData();
			data.append("id", $(this).attr("data-id"));
			$.ajax($("html").attr("data-rootdir")+"Dashboard/delwishlist.php", {
				data: data,
				processData: false,
				contentType: false,
				method: "POST"
			}).done(function(response) {
				Materialize.toast("Removed from wishlist", 4000);
			}).fail(function(response) {
				alert("Unknown error.");
				window.location="";
			});
			if (e.stopPropogation) e.stopPropogation();
			if (e.stopImmediatePropagation) e.stopImmediatePropagation();
			e.preventDefault();
			return false;
		});
		$(document).on("click", ".wishlist-add-btn", function(e) {
			$(this).addClass("wishlist-remove-btn").removeClass("wishlist-add-btn").text("Remove from wishlist");
			var data = new FormData();
			data.append("id", $(this).attr("data-id"));
			$.ajax($("html").attr("data-rootdir")+"Dashboard/addwishlist.php", {
				data: data,
				processData: false,
				contentType: false,
				method: "POST"
			}).done(function(response) {
				Materialize.toast("Added to wishlist", 4000);
			}).fail(function(response) {
				alert("Unknown error.");
				window.location="";
			});
			if (e.stopPropogation) e.stopPropogation();
			if (e.stopImmediatePropagation) e.stopImmediatePropagation();
			e.preventDefault();
			return false;
		});

		/* FEATURE BOARD */
		$(document).on("click", "tr.feature-item td:not(.vote)", function(e) {
			window.location = $(this).parent().attr("data-url");
		});
		$(document).on("click", "tr.feature-item .vote-btn", function(e) {
			e.preventDefault();
			if (e.stopPropogation) e.stopPropogation();
			if (e.stopImmediatePropagation) e.stopImmediatePropagation();

			var data = new FormData();
			data.append("feature", $(this).parent().parent().parent().attr("data-id"));
			if ($(this).hasClass("vote-yes")) {
				data.append("vote", "YES");
			} else if ($(this).hasClass("vote-maybe")) {
				data.append("vote", "MAYBE");
			} else if ($(this).hasClass("vote-no")) {
				data.append("vote", "NO");
			} else if ($(this).hasClass("vote-irrelevant")) {
				data.append("vote", "IRRELEVANT");
			}
			$(this).parent().find(".vote-btn").removeClass("vote-btn");
			$(this).text(parseInt($(this).text())+1);
			$.ajax($("html").attr("data-rootdir")+"FeatureBoard/vote.php", {
				data: data,
				processData: false,
				contentType: false,
				method: "POST"
			}).done(function(response) {
				Materialize.toast("Vote submitted", 4000);
			}).fail(function(response) {
				alert("Unknown error.");
				window.location="";
			});
		});
		<?php
		echo FormJS::generateFormHandler(Comment::getFormStructure());
		?> 

		/* NEW FEATURE REQUESTS */
		<?php
		echo FormJS::generateFormHandler(NewFeature::getFormStructure());
		?> 

		/* ONLOADS */
		$(document).on("keydown", function (event) {
			if (event.which === 8 && $("form").length != 0 && $(event.target).parents("form").length == 0) {
				event.preventDefault();
			}
		});

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
	});
})(jQuery);
