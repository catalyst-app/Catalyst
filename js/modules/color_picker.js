<?php
use \Catalyst\Color;
?>
window.initializeColorPicker = function() {
	var colors = <?= json_encode(Color::COLOR_BY_HEX) ?>;

	$(".color-swatch").hide();

	var swatches = $(".color-swatch");

	for (var i = 0; i < Object.keys(colors).length; i++) {
		$(swatches[i]).data("sub-colors", colors[Object.keys(colors)[i]]).show().css({
			backgroundColor: "#"+Object.keys(colors)[i]
		}, 100);
	}

	window.log(<?= json_encode(basename(__FILE__)) ?>, "initializeColorPicker - color picker initialized");
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

	window.log(<?= json_encode(basename(__FILE__)) ?>, ".on click for .color-swatch - color "+$(this).css("backgroundColor")+" chosen");

	if (Array.isArray(colors)) {
		for (var i = 0; i < colors.length; i++) {
			$(swatches[i]).show().css({
				backgroundColor: "#"+colors[i]
			}, 200);
		}

		window.log(<?= json_encode(basename(__FILE__)) ?>, ".on click for .color-swatch - color swatches updated");
	} else {
		$(".color-picker-modal").modal("close");
		window.log(<?= json_encode(basename(__FILE__)) ?>, ".on click for .color-swatch - modal closed");
	}
});
