window.onerror = function(message, url, lineNumber) {  
	try {
		if ($ === undefined) {
			alert("An unreportable error occured.  Upgrading your browser or reloading the page may fix the issue.");
		} else {
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
				if (Materialize !== undefined) {
					Materialize.escapeToast("An error occured", 4000);
				} else {
					alert("An unknown error occured.");
				}
			}).fail(function(response) {
				if (Materialize !== undefined) {
					Materialize.escapeToast("An error occured", 4000);
				} else {
					alert("An unknown error occured.");
				}
			});
		}
	} catch (e) {}
	return false;
};
