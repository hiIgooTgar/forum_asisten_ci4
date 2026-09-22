<?= $this->extend('layouts/student') ?>

<?= $this->section('content') ?>
<?php
$profileIncomplete = $profileIncomplete ?? false;
$hasNoTakenCourses = $hasNoTakenCourses ?? false;
$document          = $document ?? null;

if (!isset($student) || empty($student)) {
    $student = (object) [
        'student_number' => '',
        'full_name'      => '',
        'profile'        => '',
        'program_name'   => ''
    ];
}

$student = (object) $student;

$profileImg = (!empty($student->profile) && file_exists(FCPATH . 'uploads/profile_student/' . $student->profile))
    ? base_url('uploads/profile_student/' . $student->profile)
    : base_url('assets/images/profile/profile-default.png');

$validationErrors = session()->getFlashdata('errors');

$docFields = [
    'student_card_file'       => ['label' => '1. Kartu Tanda Mahasiswa (KTM)', 'format' => 'KTM_NIM_Nama-Lengkap', 'template' => 'template_ktm.docx'],
    'application_letter_file' => ['label' => '2. Surat Lamaran', 'format' => 'SL_NIM_Nama-Lengkap', 'template' => 'template_surat_lamaran.docx'],
    'cv_file'                 => ['label' => '3. Curriculum Vitae (CV)', 'format' => 'CV_NIM_Nama-Lengkap', 'template' => 'template_cv.docx'],
    'latest_transcript_file'  => ['label' => '4. Transkrip Nilai Terakhir', 'format' => 'TNT_NIM_Nama-Lengkap', 'template' => 'template_transkrip_nilai.docx'],
    'statement_letter_file'   => ['label' => '5. Surat Pernyataan', 'format' => 'SP_NIM_Nama-Lengkap', 'template' => 'template_surat_pernyataan.docx'],
    'registration_form_file'  => ['label' => '6. Formulir Pendaftaran', 'format' => 'FP_NIM_Nama-Lengkap', 'template' => 'template_form_pendaftaran.docx']
];

$uploadedCount = 0;
if ($document) {
    foreach (array_keys($docFields) as $f) {
        if (!empty($document->$f)) {
            $uploadedCount++;
        }
    }
}

$totalDocs = count($docFields);
$isFormDisabled = ($profileIncomplete || $hasNoTakenCourses);
$warningReason  = $profileIncomplete ? 'profile' : ($hasNoTakenCourses ? 'courses' : '');
?>

<div class="app-title shadow-sm bg-white rounded p-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
    <div>
        <h1 class="h4 font-weight-bold mb-1 text-dark d-flex align-items-center">
            <i class="fa fa-folder-open text-primary mr-2"></i> Upload Berkas Pendaftaran
        </h1>
        <p class="mb-0 text-muted">Kelola dokumen serta berkas kelengkapan administrasi pendaftaran akademik Anda</p>
    </div>
    <ul class="app-breadcrumb breadcrumb side bg-transparent p-0 m-0 mt-2 mt-sm-0">
        <li class="breadcrumb-item"><a href="<?= base_url('student/dashboard'); ?>"><i class="fa fa-home text-muted"></i></a></li>
        <li class="breadcrumb-item active text-primary font-weight-semibold">Upload Berkas</li>
    </ul>
</div>

