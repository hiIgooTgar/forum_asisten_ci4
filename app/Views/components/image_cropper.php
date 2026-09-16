<?php
$name            = $name ?? 'profile';
$label           = $label ?? 'Foto Profil';
$required        = $required ?? false;
$aspectRatio     = $aspectRatio ?? 1;
$previewSize     = $previewSize ?? '140px';
$maxWidth        = $maxWidth ?? '100%';
$currentImage    = $currentImage ?? null;
$defaultImage    = $defaultImage ?? 'assets/images/profile/profile-default.png';
$placeholderText = $placeholderText ?? 'Belum ada foto';
$cropWidth       = $cropWidth ?? 400;
$cropHeight      = $cropHeight ?? 400;

$isDefault = empty($currentImage) || str_contains((string)$currentImage, 'profile-default.png');
$imagePath = $isDefault ? base_url($defaultImage) : base_url('uploads/profile_student/' . $currentImage);
?>

<style>
    .crop-card {
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(10, 36, 129, 0.03);
        transition: all 0.2s ease-in-out;
    }

    .crop-preview-box {
        position: relative;
        border-radius: 4px;
        border: 2px dashed #cbd5e1;
        background-color: #f8fafc;
        overflow: hidden;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .crop-preview-box:hover {
        border-color: var(--color-primary-combine, #0c2996);
        box-shadow: 0 0 0 2px var(--color-transparent-primary, rgba(10, 36, 129, 0.1));
    }

    .crop-btn-select {
        background-color: var(--color-primary-combine-v2, #0c1d61);
        color: #ffffff;
        border: none;
        border-radius: 4px;
        padding: 6px 14px;
        font-weight: 500;
        font-size: 0.75rem;
        cursor: pointer;
        transition: background-color 0.2s ease, transform 0.1s ease;
    }

    .crop-btn-select:hover {
        background-color: var(--color-hover-primary, #081750);
        color: #ffffff;
    }

    .crop-btn-remove-custom {
        border-radius: 4px;
        font-size: 0.75rem;
        padding: 5px 14px;
        font-weight: 500;
    }
</style>

<div class="form-group mb-4 crop-component-wrapper">
    <label class="control-label font-weight-bold mb-2 d-block" style="color: var(--color-primary-combine-v2, #0c1d61);">
        <?= esc($label); ?>
        <?php if ($required): ?>
            <span class="text-danger">*</span>
        <?php endif; ?>
    </label>

    <div class="crop-card p-3 p-md-4">
        <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-start gap-3" style="gap: 1.25rem;">

            <div class="crop-preview-box flex-shrink-0 d-flex align-items-center justify-content-center"
                style="width: <?= esc((string)$previewSize); ?>; height: <?= esc((string)$previewSize); ?>; max-width: <?= esc((string)$maxWidth); ?>;">

                <img id="<?= esc($name); ?>_preview"
                    src="<?= $imagePath; ?>"
                    alt="Preview Foto"
                    style="width: 100%; height: 100%; object-fit: cover; display: <?= $isDefault ? 'none' : 'block'; ?>;">

                <span id="<?= esc($name); ?>_placeholder"
                    class="text-muted small text-center px-2"
                    style="display: <?= $isDefault ? 'block' : 'none'; ?>;">
                    <i class="fa fa-cloud-upload fa-2x d-block mb-1" style="color: var(--color-primary-combine, #0c2996); opacity: 0.6;"></i>
                    <?= esc($placeholderText); ?>
                </span>
            </div>

            <div class="flex-grow-1 text-center text-sm-left w-100">
                <p class="text-muted small mb-3" style="line-height: 1.5; font-size: 0.85rem;">
                    Format yang didukung: <strong class="text-primary">JPG, JPEG, PNG</strong>. Pastikan rasio foto seimbang.
                </p>

                <div class="d-flex flex-wrap justify-content-center justify-content-sm-start align-items-center" style="gap: 8px;">
                    <label for="<?= esc($name); ?>_file_input" class="crop-btn-select mb-0">
                        <i class="fa fa-upload mr-1"></i> Pilih Foto
                    </label>

                    <input class="d-none crop-file-input <?= session('errors.' . $name) ? 'is-invalid' : '' ?>"
                        type="file"
                        id="<?= esc($name); ?>_file_input"
                        accept="image/png, image/jpeg, image/jpg"
                        data-name="<?= esc($name); ?>"
                        data-crop-target="#<?= esc($name); ?>_base64"
                        data-preview-target="#<?= esc($name); ?>_preview"
                        data-placeholder-target="#<?= esc($name); ?>_placeholder"
                        data-remove-target="#<?= esc($name); ?>_btn_remove"
                        data-default-image="<?= base_url($defaultImage); ?>"
                        data-aspect-ratio="<?= esc((string)$aspectRatio); ?>"
                        data-crop-width="<?= esc((string)$cropWidth); ?>"
                        data-crop-height="<?= esc((string)$cropHeight); ?>">

                    <button type="button"
                        id="<?= esc($name); ?>_btn_remove"
                        class="btn btn-outline-danger crop-btn-remove-custom crop-btn-remove"
                        style="<?= $isDefault ? 'display: none;' : 'display: inline-block;' ?>"
                        data-name="<?= esc($name); ?>"
                        title="Hapus Foto">
                        <i class="fa fa-trash mr-1"></i> Hapus Foto
                    </button>
                </div>
            </div>

        </div>
    </div>

    <input type="hidden" name="<?= esc($name); ?>" id="<?= esc($name); ?>_base64" value="<?= old($name); ?>">
    <input type="hidden" name="remove_<?= esc($name); ?>" id="remove_<?= esc($name); ?>" value="0">

    <?php if (session('errors.' . $name)): ?>
        <div class="invalid-feedback d-block mt-2"><?= session('errors.' . $name) ?></div>
    <?php endif; ?>
</div>