<?= $this->extend('layouts/student') ?>

<?= $this->section('content') ?>
<?php
$student               = $student ?? null;
$takenCourses          = $takenCourses ?? [];
$profileIncomplete     = $profileIncomplete ?? true;
$hasNoTakenCourses     = $hasNoTakenCourses ?? true;
$documentsIncomplete   = $documentsIncomplete ?? true;
$canSubmitVerification = $canSubmitVerification ?? false;
$isCompleted           = $isCompleted ?? false;

$profileFileName = $student->profile ?? '';
$hasProfile      = !empty($profileFileName) && $profileFileName !== 'profile-default.png';

$realPhotoPath    = FCPATH . 'uploads/profile_student/real/' . $profileFileName;
$defaultPhotoPath = FCPATH . 'uploads/profile_student/' . $profileFileName;

if ($hasProfile && file_exists($realPhotoPath)) {
    $profileImg = base_url('uploads/profile_student/real/' . $profileFileName);
} elseif ($hasProfile && file_exists($defaultPhotoPath)) {
    $profileImg = base_url('uploads/profile_student/' . $profileFileName);
} else {
    $profileImg = base_url('assets/images/profile/profile-default.png');
}

$docItems = [
    'student_card_file'       => 'Kartu Tanda Mahasiswa (KTM)',
    'application_letter_file' => 'Surat Lamaran',
    'cv_file'                 => 'Curriculum Vitae (CV)',
    'latest_transcript_file'  => 'Transkrip Nilai Terakhir',
    'statement_letter_file'   => 'Surat Pernyataan',
    'registration_form_file'  => 'Formulir Pendaftaran'
];

$uploadedDocsCount = $uploadedDocsCount ?? 0;
$totalDocsCount    = count($docItems);
$percentage        = ($totalDocsCount > 0) ? round(($uploadedDocsCount / $totalDocsCount) * 100) : 0;

$verificationStatus = $student->verification_status ?? '';
?>

<div class="app-title shadow-sm bg-white rounded p-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
    <div>
        <h1 class="h4 font-weight-bold mb-1 text-dark d-flex align-items-center">
            <i class="fa fa-check-square text-primary mr-2"></i> Verifikasi Berkas Pendaftaran
        </h1>
        <p class="mb-0 text-muted">Pratinjau akhir dan verifikasi kelengkapan berkas calon anggota asisten</p>
    </div>
    <ul class="app-breadcrumb breadcrumb side bg-transparent p-0 m-0 mt-2 mt-sm-0">
        <li class="breadcrumb-item"><a href="<?= base_url('student/dashboard'); ?>"><i class="fa fa-home text-muted"></i></a></li>
        <li class="breadcrumb-item active text-primary font-weight-semibold">Verifikasi Pendaftaran</li>
    </ul>
</div>

<?php if ($verificationStatus === 'completed' || $isCompleted): ?>
    <div class="alert alert-primary border-0 shadow-sm p-3 p-md-4 mb-4 rounded-lg" style="background-color: #ffffff; border-left: 4px solid #0a2481 !important;">
        <div class="d-flex align-items-start" style="gap: 0.9rem">
            <div class="text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; background-color: rgba(10, 36, 129, 0.15);">
                <i class="fa fa-circle-check fa-lg"></i>
            </div>
            <div>
                <h5 class="font-weight-bold text-dark mb-1">
                    Data Anda Sudah Diverifikasi
                </h5>
                <p class="text-secondary text-small-c mb-0" style="line-height: 1.5;">
                    Verifikasi data Anda telah kami terima. Tunggu pengumuman seleksi administrasi dan persiapkan presentasi / projek mengenai materi mata kuliah yang didaftarkan untuk dipergunakan dalam tahap selanjutnya. Terima kasih.
                </p>
            </div>
        </div>
    </div>