<?php if ($profileIncomplete): ?>
    <div class="alert alert-primary border-0 shadow-sm p-3 p-md-4 mb-4 rounded-lg" style="background-color: #ffffff; border-left: 4px solid #0a2481 !important;">
        <div class="d-flex align-items-start" style="gap: 0.9rem">
            <div class="text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; background-color: rgba(10, 36, 129, 0.15);">
                <i class="fa fa-exclamation-triangle fa-lg"></i>
            </div>
            <div>
                <h6 class="font-weight-bold text-dark mb-1" style="font-size: 0.95rem;">
                    Profil Belum Lengkap!
                </h6>
                <p class="text-secondary text-small-c mb-0" style="line-height: 1.5;">
                    Anda belum dapat mengunggah berkas pendaftaran karena terdapat data profil pengguna yang masih kosong. Silakan lengkapi profil Anda terlebih dahulu pada menu Biodata Mahasiswa bagian <a href="<?= base_url('student/profile'); ?>" class="text-primary font-weight-bold">Profil Diri</a>.
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (!$profileIncomplete && $hasNoTakenCourses): ?>
    <div class="alert alert-primary border-0 shadow-sm p-3 p-md-4 mb-4 rounded-lg" style="background-color: #ffffff; border-left: 4px solid #0a2481 !important;">
        <div class="d-flex align-items-start" style="gap: 0.9rem">
            <div class="text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; background-color: rgba(10, 36, 129, 0.15);">
                <i class="fa fa-book-reader fa-lg"></i>
            </div>
            <div>
                <h6 class="font-weight-bold text-dark mb-1" style="font-size: 0.95rem;">
                    Belum Mengambil Mata Kuliah!
                </h6>
                <p class="text-secondary text-small-c mb-0" style="line-height: 1.5;">
                    Anda harus mendaftarkan mata kuliah yang diambil terlebih dahulu sebelum dapat mengunggah berkas pendaftaran. Silakan daftarkan mata kuliah Anda di menu Pendaftaran Asisten bagian <a href="<?= base_url('student/taken-courses'); ?>" class="text-primary font-weight-bold">Daftar Mata Kuliah</a>.
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (!empty($validationErrors) && is_array($validationErrors)): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm p-3 p-md-4 mb-4 position-relative overflow-hidden" role="alert" style="background-color: #fdf2f2; border-left: 4px solid #e53e3e !important;">
        <div class="d-flex align-items-start" style="gap: 0.9rem">
            <div class="alert-icon-wrapper bg-soft-danger text-danger rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; background-color: rgba(229, 62, 62, 0.12);">
                <i class="fa fa-exclamation-circle fa-lg"></i>
            </div>
            <div class="pr-4 flex-grow-1">
                <h6 class="font-weight-bold text-danger mb-2" style="font-size: 0.95rem;">
                    Terjadi Kesalahan Unggah Berkas
                </h6>
                <ul class="mb-0 pl-4 text-dark small" style="font-size: 0.85rem; line-height: 1.6;">
                    <?php foreach ($validationErrors as $error): ?>
                        <li class="mb-1 text-secondary"><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <button type="button" class="close p-3 position-absolute" data-dismiss="alert" aria-label="Close" style="top: 0; right: 0; outline: none; opacity: 0.5;">
            <span aria-hidden="true" class="text-danger" style="font-size: 1.25rem;">&times;</span>
        </button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-xl-3 col-lg-4 col-12 mb-4">
        <div class="tile p-3 bg-white shadow-sm rounded border-0 h-auto">
            <div class="tile-header-custom d-flex align-items-center justify-content-between">
                <h6 class="font-weight-bold mb-0 text-white small text-uppercase" style="letter-spacing: 0.5px;">
                    <i class="fa fa-user mr-1"></i> Profil
                </h6>
                <?php
                $isActive   = ($student->status_account ?? '') === 'active';
                $statusText = $isActive ? 'Active' : 'Inactive';
                ?>
                <span class="badge bg-transparent border border-warning text-warning font-weight-normal px-2 py-1" style="font-size: 0.68rem;">
                    <i class="fa fa-circle mr-1" style="font-size: 0.5rem; vertical-align: middle;"></i><?= $statusText ?>
                </span>
            </div>

            <hr class="my-4">

            <div class="text-center p-2">
                <img src="<?= $profileImg ?>" class="rounded-circle img-thumbnail mb-2 shadow-xs" style="width: 85px; height: 85px; object-fit: cover;">
                <h6 class="font-weight-bold text-dark mb-0 text-truncate"><?= esc($student->full_name ?: '-') ?></h6>
                <span class="text-small-c mt-1 d-block text-truncate"><?= esc($student->student_number ?: '-') ?></span>
                <span class="badge badge-soft-primary mt-2 px-3 py-1 text-wrap"><?= esc($student->program_name ?: 'Mahasiswa') ?></span>

                <div class="clock-widget-container mt-3 p-2 text-center">
                    <div id="realtime-day-date" class="text-muted small font-weight-bold" style="font-size: 0.8rem;">--</div>
                    <div id="realtime-clock" class="clock-time-display font-weight-bold mt-1" style="font-size: 0.8rem;">00:00:00 WIB</div>
                </div>
            </div>

            <hr class="my-4">

            <div class="px-1">
                <small class="text-muted d-block font-weight-bold text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Status Berkas Pendaftaran</small>
                <div class="d-flex justify-content-between align-items-center text-xs-c mb-2">
                    <span class="text-secondary">Terunggah:</span>
                    <span class="font-weight-bold badge badge-primary px-2.5 py-1"><?= $uploadedCount ?> / <?= $totalDocs ?></span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?= ($uploadedCount / $totalDocs) * 100 ?>%;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-9 col-lg-8 col-12">
        <div class="tile p-3 p-md-4 bg-white shadow-sm rounded border-0">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center pb-3 mb-4 border-bottom gap-4">
                <div>
                    <h5 class="font-weight-bold mb-1 text-dark border-left-primary pl-2">Unggah Berkas Administrasi</h5>
                    <p class="text-secondary text-small-c mb-0">Lengkapi berkas pendaftaran sesuai dengan format dan aturan penamaan yang ditentukan.</p>
                </div>
            </div>

            <div class="p-3 bg-soft-primary border-left-primary mb-4 rounded-right">
                <div class="d-flex align-items-center mb-1">
                    <i class="fa fa-info-circle text-primary mr-2"></i>
                    <h6 class="font-weight-bold mb-0 text-dark">Petunjuk & Persyaratan Unggah Berkas</h6>
                </div>
                <ul class="mb-0 pl-4 text-secondary" style="font-size: 0.85rem; line-height: 1.6;">
                    <li><small class="text-xs-c d-block">Seluruh dokumen <strong>wajib berformat PDF</strong> dengan ukuran maksimum <strong>3MB</strong> per file.</small></li>
                    <li><small class="text-xs-c d-block">Harap perhatikan petunjuk penamaan berkas pada masing-masing formulir sebelum melakukan unggah.</small></li>
                    <li><small class="text-xs-c d-block">Pastikan berkas dokumen yang diunggah dapat terbaca dengan jelas.</small></li>
                    <li><small class="text-xs-c d-block">Anda dapat mengunduh format template berkas pendaftaran yang disediakan di bagian bawah setiap formulir.</small></li>
                </ul>
            </div>

            <?php
            $formAction = $document
                ? base_url('student/documents/update/' . $document->document_code)
                : base_url('student/documents/store');
            ?>

            <form action="<?= $formAction ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="row">
                    <?php foreach ($docFields as $fieldName => $meta): ?>
                        <?php
                        $existingFile = $document->$fieldName ?? null;
                        $oldFileName  = old('old_' . $fieldName, $existingFile);
                        $isUploaded = !empty($oldFileName);

                        $fileDisplayText = "Pilih atau Seret Berkas PDF";
                        $textClass       = "text-dark";
                        ?>
                        <div class="col-md-6 col-12 mb-4">
                            <div class="card h-100 border rounded-lg p-3 shadow-xs">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <label class="font-weight-bold text-dark text-small-c mb-0">
                                        <?= esc($meta['label']) ?> <span class="text-danger">*</span>
                                    </label>
                                    <?php if ($isUploaded): ?>
                                        <span class="badge badge-soft-success" style="font-size: 0.7rem;">
                                            <i class="fa fa-check-circle mr-1"></i>Tersimpan
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-2">
                                    <span class="naming-badge"><i class="fa fa-tag mr-1"></i>Format: <?= esc($meta['format']) ?></span>
                                </div>
                                <input type="hidden" name="old_<?= esc($fieldName) ?>" value="<?= esc($oldFileName) ?>">

                                <div class="custom-file-dropzone mb-3"
                                    data-disabled="<?= $isFormDisabled ? 'true' : 'false' ?>"
                                    data-reason="<?= esc($warningReason) ?>">
                                    <i class="fa fa-cloud-upload-alt text-primary fa-2x mb-1"></i>
                                    <span class="d-block font-weight-semibold small file-name-display <?= $textClass ?>">
                                        <?= esc($fileDisplayText) ?>
                                    </span>
                                    <input type="file" name="<?= esc($fieldName) ?>" accept=".pdf,application/pdf" onchange="updateFileName(this)">
                                </div>

                                <div class="mt-auto d-flex justify-content-between align-items-center pt-2 border-top">
                                    <?php if (!empty($existingFile)): ?>
                                        <a href="<?= base_url('student/files/view/document_file/' . esc($existingFile)) ?>" target="_blank" class="btn btn-sm btn-outline-primary font-weight-semibold">
                                            <i class="fa fa-eye mr-1"></i> Lihat Berkas
                                        </a>
                                    <?php else: ?>
                                        <span class="badge badge-soft-danger"> <i class="fa fa-times-circle"></i> Belum Diunggah</span>
                                    <?php endif; ?>

                                    <a href="<?= base_url('registration/templates_file/' . esc($meta['template'])) ?>" class="text-primary text-xs-c font-weight-bold" download>
                                        <i class="fa fa-download mr-1"></i> Template
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="d-flex flex-column-reverse flex-sm-row justify-content-end gap-2 align-items-center mt-3 pt-3 border-top">
                    <?php if ($document): ?>
                        <div>
                            <button type="button"
                                class="btn btn-outline-danger font-weight-bold px-3 py shadow-sm"
                                onclick="<?= $isFormDisabled ? "handleIncompleteWarning('$warningReason')" : "confirmResetDocuments('" . base_url('student/documents/reset/' . $document->document_code) . "')" ?>">
                                <i class="fa fa-undo mr-1"></i> Reset Berkas
                            </button>
                        </div>
                    <?php endif; ?>

                    <div>
                        <button type="<?= $isFormDisabled ? 'button' : 'submit' ?>"
                            class="btn btn-primary font-weight-bold px-4 py shadow-sm"
                            <?= $isFormDisabled ? "onclick=\"handleIncompleteWarning('$warningReason')\"" : "" ?>>
                            <i class="fa fa-cloud-upload-alt mr-1.5"></i> <?= $document ? 'Perbarui Berkas' : 'Simpan & Unggah Berkas' ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const dropzones = document.querySelectorAll(".custom-file-dropzone");

        dropzones.forEach((zone) => {
            const fileInput = zone.querySelector('input[type="file"]');

            if (fileInput) {
                fileInput.addEventListener("click", function(e) {
                    const isDisabled = zone.getAttribute("data-disabled") === "true";
                    const reason = zone.getAttribute("data-reason");

                    if (isDisabled) {
                        e.preventDefault();
                        handleIncompleteWarning(reason);
                    }
                });
            }
        });
    });
</script>

<?= $this->endSection() ?>