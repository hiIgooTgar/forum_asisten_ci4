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

    $(document).on("change", ".crop-file-input", function (e) {
        const files = e.target.files;
        if (files && files.length > 0) {
            activeFileInput = this;
            activeTargetInput = $($(this).data("crop-target"))[0];
            activePreviewImg = $($(this).data("preview-target"))[0];
            activePlaceholder = $($(this).data("placeholder-target"))[0];
            activeRemoveBtn = $($(this).data("remove-target"))[0];

            const aspectRatio = parseFloat($(this).data("aspect-ratio")) || 1;
            cropWidth = parseInt($(this).data("crop-width")) || 400;
            cropHeight = parseInt($(this).data("crop-height")) || 400;

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
            $(`#${inputName}_is_removed`).val("0");

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

    $(document).on("click", ".crop-btn-remove", function (e) {
        e.preventDefault();

        const name = $(this).data("name");
        const $fileInput = $(`#${name}_file_input`);
        const defaultImageSrc = $fileInput.data("default-image");

        $fileInput.val("");
        $(`#${name}_base64`).val("");
        $(`#${name}_is_removed`).val("1");

        $(`#${name}_preview`).attr("src", defaultImageSrc).show();
        $(`#${name}_placeholder`).hide();
        $(this).hide();
    });
});
