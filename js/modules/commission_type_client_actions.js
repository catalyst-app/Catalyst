// hovering over remove from favorites
$(document).on("mouseover", ".commission-type-client-actions a[data-action=wishlist][data-state=on]", function() {
	window.log(<?= json_encode(basename(__FILE__)) ?>, "Remove from wishlist button mouseover");

	$(this).addClass("red");
});
// unhovering over remove from favorites
$(document).on("mouseout", ".commission-type-client-actions a[data-action=wishlist][data-state=on]", function() {
	window.log(<?= json_encode(basename(__FILE__)) ?>, "Remove from wishlist button mouseout");

	$(this).removeClass("red");
});

// removing from wishlist
$(document).on("click", ".commission-type-client-actions a[data-action=wishlist][data-state=on]", function(e) {
	window.log(<?= json_encode(basename(__FILE__)) ?>, "Removing CT "+$(this).closest(".commission-type-row").attr("data-token")+" from wishlist");

	$(this).closest(".tooltipped")[0].M_Tooltip.close();
	$(this).attr("data-state", "off")
		.removeClass("red")
		.find("i").text("star_outline")
		.closest("li").attr("data-tooltip", "Add to wishlist")[0];

	e.stopPropogation && e.stopPropogation();
	e.stopImmediatePropogation && e.stopImmediatePropogation();
	e.preventDefault && e.preventDefault();

	return false;
});

// adding to wishlist
$(document).on("click", ".commission-type-client-actions a[data-action=wishlist][data-state=off]", function(e) {
	window.log(<?= json_encode(basename(__FILE__)) ?>, "Adding CT "+$(this).closest(".commission-type-row").attr("data-token")+" to wishlist");

	$(this).closest(".tooltipped")[0].M_Tooltip.close();
	$(this).attr("data-state", "on")
		.addClass("red")
		.find("i").text("star")
		.closest("li").attr("data-tooltip", "Remove from wishlist")[0];

	e.stopPropogation && e.stopPropogation();
	e.stopImmediatePropogation && e.stopImmediatePropogation();
	e.preventDefault && e.preventDefault();

	return false;
});
