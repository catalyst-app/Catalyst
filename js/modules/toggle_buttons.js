$(document).on("click", ".toggle-btn", function(e) {
	if ($(this).hasClass("off")) {
		$(this).removeClass("off").addClass("on");
	} else {
		$(this).removeClass("on").addClass("off");
	}
});

$(document).on("mousedown", ".toggle-btn", function(e) {
	e.preventDefault && e.preventDefault();
});

$(document).on("click", ".toggle-btn-invert-btn", function(e) {
	e.preventDefault();
	$($(this).parent().next().children()).each(function(i, a) {
		if ($(a).hasClass("off")) {
			$(a).addClass("on").removeClass("off");
		} else {
			$(a).addClass("off").removeClass("on");
		}
	});
});
