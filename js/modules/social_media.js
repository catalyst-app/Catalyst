function addSocialMediaChip(data) {
	$(".modal").modal("close");
	$(".social-chips > div").append($(data.html).html());
}

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
		M.escapeToast("Removed", 4000);
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
		appendTo: 'body'
	}).on("mirror:created", function(e) {
		$(e.mirror).css("max-width", $(e.originalSource).width());
		$(e.mirror).find("i").remove(); // dont show remove button while dragging
	}).on("sortable:stop", function() {
		var result = [];
		for (var i = 0; i < $(".social-chips .chip:not(.draggable-mirror):visible").length; i++) {
			result.push($($(".social-chips .chip:not(.draggable-mirror):visible")[i]).attr("data-id"));
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
			M.escapeToast("Saved", 4000);
		}).fail(function(response) {
			console.log(response);
			var data = JSON.parse(response.responseText);
			showErrorMessageForCode(data.error_code);
		});
	});
} catch(e) {}
