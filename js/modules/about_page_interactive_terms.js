$(document).on("click", ".about-page-inline-block", function(e) {
	$(".about-page-inline-block[data-reveal-clump="+$(this).attr("data-reveal-clump")+"]").removeClass("about-page-inline-block-selected");
	$(this).addClass("about-page-inline-block-selected");

	$("[data-clump="+$(this).attr("data-reveal-clump")+"]").addClass("hide");
	$("#"+$(this).attr("data-reveal")).removeClass("hide");
});
