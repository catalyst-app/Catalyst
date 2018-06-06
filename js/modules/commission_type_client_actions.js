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
	var token = $(this).closest(".commission-type-row").attr("data-token");

	window.log(<?= json_encode(basename(__FILE__)) ?>, "Removing CT "+token+" from wishlist");

	var data = new FormData();
	data.append("token", token);
	data.append("rootdir", $("html").attr("data-rootdir"));

	window.log(<?= json_encode(basename(__FILE__)) ?>, "wishlist removal - sending AJAX request to remove "+token);

	$.ajax($("html").attr("data-rootdir") + "api\/internal\/wishlist\/remove\/", {
		data: data,
		processData: false,
		contentType: false,
		method: "POST"
	}).done(function(response) {
		window.log(<?= json_encode(basename(__FILE__)) ?>, "wishlist removal for token "+token+" - request complete, toasting");
		M.escapeToast("Removed", 4000);
	}).fail(function(response) {
		window.log(<?= json_encode(basename(__FILE__)) ?>, "wishlist removal for token "+token+" - request failed, parsing error and toasting", true);
		console.log(response);
		var data = JSON.parse(response.responseText);
		showErrorMessageForCode(data.error_code);
	});

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
	var token = $(this).closest(".commission-type-row").attr("data-token");

	window.log(<?= json_encode(basename(__FILE__)) ?>, "Adding CT "+token+" to wishlist");

	var data = new FormData();
	data.append("token", token);
	data.append("rootdir", $("html").attr("data-rootdir"));

	window.log(<?= json_encode(basename(__FILE__)) ?>, "wishlist addition - sending AJAX request to add "+token);

	$.ajax($("html").attr("data-rootdir") + "api\/internal\/wishlist\/add\/", {
		data: data,
		processData: false,
		contentType: false,
		method: "POST"
	}).done(function(response) {
		window.log(<?= json_encode(basename(__FILE__)) ?>, "wishlist addition for token "+token+" - request complete, toasting");
		M.escapeToast("Added", 4000);
	}).fail(function(response) {
		window.log(<?= json_encode(basename(__FILE__)) ?>, "wishlist addition for token "+token+" - request failed, parsing error and toasting", true);
		console.log(response);
		var data = JSON.parse(response.responseText);
		showErrorMessageForCode(data.error_code);
	});

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
