$(document).on("click", ".file-input-field, .file-input-field *", function(e) {
	$(this).find("input[type=file]").focus().trigger("click");
	if (e.stopPropogation) e.stopPropogation();
	if (e.stopImmediatePropagation) e.stopImmediatePropagation();
});
$(document).on('change', '.file-input-field .file-input-path', function () {
	if ($(this).val().length == 0 && $(this).attr("data-required") == "yes") {
		$(this).addClass('invalid').removeClass('valid');
	} else if ($(this).val().length == 0) {
		$(this).removeClass('valid');
	} else {
		$(this).addClass('valid').removeClass('invalid');
	}
});
$(document).on('change', '.file-input-field input[type="file"]', function () {
	var fileField = $(this).closest('.file-input-field');
	var pathInput = fileField.find('input.file-input-path');
	var files = $(this)[0].files;
	var fileNames = [];
	for (var i = 0; i < files.length; i++) {
		fileNames.push(files[i].name);
	}
	pathInput.val(fileNames.join(", "));
	pathInput.trigger('change');
	if (files.length == 0) {
		pathInput.parent().find("label").removeClass("active");
	} else {
		pathInput.parent().find("label").addClass("active");
	}
});
