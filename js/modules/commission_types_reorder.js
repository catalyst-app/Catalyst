new Draggable.Sortable(document.querySelectorAll(".commission-type-rearranger"), {
	draggable: '.commission-type-rearranger > div',
	appendTo: 'body'
}).on("mirror:created", function(e) {
	window.log(<?= json_encode(basename(__FILE__)) ?>, "mirror:created - setting mirror width");

	$(e.mirror).width($(e.originalSource).width()*.95);
}).on("sortable:stop", function(e) {
	window.log(<?= json_encode(basename(__FILE__)) ?>, "sortable:stop - we are done sorting.  Pushing...");
	
	var result = [];
	for (var i = 0; i < $(".commission-type-rearranger > div:not(.draggable-mirror):visible").length; i++) {
		result.push($($(".commission-type-rearranger > div:not(.draggable-mirror):visible")[i]).attr("data-token"));
	}

	window.log(<?= json_encode(basename(__FILE__)) ?>, "sortable:stop - aggregating commission type order, saving to server ("+JSON.stringify(result)+")");

	if (e.oldIndex == e.newIndex) {
	} else if (e.oldIndex < e.newIndex) { // moving forwards
		var originalElement = $(".user-commission-types").find(".commission-type-row").eq(e.oldIndex);
		var beforeElement = $(".user-commission-types").find(".commission-type-row").eq(e.newIndex);
		beforeElement.after(originalElement);
	} else if (e.newIndex == 0) {
		var originalElement = $(".user-commission-types").find(".commission-type-row").eq(e.oldIndex);
		$(".user-commission-types").prepend(originalElement);
	} else { // backwards
		var originalElement = $(".user-commission-types").find(".commission-type-row").eq(e.oldIndex);
		var afterElement = $(".user-commission-types").find(".commission-type-row").eq(e.newIndex);
		afterElement.before(originalElement);
	}

	var data = new FormData();
	data.append("rootdir", $("html").attr("data-rootdir"));
	data.append("order", JSON.stringify(result));

	$.ajax($("html").attr("data-rootdir") + "api\/internal\/commission_type\/order\/", {
		data: data,
		processData: false,
		contentType: false,
		method: "POST"
	}).done(function(response) {
		window.log(<?= json_encode(basename(__FILE__)) ?>, "sortable:stop - request complete, toasting");
		M.escapeToast("Saved", 4000);
	}).fail(function(response) {
		window.log(<?= json_encode(basename(__FILE__)) ?>, "sortable:stop - request failed, parsing error and toasting", true);
		var data = JSON.parse(response.responseText);
		showErrorMessageForCode(data.error_code);
	});
});
