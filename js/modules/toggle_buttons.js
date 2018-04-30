$(document).on("click", ".toggle-btn", function(e) {
	if ($(this).hasClass("off")) {
		$(this).removeClass("off").addClass("on");
	} else {
		$(this).removeClass("on").addClass("off");
	}
    window.log(<?= json_encode(basename(__FILE__)) ?>, "Toggling button "+($(this).attr("data-key") ? $(this).attr("data-key") : $(this).attr("data-action")));
	$(this).trigger("toggle", {newValue: $(this).hasClass("on")});
});

$(document).on("click", ".btn[href^=\"#\"]", function(e) {
	e.preventDefault && e.preventDefault();
	e.stopPropogation && e.stopPropogation();
});

$(document).on("mousedown", ".toggle-btn", function(e) {
	e.preventDefault && e.preventDefault();
});

$(document).on("click", ".toggle-btn-invert-btn", function(e) {
	e.preventDefault && e.preventDefault();
    window.log(<?= json_encode(basename(__FILE__)) ?>, "Inverting button set "+$(this).parent().text());
	$($(this).parent().next().children()).each(function(i, a) {
		$(a).trigger("click");
	});
});