<?php else: ?>
    <?php if ($profileIncomplete || $hasNoTakenCourses || $documentsIncomplete): ?>
        <div class="alert alert-primary border-0 shadow-sm p-3 p-md-4 mb-4 rounded-lg" style="background-color: #ffffff; border-left: 4px solid #0a2481 !important;">
            <div class="d-flex align-items-start" style="gap: 0.9rem">
                <div class="text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; background-color: rgba(10, 36, 129, 0.15);">
                    <i class="fa fa-exclamation-circle fa-lg"></i>
                </div>
                <div>
                    <h6 class="font-weight-bold text-dark mb-1" style="font-size: 0.95rem;">
                        Persyaratan Pendaftaran Belum Lengkap!
                    </h6>
                    <p class="text-secondary text-small-c mb-0" style="line-height: 1.5;">
                        Pastikan Foto Profil & Biodata Diri, Mata Kuliah yang Diambil, serta 6 Berkas Dokumen Wajib sudah diisi sebelum melakukan submit akhir.
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="card-modern p-4 mb-4">
            <h5 class="font-weight-bold mb-4 text-dark border-left-primary pl-2">1. Biodata Lengkap Calon Asisten</h5>
            <div class="row align-items-start">
                <div class="col-xl-2 col-lg-3 col-md-5 text-center border-right-md pr-md-4">
                    <div class="photo-container-4x6 mb-2">
                        <img src="<?= $profileImg ?>" alt="Pasfoto Calon Asisten">
                    </div>
                    <div class="small text-muted font-weight-bold mb-3">Pas Foto Resmi (4:6)</div>
                    <span class="badge <?= $isCompleted ? 'badge-status-completed' : 'badge-status-draft text-dark' ?> px-3 py-2">
                        <i class="fa <?= $isCompleted ? 'fa-lock' : 'fa-edit' ?> mr-1"></i>
                        <?= $isCompleted ? 'Pendaftaran Selesai' : 'Draf Pendaftaran' ?>
                    </span>
                </div>

                <div class="col-xl-10 col-lg-9 col-md-7 pl-md-4">
                    <div class="sub-section-header">
                        <span class="sub-section-icon"><i class="fa fa-user"></i></span>
                        <span>Data Pribadi & Kontak</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-md-12 col-lg-6 col-sm-6 mb-3">
                            <div class="info-card">
                                <div class="biodata-label">NIM</div>
                                <div class="biodata-value text-dark"><?= esc($student->student_number ?: '-') ?></div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-12 col-lg-6 col-sm-6 mb-3">
                            <div class="info-card">
                                <div class="biodata-label">Nama Lengkap</div>
                                <div class="biodata-value text-dark"><?= esc($student->full_name ?: '-') ?></div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-12 col-lg-6 col-sm-6 mb-3">
                            <div class="info-card">
                                <div class="biodata-label">Email Mahasiswa</div>
                                <div class="biodata-value"><?= esc($student->email ?: '-') ?></div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-12 col-lg-6 col-sm-6 mb-3">
                            <div class="info-card">
                                <div class="biodata-label">Nomor Telepon / WA</div>
                                <div class="biodata-value"><?= esc($student->phone_number ?: '-') ?></div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-12 col-lg-6 col-sm-6 mb-3">
                            <div class="info-card">
                                <div class="biodata-label">Tempat, Tanggal Lahir</div>
                                <div class="biodata-value">
                                    <?= esc($student->place_of_birth ?: '-') ?>, <?= format_indo_date($student->date_of_birth ?? null) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-12 col-lg-6 col-sm-6 mb-3">
                            <div class="info-card">
                                <div class="biodata-label">Jenis Kelamin</div>
                                <div class="biodata-value">
                                    <?= ($student->gender === 'male') ? 'Laki-Laki' : (($student->gender === 'female') ? 'Perempuan' : '-') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sub-section-header mt-2">
                        <span class="sub-section-icon"><i class="fa fa-graduation-cap"></i></span>
                        <span>Informasi Akademik</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-4 col-md-12 col-lg-6 col-sm-6 mb-3">
                            <div class="info-card">
                                <div class="biodata-label">Fakultas</div>
                                <div class="biodata-value"><?= esc($student->faculty_name ?: '-') ?></div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-12 col-lg-6 col-sm-6 mb-3">
                            <div class="info-card">
                                <div class="biodata-label">Program Studi / Jenjang</div>
                                <div class="biodata-value">
                                    <?= esc($student->program_name ?: '-') ?>
                                    <span class="badge badge-soft-primary-verification ml-1"><?= esc($student->degree_level ?: 'S1') ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-sm-6 mb-3">
                            <div class="info-card">
                                <div class="biodata-label">Kelas</div>
                                <div class="biodata-value"><?= esc($student->class_name ?: '-') ?></div>
                            </div>
                        </div>

                        <div class="col-xl-2 col-sm-6 mb-3">
                            <div class="info-card">
                                <div class="biodata-label">IPK Terakhir</div>
                                <div class="biodata-value text-primary font-weight-bold">
                                    <?= esc($student->gpa ?: '0.00') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="sub-section-header mt-2">
                        <span class="sub-section-icon"><i class="fa fa-map-marker-alt"></i></span>
                        <span>Alamat & Domisili</span>
                    </div>
                    <div class="row">
                        <div class="col-xl-3 col-md-12 col-lg-6 col-sm-6 mb-3">
                            <div class="info-card">
                                <div class="biodata-label">Provinsi</div>
                                <div class="biodata-value"><?= esc($student->province ?: '-') ?></div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-12 col-lg-6 col-sm-6 mb-3">
                            <div class="info-card">
                                <div class="biodata-label">Kabupaten / Kota</div>
                                <div class="biodata-value"><?= esc($student->regency ?: '-') ?></div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-12 col-lg-6 col-sm-6 mb-3">
                            <div class="info-card">
                                <div class="biodata-label">Kecamatan</div>
                                <div class="biodata-value"><?= esc($student->subdistrict ?: '-') ?></div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-12 col-lg-6 col-sm-6 mb-3">
                            <div class="info-card">
                                <div class="biodata-label">Kelurahan / Desa</div>
                                <div class="biodata-value"><?= esc($student->village ?: '-') ?></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="info-card">
                                <div class="biodata-label">Alamat Lengkap (Jalan, RT/RW, No. Rumah)</div>
                                <div class="biodata-value">
                                    <?= esc($student->address ?: '-') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card-modern p-4 mb-4">
            <div class="d-flex align-items-md-center align-items-start justify-content-between flex-column flex-md-row gap-2 mb-4">
                <h5 class="font-weight-bold mb-0 text-dark border-left-primary mb-2 mb-md-0 pl-2">
                    2. Mata Kuliah yang Diambil
                </h5>
                <span class="badge badge-soft-primary-verification px-3 py-2">
                    <i class="fa fa-book mr-1"></i> Total: <?= count($takenCourses) ?> Mata Kuliah
                </span>
            </div>

            <?php if (!empty($takenCourses)): ?>
                <div class="table-responsive d-none d-lg-block">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 5%;">No</th>
                                <th style="width: 15%;">Kode MK</th>
                                <th style="width: 40%;">Nama Mata Kuliah</th>
                                <th class="text-center" style="width: 15%;">Semester</th>
                                <th class="text-center" style="width: 10%;">SKS</th>
                                <th class="text-center" style="width: 15%;">Nilai Khusus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($takenCourses as $idx => $course):
                                $gradeUpper = strtoupper(trim($course->grade ?? ''));
                                $gradeBadgeClass = 'badge-grade-default';
                                if (strpos($gradeUpper, 'A') !== false) {
                                    $gradeBadgeClass = 'badge-grade-a';
                                } elseif (strpos($gradeUpper, 'B') !== false) {
                                    $gradeBadgeClass = 'badge-grade-b';
                                }
                            ?>
                                <tr>
                                    <td class="text-center font-weight-bold text-muted"><?= $idx + 1 ?></td>
                                    <td>
                                        <span class="badge badge-soft-primary-verification px-2 py-1">
                                            <?= esc($course->course_code) ?>
                                        </span>
                                    </td>
                                    <td style="font-weight: 600;" class="text-dark">
                                        <?= esc($course->course_name) ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="text-secondary font-weight-medium">Semester <?= esc($course->semester) ?></span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-warning px-2 py-1"><?= esc($course->credits) ?> SKS</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge <?= $gradeBadgeClass ?> px-3 py-1 text-xs-c">
                                            <?= esc($course->grade) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-block d-lg-none">
                    <?php foreach ($takenCourses as $idx => $course):
                        $gradeUpper = strtoupper(trim($course->grade ?? ''));
                        $gradeBadgeClass = 'badge-grade-default';
                        if (strpos($gradeUpper, 'A') !== false) {
                            $gradeBadgeClass = 'badge-grade-a';
                        } elseif (strpos($gradeUpper, 'B') !== false) {
                            $gradeBadgeClass = 'badge-grade-b';
                        }
                    ?>
                        <div class="course-card-mobile">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge badge-soft-primary-verification">
                                    <?= esc($course->course_code) ?>
                                </span>
                                <span class="badge <?= $gradeBadgeClass ?> px-2 py-1">
                                    Nilai: <?= esc($course->grade) ?>
                                </span>
                            </div>
                            <div class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">
                                <?= esc($course->course_name) ?>
                            </div>
                            <div class="d-flex justify-content-between text-primary align-items-center pt-2 border-top small">
                                <span><i class="fa fa-calendar-alt mr-1"></i> Semester <?= esc($course->semester) ?></span>
                                <span><i class="fa fa-credit-card mr-1"></i> <?= esc($course->credits) ?> SKS</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <div class="empty-state-box my-2">
                    <div class="empty-state-icon">
                        <i class="fa fa-folder-open"></i>
                    </div>
                    <h6 class="font-weight-bold text-primary mb-1">Belum Ada Mata Kuliah</h6>
                    <p class="text-xs-c text-secondary mb-0">
                        Calon asisten belum memilih atau menginput daftar mata kuliah yang pernah diambil.
                    </p>
                </div>

            <?php endif; ?>
        </div>

        <div class="card-modern p-4 mb-4">
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-4">
                <h5 class="font-weight-bold mb-3 mb-sm-2 mb-sm-0 text-dark border-left-primary pl-2">
                    3. Kelengkapan Berkas Administrasi
                </h5>

                <div class="d-flex align-items-center" style="min-width: 200px;">
                    <div class="mr-3 text-right">
                        <span class="badge <?= $percentage == 100 ? 'badge-soft-primary-verification' : 'badge-soft-danger' ?> px-2 py-1">
                            <?= $uploadedDocsCount ?> / <?= $totalDocsCount ?> Berkas (<?= $percentage ?>%)
                        </span>
                    </div>
                    <div class="progress progress-custom flex-grow-1" style="width: 60px; height: 8px;">
                        <div class="progress-bar progress-bar-uploaded" role="progressbar" style="width: <?= $percentage ?>%;" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>

            <div class="row">
                <?php foreach ($docItems as $field => $label): ?>
                    <?php
                    $isUploaded = !empty($student->$field);
                    $fileName   = $isUploaded ? $student->$field : '';
                    ?>
                    <div class="col-xl-4 col-lg-6 col-12 mb-3">
                        <div class="doc-card <?= $isUploaded ? 'doc-uploaded' : 'doc-empty' ?> d-flex align-items-center justify-content-between">

                            <div class="d-flex align-items-center mr-2" style="gap: 12px; min-width: 0;">
                                <div class="doc-icon-wrapper <?= $isUploaded ? 'uploaded' : 'empty' ?>">
                                    <i class="fa <?= $isUploaded ? 'fa-file-circle-check' : 'fa-file-circle-xmark' ?>"></i>
                                </div>

                                <div class="text-truncate">
                                    <div class="biodata-label text-truncate" title="<?= esc($label) ?>"><?= esc($label) ?></div>
                                    <?php if ($isUploaded): ?>
                                        <div class="biodata-value text-success d-flex align-items-center" style="font-size: 0.73rem;">
                                            <i class="fa fa-check-circle mr-1"></i> Berkas Siap
                                        </div>
                                    <?php else: ?>
                                        <div class="biodata-value text-secondary d-flex align-items-center" style="font-size: 0.73rem;">
                                            <i class="fa fa-circle-exclamation mr-1"></i> Belum Diunggah
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="flex-shrink-0 ml-2">
                                <?php if ($isUploaded): ?>
                                    <a href="<?= base_url('student/files/view/document_file/' . esc($fileName)) ?>"
                                        target="_blank"
                                        class="btn btn-xs btn-custom-primary px-3 shadow-xs"
                                        title="Lihat Dokumen">
                                        <i class="fa fa-eye mr-1 pb-1" style="font-size: 0.7rem;"></i> Lihat
                                    </a>
                                <?php else: ?>
                                    <span class="badge badge-soft-danger px-2 py-1">
                                        Kosong
                                    </span>
                                <?php endif; ?>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ($verificationStatus === 'unsubmitted'):  ?>

            <div class="card-modern p-4 mb-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <div class="mb-3 mb-md-0 mr-md-3">
                        <h5 class="font-weight-bold text-dark mb-1">Finalisasi Verifikasi Pendaftaran</h5>
                        <p class="text-muted text-small-c mb-0">Pastikan seluruh data profil, foto, mata kuliah, dan berkas di atas sudah sesuai sebelum menekan tombol kirim.</p>
                    </div>
                    <div class="flex-shrink-0">
                        <?php if ($canSubmitVerification): ?>
                            <button type="button" class="btn btn-custom-primary px-4 py-2 font-weight-bold shadow-xs btn-block btn-md-auto" data-toggle="modal" data-target="#modalConfirmVerification">
                                <i class="fa fa-paper-plane mr-2"></i> Kirim Verifikasi Pendaftaran
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn btn-warning text-dark px-4 py-2 font-weight-bold shadow-xs btn-block btn-md-auto" onclick="showIncompleteNotice()">
                                <i class="fa fa-exclamation-triangle mr-2"></i> Lengkapi Persyaratan
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="modal fade modal-modern" id="modalConfirmVerification" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content border-0 shadow-lg">

                        <div class="modal-header text-white">
                            <h5 class="modal-title font-weight-bold d-flex align-items-center" id="modalLabel" style="font-size: 1rem;">
                                <i class="fa fa-shield-halved mr-2"></i> Konfirmasi Finalisasi Data
                            </h5>
                            <button type="button" class="close close-btn-c text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body p-4 text-center">
                            <div class="modal-warning-icon-wrapper">
                                <i class="fa fa-triangle-exclamation"></i>
                            </div>

                            <h5 class="font-weight-bold text-dark mb-2">Apakah Data Sudah Sesuai?</h5>
                            <p class="text-muted text-small-c mb-3">
                                Mohon periksa kembali seluruh isian formulir dan kelengkapan berkas Anda sebelum melanjutkan.
                            </p>

                            <div class="modal-alert-box">
                                <div class="d-flex align-items-start">
                                    <i class="fa fa-circle-info text-primary mr-2 mt-1" style="font-size: 0.85rem;"></i>
                                    <p class="text-xs-c text-secondary mb-0" style="line-height: 1.45;">
                                        Setelah dikirim, status verifikasi Anda akan menjadi <strong class="text-primary">Selesai</strong> dan Anda <strong class="text-danger">tidak dapat lagi memperbarui data</strong> pada sistem.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer bg-light border-top-0 px-4 py-3 d-flex justify-content-end" style="gap: 8px;">
                            <button type="button" class="btn btn-light border px-4 font-weight-semibold" data-dismiss="modal">
                                Batal
                            </button>
                            <form action="<?= base_url('student/verification/submit') ?>" method="POST" class="m-0">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-custom-primary px-4 font-weight-bold shadow-xs">
                                    <i class="fa fa-check mr-1.5"></i> Ya, Kirim Sekarang
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>


<?= $this->endSection() ?>