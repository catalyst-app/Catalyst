<?php
use \Catalyst\Form\Field\MultipleImageWithNsfwCaptionAndInfoField;
use \Catalyst\Images\MIMEType;
?>
$(document).on("change", "input[type=file].<?= MultipleImageWithNsfwCaptionAndInfoField::INPUT_CLASS ?>", function(e) {
	if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
		window.log(<?= json_encode(basename(__FILE__)) ?>, ".on change for .<?= MultipleImageWithNsfwCaptionAndInfoField::INPUT_CLASS ?> - browser does not support nifty file APIs", true);
		$("#"+$(this).attr("id")+<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::ROW_CONTAINER_ID_SUFFIX) ?>).text("Your browser does not support the needed upload technologies.  Please upgrade your browser as soon as possible.");
		return;
	}

	var existingRows = [];
	var inputRows = [];
	var inputRowsFullId = [];
	var inputRowFileObjects = [];
	
	for (var i = 0; i < $(this)[0].files.length; i++) {
		file = $(this)[0].files[i];
		if (file.type.length == 0 || !<?= json_encode(MIMEType::getMimeTypes()) ?>.includes(file.type)) {
			continue;
		}
		inputRows.push(""<?= MultipleImageWithNsfwCaptionAndInfoField::EL_ID_SUFFIX_EXPR ?>);
		inputRowsFullId.push($(this).attr("id")+<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::ROW_ID_SUFFIX) ?><?= MultipleImageWithNsfwCaptionAndInfoField::EL_ID_SUFFIX_EXPR ?>);
		inputRowFileObjects.push(file);
	}

	for (var i = 0; i < $(<?= json_encode(".".MultipleImageWithNsfwCaptionAndInfoField::ROW_CLASS) ?>+'[data-input='+$(this).attr("id")+']').length; i++) {
		existingRows.push($($(<?= json_encode(".".MultipleImageWithNsfwCaptionAndInfoField::ROW_CLASS) ?>+'[data-input='+$(this).attr("id")+']')[i]).attr("id"));
	}

	var toRemove = [];
	var toAdd = [];
	
	for (var i = 0; i < existingRows.length; i++) {
		if (!(inputRowsFullId.includes(existingRows[i]))) {
			if (!$("#"+existingRows[i]).hasClass("pre-existing")) {
				toRemove.push(existingRows[i]);
			}
		}
	}
	for (var i = 0; i < inputRowsFullId.length; i++) {
		if (!(existingRows.includes(inputRowsFullId[i]))) {
			toAdd.push([inputRows[i], inputRowsFullId[i], inputRowFileObjects[i]]);
		}
	}

	for (var i = toRemove.length - 1; i >= 0; i--) {
		$("#"+toRemove[i]).remove();
		$("#"+toRemove[i]+"-reorder-img").parent().remove();
	}

	for (var i = 0; i < toAdd.length; i++) {
		// work around to clean scope, thanks to toish!
		(function(toAdd, i, input) {
			var file = toAdd[i][2];

			var row = $("<div></div>");
			row.addClass(<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::ROW_CLASS) ?>);
			row.addClass("row");
			row.attr("id", toAdd[i][1]);
			row.attr("data-input", $(input).attr("id"));

			var imgPreviewWrapper = $("<div></div>");
			imgPreviewWrapper.addClass("center");
			imgPreviewWrapper.addClass("force-square-contents");
			imgPreviewWrapper.addClass("col s4 offset-s4 m3 l2");

			var imgPreview = $("<div></div>");
			var id = toAdd[i][1]+"-img-preview";
			imgPreview.attr("id", id)
			imgPreview.addClass("img-strict-circle");

			imgPreviewWrapper.append(imgPreview);

			row.append(imgPreviewWrapper);

			var modalImgRepresentation = $("<div></div>");
			modalImgRepresentation.addClass("center");
			modalImgRepresentation.addClass("force-square-contents");
			modalImgRepresentation.addClass("col s4 m2");

			var modalImgPreview = $("<div></div>");
			modalImgPreview.addClass("img-strict-circle");
			modalImgPreview.attr("data-container", $(input).attr("id")+<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::ROW_CONTAINER_ID_SUFFIX) ?>);
			modalImgPreview.attr("id", toAdd[i][1]+"-reorder-img")
			modalImgPreview.css("margin", "1em");

			modalImgRepresentation.append(modalImgPreview);

			$("#"+$(input).attr("id")+<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::IMAGE_REARRANGER_ID_SUFFIX) ?>).append(modalImgRepresentation);

			var reader = new FileReader();
			reader.onload = function (e) {
				imgPreview.css("background-image", 'url('+e.target.result+')');

				modalImgPreview.css("background-image", 'url('+e.target.result+')');
			};
			reader.readAsDataURL(file);

			var remainingRowWrapper = $("<div></div>");
			remainingRowWrapper.addClass("left-align center-on-small-only");
			remainingRowWrapper.addClass("col s12 m9 l10");

			var infoLine = $("<h4></h4>");
			infoLine.addClass("col s12");
			infoLine.text(file.name + " ("+humanFileSize(file.size)+")");

			remainingRowWrapper.append(infoLine);

			var nsfwCheckbox = $("<p></p>");
			nsfwCheckbox.addClass("col s12");

			var nsfwCheckboxLabel = $("<label></label>");
			nsfwCheckboxLabel.attr("for", $(input).attr("id")+<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::NSFW_CHECKBOX_ID_SUFFIX) ?><?= MultipleImageWithNsfwCaptionAndInfoField::EL_ID_SUFFIX_EXPR ?>);
			
			var nsfwCheckboxInput = $("<input>");
			nsfwCheckboxInput.addClass("filled-in");
			nsfwCheckboxInput.addClass(<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::NSFW_CLASS) ?>);
			nsfwCheckboxInput.attr("id", $(input).attr("id")+<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::NSFW_CHECKBOX_ID_SUFFIX) ?><?= MultipleImageWithNsfwCaptionAndInfoField::EL_ID_SUFFIX_EXPR ?>);
			nsfwCheckboxInput.attr("type", "checkbox");
			
			nsfwCheckboxLabel.append(nsfwCheckboxInput);

			nsfwCheckboxLabelSpan = $("<span></span>");
			nsfwCheckboxLabelSpan.text("This image is mature or explicit");

			nsfwCheckboxLabel.append(nsfwCheckboxLabelSpan);

			nsfwCheckbox.append(nsfwCheckboxLabel);

			remainingRowWrapper.append(nsfwCheckbox);

			var captionWrapper = $("<div></div>");
			captionWrapper.addClass("input-field");
			captionWrapper.addClass("col s12 m6");

			var captionInput = $("<input>");
			captionInput.addClass(<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::CAPTION_CLASS) ?>);
			captionInput.attr("type", "text");
			captionInput.attr("maxlength", 255);
			captionInput.attr("id", $(input).attr("id")+<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::CAPTION_ID_SUFFIX) ?><?= MultipleImageWithNsfwCaptionAndInfoField::EL_ID_SUFFIX_EXPR ?>)

			captionWrapper.append(captionInput);

			var captionLabel = $("<label></label>");
			captionLabel.attr("data-error", "Caption cannot be longer than 255 characters");
			captionLabel.attr("for", $(input).attr("id")+<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::CAPTION_ID_SUFFIX) ?><?= MultipleImageWithNsfwCaptionAndInfoField::EL_ID_SUFFIX_EXPR ?>);
			captionLabel.text("Caption");

			captionWrapper.append(captionLabel);

			remainingRowWrapper.append(captionWrapper);

			var infoWrapper = $("<div></div>");
			infoWrapper.addClass("input-field");
			infoWrapper.addClass("col s12 m6");

			var infoInput = $("<input>");
			infoInput.addClass(<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::INFO_CLASS) ?>);
			infoInput.attr("type", "text");
			infoInput.attr("id", $(input).attr("id")+<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::INFO_ID_SUFFIX) ?><?= MultipleImageWithNsfwCaptionAndInfoField::EL_ID_SUFFIX_EXPR ?>)

			infoWrapper.append(infoInput);

			var infoLabel = $("<label></label>");
			infoLabel.attr("for", $(input).attr("id")+<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::INFO_ID_SUFFIX) ?><?= MultipleImageWithNsfwCaptionAndInfoField::EL_ID_SUFFIX_EXPR ?>);
			infoLabel.text($(input).attr("data-extra-info-name"));

			infoWrapper.append(infoLabel);

			remainingRowWrapper.append(infoWrapper);

			row.append(remainingRowWrapper);

			$("#"+$(input).attr("id")+<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::ROW_CONTAINER_ID_SUFFIX) ?>).append(row);
		})(toAdd, i, this);
	}

	if ($(<?= json_encode(".".MultipleImageWithNsfwCaptionAndInfoField::ROW_CLASS) ?>+'[data-input='+$(this).attr("id")+']').length === 0) {
		$('#reorder-modal-button-'+$(this).attr("id")).parent().remove();
	} else if ($('#reorder-modal-button-'+$(this).attr("id")).length == 0) {
		var buttonWrapper = $("<div></div>");
		buttonWrapper.addClass("col s12");

		var button = $("<div></div>");
		button.addClass("btn");
		button.addClass("right");
		button.addClass("modal-trigger");
		button.attr("id", 'reorder-modal-button-'+$(this).attr("id"));
		button.attr("data-target", $(this).attr("id")+<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::MODAL_ID_SUFFIX) ?>);
		button.text("reorder");

		buttonWrapper.append(button);

		$("#"+$(this).attr("id")+<?= json_encode(MultipleImageWithNsfwCaptionAndInfoField::ROW_CONTAINER_ID_SUFFIX) ?>).prepend(buttonWrapper);
	}

	window.log(<?= json_encode(basename(__FILE__)) ?>, ".on change for .<?= MultipleImageWithNsfwCaptionAndInfoField::INPUT_CLASS ?> - image upload arranger was refreshed with new files");
});
new Draggable.Sortable(document.querySelectorAll(".image-rearranger"), {
	draggable: '.image-rearranger > div',
	appendTo: 'body'
}).on("mirror:created", function(e) {
	window.log(<?= json_encode(basename(__FILE__)) ?>, "mirror:created - setting mirror width");

	$(e.mirror).width($(e.originalSource).width()*.95);
}).on("sortable:stop", function(e) {
	window.log(<?= json_encode(basename(__FILE__)) ?>, "sortable:stop - we are done sorting.  Rearranging corresponding elements to match surrogates");
	
	if (e.oldIndex == e.newIndex) {
		return;
	} else if (e.oldIndex < e.newIndex) { // moving forwards
		var containerId = $($(".image-rearranger .img-strict-circle:not(.draggable-mirror,.draggable-source--is-dragging):visible")[e.oldIndex]).attr("data-container");
		var originalElement = $("#"+containerId).find(".image-extra-info-row").eq(e.oldIndex);
		var beforeElement = $("#"+containerId).find(".image-extra-info-row").eq(e.newIndex);
		beforeElement.after(originalElement);
	} else if (e.newIndex == 0) {
		var containerId = $($(".image-rearranger .img-strict-circle:not(.draggable-mirror,.draggable-source--is-dragging):visible")[e.oldIndex]).attr("data-container");
		var originalElement = $("#"+containerId).find(".image-extra-info-row").eq(e.oldIndex);
		$("#"+containerId).prepend(originalElement);
		$("#"+containerId).prepend(originalElement.next()); // move reorder button back
	} else { // backwards
		var containerId = $($(".image-rearranger .img-strict-circle:not(.draggable-mirror,.draggable-source--is-dragging):visible")[e.oldIndex]).attr("data-container");
		var originalElement = $("#"+containerId).find(".image-extra-info-row").eq(e.oldIndex);
		var afterElement = $("#"+containerId).find(".image-extra-info-row").eq(e.newIndex);
		afterElement.before(originalElement);
	}
});
$(document).on("click", ".image-row-remove-icon", function(e) {
	window.log(<?= json_encode(basename(__FILE__)) ?>, ".on click for .image-row-remove-icon - removing an image row");

	$("[data-path=\""+$(this).parent().parent().parent().attr("data-internal-filename")+"\"]").parent().remove();
	$(this).parent().parent().parent().remove();
});
