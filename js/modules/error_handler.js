window.onerror = function(message, url, lineNumber) {  
	try {
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
	} catch (e) {}
	return false;
};
