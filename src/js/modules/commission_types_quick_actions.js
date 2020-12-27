$(document).on("toggle", ".commission-type-quick-toggle-button", function(e, eventData) {
	var token = $(this).closest(".commission-type-row").attr("data-token");
	var action = $(this).attr("data-action");

	window.log(<?= json_encode(basename(__FILE__)) ?>, token+" - "+action+" is changed to "+eventData.newValue);

	var data = new FormData();
	data.append("rootdir", $("html").attr("data-rootdir"));
	data.append("token", token);
	data.append("action", action);
	data.append("value", eventData.newValue);

	$.ajax($("html").attr("data-rootdir") + "api\/internal\/commission_type\/toggle_quick_action\/", {
		data: data,
		processData: false,
		contentType: false,
		method: "POST"
	}).done(function(response) {
		window.log(<?= json_encode(basename(__FILE__)) ?>, token+" - "+action+" has been toggled");
		M.escapeToast("Saved", 4000);
	}).fail(function(response) {
		window.log(<?= json_encode(basename(__FILE__)) ?>, token+" - "+action+" failed", true);
		console.log(response);
		var data = JSON.parse(response.responseText);
		showErrorMessageForCode(data.error_code);
	});
});
