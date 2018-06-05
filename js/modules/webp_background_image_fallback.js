var img = new Image();
img.onerror = function() {
	window.log("webp", "webp support is not present", true);
	$(".primary-webp-thumb").each(function(a,b) {
		window.log("webp", "Reverting "+$(b).css("background-image")+" to fallback "+$(b).attr("data-fallback-src"), true);
		$(b).css("background-image", "url(\""+$(b).attr("data-fallback-src")+"\")");
	});
};
img.onload = function() {
	window.log("webp", "webp support is present");
};

window.log("webp", "Testing webp support");
img.src="data:image/webp;base64,UklGRiIAAABXRUJQVlA4IBYAAAAwAQCdASoBAAEADsD+JaQAA3AAAAAA";
