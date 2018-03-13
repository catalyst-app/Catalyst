<?php
use \Catalyst\Color;
?>
function initializeColorPicker() {
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
