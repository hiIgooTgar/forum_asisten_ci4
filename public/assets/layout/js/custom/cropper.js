$(document).ready(function () {
  let cropper = null;
  let activeFileInput = null;
  let activeTargetInput = null;
  let activePreviewImg = null;
  let activePlaceholder = null;
  let activeRemoveBtn = null;

  let cropWidth = 400;
  let cropHeight = 400;

  const $cropModal = $("#globalCropModal");
  const imageToCrop = document.getElementById("globalImageToCrop");

  if ($cropModal.length && imageToCrop) {
    $(document).on("change", ".crop-file-input", function (e) {
      const files = e.target.files;
      if (files && files.length > 0) {
        activeFileInput = this;

        const cropTargetSelector = $(this).attr("data-crop-target");
        const previewTargetSelector = $(this).attr("data-preview-target");
        const placeholderTargetSelector = $(this).attr(
          "data-placeholder-target",
        );
        const removeTargetSelector = $(this).attr("data-remove-target");

        activeTargetInput = $(cropTargetSelector)[0] || null;
        activePreviewImg = $(previewTargetSelector)[0] || null;
        activePlaceholder = $(placeholderTargetSelector)[0] || null;
        activeRemoveBtn = $(removeTargetSelector)[0] || null;

        const aspectRatio = parseFloat($(this).attr("data-aspect-ratio")) || 1;
        cropWidth = parseInt($(this).attr("data-crop-width")) || 400;
        cropHeight = parseInt($(this).attr("data-crop-height")) || 400;

        const reader = new FileReader();
        reader.onload = function (event) {
          imageToCrop.src = event.target.result;
          $cropModal.data("aspectRatio", aspectRatio);
          $cropModal.modal("show");
        };
        reader.readAsDataURL(files[0]);
      }
    });

    $cropModal
      .on("shown.bs.modal", function () {
        const ratio = $cropModal.data("aspectRatio") || 1;

        if (cropper) {
          cropper.destroy();
        }

        cropper = new Cropper(imageToCrop, {
          aspectRatio: ratio,
          viewMode: 1,
          autoCropArea: 1,
          responsive: true,
          restore: false,
        });
      })
      .on("hidden.bs.modal", function () {
        if (cropper) {
          cropper.destroy();
          cropper = null;
        }
        if (activeFileInput) {
          activeFileInput.value = "";
        }
      });

    $("#globalCropBtn").on("click", function () {
      if (cropper && activeTargetInput) {
        const canvas = cropper.getCroppedCanvas({
          width: cropWidth,
          height: cropHeight,
          imageSmoothingEnabled: true,
          imageSmoothingQuality: "high",
        });

        const base64Image = canvas.toDataURL("image/png");
        $(activeTargetInput).val(base64Image);

        const inputName = $(activeFileInput)
          .attr("id")
          .replace("_file_input", "");

        $(`#remove_${inputName}`).val("0");

        if (activePreviewImg) {
          $(activePreviewImg).attr("src", base64Image).show();
        }
        if (activePlaceholder) {
          $(activePlaceholder).hide();
        }
        if (activeRemoveBtn) {
          $(activeRemoveBtn).show();
        }

        $cropModal.modal("hide");
      }
    });
  }

  $(document).on("click", ".crop-btn-remove", function (e) {
    e.preventDefault();

    const name = $(this).attr("data-name");
    const $fileInput = $(`#${name}_file_input`);
    const defaultImageSrc = $fileInput.attr("data-default-image") || "";

    $fileInput.val("");
    $(`#${name}_base64`).val("");
    $(`#remove_${name}`).val("1");
    $(`#${name}_preview`).attr("src", defaultImageSrc).show();
    $(`#${name}_placeholder`).hide();
    $(this).hide();
  });
});
