var img = new Image();
img.onerror = function() {
	$(".has-webp-thumb").each(function(a,b) {
		$(b).attr("src", $(b).attr("primary-webp-thumb"));
	});
};
img.src="data:image/webp;base64,UklGRiIAAABXRUJQVlA4IBYAAAAwAQCdASoBAAEADsD+JaQAA3AAAAAA";
