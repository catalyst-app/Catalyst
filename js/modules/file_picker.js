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
	var file_field = $(this).closest('.file-input-field');
	var path_input = file_field.find('input.file-input-path');
	var files = $(this)[0].files;
	var file_names = [];
	for (var i = 0; i < files.length; i++) {
		file_names.push(files[i].name);
	}
	path_input.val(file_names.join(", "));
	path_input.trigger('change');
});
