<?= $this->extend('layouts/student') ?>

<?= $this->section('content') ?>
<?php
$rawPhone  = $appProfile->phone_number ?? '-';
$waNumber  = preg_replace('/[^0-9]/', '', $rawPhone);
$waUrl     = "https://wa.me/" . $waNumber;

$logoImg = (!empty($appProfile->logo) && file_exists(FCPATH . 'uploads/logo/' . $appProfile->logo))
    ? base_url('uploads/logo/' . $appProfile->logo)
    : base_url('assets/images/logo/' . ($appProfile->logo ?? 'logo-fa.png'));

$canVerify         = $canVerify ?? false;
$faculties     = $faculties ?? [];
$studyPrograms = $studyPrograms ?? [];
$classGroups   = $classGroups ?? [];

if (!isset($student) || empty($student)) {
    $student = (object) [
        'student_number'   => '',
        'full_name'        => '',
        'email'            => '',
        'phone_number'     => '',
        'faculty_params'       => '',
        'study_program_params' => '',
        'class_params'         => '',
        'place_of_birth'   => '',
        'date_of_birth'    => '',
        'gender'           => '',
        'province'         => '',
        'regency'          => '',
        'subdistrict'      => '',
        'village'          => '',
        'address'          => '',
        'gpa'              => '',
        'profile'          => '',
        'program_name'     => ''
    ];
}

$activeTab  = $activeTab ?? 'biodata';
$profileImg = (!empty($student->profile) && file_exists(FCPATH . 'uploads/profile_student/' . $student->profile))
    ? base_url('uploads/profile_student/' . $student->profile)
    : base_url('assets/images/profile/profile-default.png');


$eventData    = (isset($active_event) && is_array($active_event)) ? $active_event : null;
$computedStatus = $eventData['computed_status'] ?? 'CLOSED';
$targetTime     = $eventData['target_time'] ?? null;
$eventName      = $eventData['event_name'] ?? 'Pendaftaran Asisten Praktikum';
$badgeText      = $eventData['badge_text'] ?? 'Tutup';
$badgeColor     = $eventData['badge_color'] ?? 'bg-danger text-white';
$statusLabel    = $eventData['status_label'] ?? 'Pendaftaran Ditutup';
?>
<div class="app-title shadow-sm bg-white rounded p-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
    <div>
        <h1 class="h4 font-weight-bold mb-1 text-dark d-flex align-items-center">
            <i class="fa fa-user-circle text-primary mr-2"></i> Pengaturan Profil Mahasiswa
        </h1>
        <p class="mb-0">Kelola informasi biodata pribadi, data akademik, dan pas foto resmi Anda</p>
    </div>
    <ul class="app-breadcrumb breadcrumb side bg-transparent p-0 m-0 mt-2 mt-sm-0">
        <li class="breadcrumb-item"><a href="<?= base_url('student/dashboard'); ?>"><i class="fa fa-home text-muted"></i></a></li>
        <li class="breadcrumb-item active text-primary font-weight-semibold">Profil Saya</li>
    </ul>
</div>



<div class="row">
    <div class="col-12">
        <div class="tile tile-brand shadow-sm p-4 text-white rounded position-relative overflow-hidden mb-4" style="background: linear-gradient(135deg, var(--primary) 0%, var(--color-primary-combine) 100%);">
            <div class="row align-items-center z-index-1">
                <div class="col-lg-7 col-md-12 mb-3 mb-lg-0">
                    <div class="d-flex align-items-start justify-content-start mb-1 flex-column" style="gap: 8px;">
                        <span class="badge <?= $badgeColor ?> font-weight-bold px-2 py-1 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            <?= esc($badgeText) ?>
                        </span>
                        <h3 class="mb-0 text-white font-weight-bold h4"><?= esc($eventName) ?></h3>
                    </div>
                    <p class="mb-0 text-white-50 text-small-c" style="line-height: 1.6;">
                        <?php if ($eventData): ?>
                            <?= esc($eventData['description'] ?? 'Periode pendaftaran calon asisten praktikum.') ?>
                        <?php else: ?>
                            Saat ini belum ada periode pendaftaran aktif yang dibuka.
                        <?php endif; ?>
                    </p>
                </div>

                <div class="col-lg-5 col-md-12 text-lg-right d-flex align-items-center justify-content-center align-items-sm-end justify-content-sm-end">
                    <?php if ($targetTime && $computedStatus !== 'CLOSED'): ?>
                        <div class="d-inline-block text-lg-right text-center">
                            <span class="d-block small text-white-50 mb-1 font-weight-semibold"><?= esc($statusLabel) ?></span>
                            <div class="d-flex align-items-center justify-content-center justify-content-lg-end" style="gap: 6px;" id="countdown-board">
                                <div class="timer-card">
                                    <div class="timer-num" id="cd-days">00</div>
                                    <div class="timer-unit">Hari</div>
                                </div>
                                <div class="timer-card">
                                    <div class="timer-num" id="cd-hours">00</div>
                                    <div class="timer-unit">Jam</div>
                                </div>
                                <div class="timer-card">
                                    <div class="timer-num" id="cd-minutes">00</div>
                                    <div class="timer-unit">Menit</div>
                                </div>
                                <div class="timer-card">
                                    <div class="timer-num" id="cd-seconds">00</div>
                                    <div class="timer-unit">Detik</div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <button class="btn btn-light btn-block btn-sm-inline px-4 py-2 font-weight-semibold text-muted" disabled>
                            <i class="fa fa-lock mr-1"></i> Pendaftaran Ditutup
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-fullscreen-closed p-0" id="modalClosedNotice" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content d-flex align-items-center justify-content-center text-center p-4">
            <div class="container my-auto" style="max-width: 680px;">
                <div class="mb-4">
                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white-10 mb-3" style="width: 80px; height: 80px; background: rgba(255,255,255, 1);">
                        <img style="width: 60px; 60px" src="<?= $logoImg; ?>"
                            alt="<?= esc($appProfile->application_name ?? 'Logo FA'); ?>"
                            class="verified-logo"
                            onerror="this.onerror=null; this.src='<?= base_url('assets/images/profile/profile-default.png'); ?>';">
                    </div>
                    <h3 class="font-weight-bold text-white mb-2">Pendaftaran Telah Ditutup</h3>
                    <p class="text-white-50 lead" style="font-size: 0.95rem;">
                        Mohon maaf, periode <strong><?= esc($eventName) ?></strong> saat ini telah resmi ditutup atau belum dibuka.
                    </p>
                </div>

                <div class="card bg-white text-dark shadow-lg border-0 rounded-lg p-4 mb-4">
                    <h6 class="font-weight-bold text-primary mb-2">Informasi Penting</h6>
                    <p class="text-muted text-small-c2 mb-0" style="line-height: 1.6;">
                        Proses pendaftaran dan unggah berkas tidak lagi menerima inputan baru. Bagi Anda yang telah menyelesaikan verifikasi, silakan pantau secara berkala halaman pengumuman hasil seleksi.
                    </p>
                </div>

                <div class="d-flex flex-column flex-sm-row justify-content-center" style="gap: 1rem;">
                    <a href="<?= base_url('student/announcement'); ?>" class="btn btn-warning font-weight-bold px-4 py-2.5 shadow rounded">
                        <i class="fa fa-bullhorn mr-1"></i> Cek Pengumuman Hasil
                    </a>
                    <a href="<?= base_url('student/dashboard'); ?>" class="btn btn-outline-light font-weight-bold px-4 py-2.5 shadow rounded">
                        <i class="fa fa-dashboard mr-1"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if ($student->verification_status === "completed"): ?>
    <div class="card verified-card p-4 p-md-5 mb-4 text-center">
        <div class="verified-card-bg-accent"></div>

        <div class="card-body p-0 position-relative" style="z-index: 1;">
            <div class="mb-3">
                <div class="logo-container mb-3">
                    <img src="<?= $logoImg; ?>"
                        alt="<?= esc($appProfile->application_name ?? 'Logo FA'); ?>"
                        class="verified-logo"
                        onerror="this.onerror=null; this.src='<?= base_url('assets/images/profile/profile-default.png'); ?>';">
                    <span class="status-check-badge" title="Terverifikasi">
                        <i class="fa fa-check"></i>
                    </span>
                </div>
                <div>
                    <span class="verified-status-tag">
                        <i class="fa fa-lock text-primary" style="font-size: 0.63rem;"></i> Data Terkunci
                    </span>
                </div>
            </div>

            <h5 class="font-weight-bold text-primary mb-2" style="line-height: 1.65;">
                Terima Kasih! Pendaftaran Anda Telah Terverifikasi
            </h5>

            <p class="text-small-c text-secondary-c mx-auto mb-4" style="max-width: 640px; line-height: 1.65;">
                Seluruh berkas dokumen administrasi dan pendaftaran mata kuliah Anda telah selesai diverifikasi secara permanen. Data pendaftaran asisten praktikum Anda saat ini resmi disimpan dan tidak dapat diubah kembali secara mandiri.
            </p>

            <div class="sdm-help-box text-left text-sm-center">
                <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-4">
                    <div class="d-flex align-items-start align-items-sm-center text-left mb-2 mb-sm-0">
                        <div class="mr-3 mt-2 mt-sm-0 text-primary">
                            <i class="fa fa-info-circle fa-2x" style="color: #0a2481;"></i>
                        </div>
                        <div>
                            <h6 class="font-weight-bold text-dark mb-1 text-small-c2">
                                Menemukan Kekeliruan / Kesalahan Data?
                            </h6>
                            <span class="text-muted text-xs-c" style="line-height: 1.5;">
                                Apabila terdapat kesalahan upload dokumen atau penyesuaian mata kuliah, silakan hubungi Nomor SDM <strong class="text-primary">+<?= esc($appProfile->phone_number ?? '-'); ?></strong>.
                            </span>
                        </div>
                    </div>

                    <div class="mt-2 mt-sm-0 flex-shrink-0">
                        <a href="<?= $waUrl; ?>"
                            target="_blank"
                            class="btn-sdm-contact">
                            <i class="fa fa-phone fa-lg"></i>
                            <span>Hubungi SDM</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>

    <?php if ($canVerify && ($student->verification_status ?? '') !== 'completed'): ?>
        <div class="alert alert-primary border-0 shadow-sm p-3 p-md-4 mb-4 rounded-lg" style="background-color: #ffffff; border-left: 4px solid #0a2481 !important;">
            <div class="d-flex align-items-start" style="gap: 0.9rem">
                <div class="text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; background-color: rgba(10, 36, 129, 0.15);">
                    <i class="fa fa-check-circle fa-lg"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="font-weight-bold text-primary mb-1" style="font-size: 0.95rem;">
                        Berkas & Persyaratan Lengkap!
                    </h6>
                    <p class="text-dark text-small-c mb-2" style="line-height: 1.5;">
                        Seluruh profil, mata kuliah pilihan, dan 6 dokumen persyaratan Anda telah lengkap. Silakan lakukan verifikasi akhir pendaftaran Anda sekarang.
                    </p>
                    <a href="<?= base_url('student/verification'); ?>" class="btn btn-sm btn-primary px-3 font-weight-bold shadow-sm rounded">
                        <i class="fa fa-shield-alt mr-1"></i> Verifikasi Pendaftaran Sekarang
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-xl-3 col-lg-4 col-12 mb-4">
            <div class="tile p-3 bg-white shadow-sm rounded border-0 h-auto">
                <div class="tile-header-custom d-flex align-items-center justify-content-between">
                    <h6 class="font-weight-bold mb-0 text-white small text-uppercase" style="letter-spacing: 0.5px;">
                        <i class="fa fa-compass mr-1"></i> Navigasi Profil
                    </h6>
                    <?php
                    $isActive = ($student->status_account ?? '') === 'active';
                    $borderClass = $isActive ? 'border-warning text-warning' : 'border-warning text-warning';
                    $statusText  = $isActive ? 'Active' : 'Inactive';
                    ?>

                    <span class="badge bg-transparent border <?= $borderClass ?> font-weight-normal px-2 py-1" style="font-size: 0.68rem; letter-spacing: 0.3px;">
                        <i class="fa fa-circle mr-1" style="font-size: 0.5rem; vertical-align: middle;"></i><?= $statusText ?>
                    </span>
                </div>

                <div class="vertical-stepper">
                    <div class="stepper-item <?= $activeTab === 'biodata' ? 'active' : '' ?>">
                        <div class="stepper-icon">
                            <?= $activeTab === 'biodata' ? '<i style="margin-top: 2px" class="fa fa-check"></i>' : '<i style="margin-top: 1px" class="fa fa-circle"></i>' ?>
                        </div>
                        <div class="stepper-content">
                            <span class="stepper-title">Biodata Diri</span>
                            <span class="stepper-desc d-block text-truncate">Data identitas &amp; akademik</span>
                        </div>
                    </div>

                    <div class="stepper-line"></div>

                    <div class="stepper-item <?= $activeTab === 'photo' ? 'active' : '' ?>">
                        <div class="stepper-icon">
                            <?= $activeTab === 'photo' ? '<i style="margin-top: 2px" class="fa fa-check"></i>' : '<i style="margin-top: 1px" class="fa fa-circle"></i>' ?>
                        </div>
                        <div class="stepper-content">
                            <span class="stepper-title">Pas Foto Profil</span>
                            <span class="stepper-desc d-block text-truncate">Unggah pas foto formal</span>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="text-center p-2">
                    <img src="<?= $profileImg ?>" class="rounded-circle img-thumbnail mb-2 shadow-xs" style="width: 85px; height: 85px; object-fit: cover;">
                    <h6 class="font-weight-bold text-dark mb-0 text-truncate"><?= esc($student->full_name ?: '-') ?></h6>
                    <span class="text-small-c mt-1 d-block text-truncate"><?= esc($student->student_number ?: '-') ?></span>
                    <span class="badge badge-soft-primary mt-2 px-3 py-1 text-wrap"><?= esc($student->program_name ?: 'Mahasiswa') ?></span>
                    <div class="clock-widget-container mt-3 p-2 text-center">
                        <div id="realtime-day-date" class="text-muted small font-weight-bold" style="font-size: 0.8rem;">
                            --
                        </div>
                        <div id="realtime-clock" class="clock-time-display font-weight-bold mt-1" style="font-size: 0.8rem;">
                            00:00:00 WIB
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9 col-lg-8 col-12">
            <div class="tile p-3 p-md-4 bg-white shadow-sm rounded border-0">

                <ul class="nav nav-pills custom-pills mb-4 border-bottom pb-3 flex-column flex-sm-row gap-2" id="profileTabs">
                    <li class="nav-item">
                        <a class="nav-link <?= $activeTab === 'biodata' ? 'active' : '' ?> font-weight-bold text-center text-sm-left" href="<?= base_url('student/profile?tab=biodata') ?>">
                            <i class="fa fa-id-card mr-2"></i>1. Biodata Diri &amp; Akademik
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $activeTab === 'photo' ? 'active' : '' ?> font-weight-bold text-center text-sm-left" href="<?= base_url('student/profile?tab=photo') ?>">
                            <i class="fa fa-camera mr-2"></i>2. Pas Foto Profil
                        </a>
                    </li>
                </ul>

                <div class="tab-content">

                    <div class="tab-pane fade <?= $activeTab === 'biodata' ? 'show active' : '' ?>">
                        <form action="<?= base_url('student/profile/update-biodata') ?>" method="POST" novalidate>
                            <?= csrf_field() ?>

                            <div class="p-3 bg-soft-primary border-left-primary mb-4">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="fa fa-clipboard-list text-primary mr-2"></i>
                                    <h6 class="font-weight-bold mb-0 text-dark">Ketentuan Pengisian Data</h6>
                                </div>
                                <small class="text-xs-c text-secondary-c d-block">
                                    Mohon isi seluruh data bertanda (<span class="text-danger">*</span>) sesuai dengan dokumen resmi. Pastikan pemilihan Wilayah Domisili (Provinsi hingga Kelurahan) dan informasi akademik telah sesuai sebelum menekan tombol <strong class="text-primary">Simpan Biodata</strong>.
                                </small>
                            </div>

                            <h5 class="font-weight-bold mb-3 text-dark border-left-primary-title pl-2">Informasi Akun &amp; Akademik</h5>

                            <div class="row">
                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="control-label font-weight-bold">NIM</label>
                                    <input class="form-control bg-light" type="text" value="<?= esc($student->student_number) ?>" readonly disabled>
                                </div>
                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="control-label font-weight-bold">Email</label>
                                    <input class="form-control bg-light" type="email" value="<?= esc($student->email) ?>" readonly disabled>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="control-label font-weight-bold">Fakultas <span class="text-danger">*</span></label>
                                    <select class="form-control select2 <?= session('errors.faculty_params') ? 'is-invalid' : '' ?>" id="faculty_params" name="faculty_params" style="width: 100%;">
                                        <option value="" disabled <?= old('faculty_params', $student->faculty_params) ? '' : 'selected' ?>>-- Pilih Fakultas --</option>
                                        <?php foreach ($faculties as $faculty): ?>
                                            <option value="<?= $faculty->faculty_main ?>" <?= old('faculty_params', $student->faculty_params) == $faculty->faculty_main ? 'selected' : '' ?>>
                                                <?= esc($faculty->faculty_name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (session('errors.faculty_params')): ?>
                                        <span class="invalid-feedback d-block"><?= session('errors.faculty_params') ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="control-label font-weight-bold">Program Studi <span class="text-danger">*</span></label>
                                    <select class="form-control select2 <?= session('errors.study_program_params') ? 'is-invalid' : '' ?>"
                                        id="study_program_params"
                                        name="study_program_params"
                                        data-selected="<?= esc(old('study_program_params', $student->study_program_params)) ?>"
                                        style="width: 100%;"
                                        <?= empty($studyPrograms) ? 'disabled' : '' ?>>
                                        <option value="" disabled <?= old('study_program_params', $student->study_program_params) ? '' : 'selected' ?>>-- Pilih Program Studi --</option>
                                        <?php foreach ($studyPrograms as $program): ?>
                                            <option value="<?= $program->program_main ?>" <?= old('study_program_params', $student->study_program_params) == $program->program_main ? 'selected' : '' ?>>
                                                <?= esc($program->program_name) ?> (<?= esc($program->degree_level ?? 'S1') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (session('errors.study_program_params')): ?>
                                        <span class="invalid-feedback d-block"><?= session('errors.study_program_params') ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="control-label font-weight-bold">Kelas Group <span class="text-danger">*</span></label>
                                    <select class="form-control select2 <?= session('errors.class_params') ? 'is-invalid' : '' ?>"
                                        id="class_params"
                                        name="class_params"
                                        data-selected="<?= esc(old('class_params', $student->class_params)) ?>"
                                        style="width: 100%;"
                                        <?= empty($classGroups) ? 'disabled' : '' ?>>
                                        <option value="" disabled <?= old('class_params', $student->class_params) ? '' : 'selected' ?>>-- Pilih Kelas --</option>
                                        <?php foreach ($classGroups as $class): ?>
                                            <option value="<?= $class->class_main ?>" <?= old('class_params', $student->class_params) == $class->class_main ? 'selected' : '' ?>>
                                                <?= esc($class->class_name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (session('errors.class_params')): ?>
                                        <span class="invalid-feedback d-block"><?= session('errors.class_params') ?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="control-label font-weight-bold">IPK Terakhir <span class="text-danger">*</span></label>
                                    <input class="form-control <?= session('errors.gpa') ? 'is-invalid' : '' ?>"
                                        type="number"
                                        step="0.01"
                                        min="0.10"
                                        max="4.00"
                                        id="gpa_input"
                                        name="gpa"
                                        value="<?= old('gpa', $student->gpa) ?>"
                                        placeholder="Contoh: 3.75">
                                    <?php if (session('errors.gpa')): ?>
                                        <span class="invalid-feedback d-block"><?= session('errors.gpa') ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h5 class="font-weight-bold mb-3 text-dark border-left-primary-title pl-2">Data Diri Pribadi</h5>

                            <div class="row">
                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="control-label font-weight-bold">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input class="form-control <?= session('errors.full_name') ? 'is-invalid' : '' ?>" type="text" name="full_name" value="<?= old('full_name', $student->full_name) ?>">
                                    <?php if (session('errors.full_name')): ?>
                                        <span class="invalid-feedback d-block"><?= session('errors.full_name') ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="control-label font-weight-bold">Nomor WhatsApp / HP <span class="text-danger">*</span></label>
                                    <input class="form-control <?= session('errors.phone_number') ? 'is-invalid' : '' ?>"
                                        type="text"
                                        id="phone_number_input"
                                        autocomplete="off"
                                        name="phone_number"
                                        value="<?= old('phone_number', $student->phone_number) ?>"
                                        placeholder="0811 - 1111 - 2222 - 3333">
                                    <?php if (session('errors.phone_number')): ?>
                                        <span class="invalid-feedback d-block"><?= session('errors.phone_number') ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="control-label font-weight-bold">Tempat Lahir <span class="text-danger">*</span></label>
                                    <input class="form-control <?= session('errors.place_of_birth') ? 'is-invalid' : '' ?>" type="text" name="place_of_birth" value="<?= old('place_of_birth', $student->place_of_birth) ?>">
                                    <?php if (session('errors.place_of_birth')): ?>
                                        <span class="invalid-feedback d-block"><?= session('errors.place_of_birth') ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="control-label font-weight-bold">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input class="form-control <?= session('errors.date_of_birth') ? 'is-invalid' : '' ?>" type="date" name="date_of_birth" value="<?= old('date_of_birth', $student->date_of_birth) ?>">
                                    <?php if (session('errors.date_of_birth')): ?>
                                        <span class="invalid-feedback d-block"><?= session('errors.date_of_birth') ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-xl-6 col-lg-10 mb-3">
                                    <label class="control-label font-weight-bold d-block">Jenis Kelamin <span class="text-danger">*</span></label>

                                    <div class="gender-box-group">
                                        <label class="gender-box-option mb-0">
                                            <input type="radio"
                                                name="gender"
                                                value="male"
                                                <?= old('gender', $student->gender) === 'male' ? 'checked' : '' ?>>
                                            <div class="gender-box-card">
                                                <i class="fas fa-male"></i>
                                                <div class="desc">
                                                    <i class="fa fa-mars text-primary"></i>
                                                    <span>Laki-laki</span>
                                                </div>
                                            </div>
                                        </label>

                                        <label class="gender-box-option mb-0">
                                            <input type="radio"
                                                name="gender"
                                                value="female"
                                                <?= old('gender', $student->gender) === 'female' ? 'checked' : '' ?>>
                                            <div class="gender-box-card">
                                                <i class="fas fa-female"></i>
                                                <div class="desc">
                                                    <i class="fa fa-venus text-primary"></i>
                                                    <span>Perempuan</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <?php if (session('errors.gender')): ?>
                                        <span class="invalid-feedback d-block mt-1"><?= session('errors.gender') ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h5 class="font-weight-bold mb-3 text-dark border-left-primary-title pl-2">Alamat Domisili</h5>

                            <div class="row">
                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="control-label font-weight-bold">Provinsi <span class="text-danger">*</span></label>
                                    <select class="form-control select2 <?= session('errors.province') ? 'is-invalid' : '' ?>" id="select_province" name="province" data-initial="<?= esc(old('province', $student->province)) ?>">
                                        <option value="">-- Pilih Provinsi --</option>
                                    </select>
                                    <?php if (session('errors.province')): ?>
                                        <span class="invalid-feedback d-block"><?= session('errors.province') ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="control-label font-weight-bold">Kabupaten / Kota <span class="text-danger">*</span></label>
                                    <select class="form-control select2 <?= session('errors.regency') ? 'is-invalid' : '' ?>" id="select_regency" name="regency" data-initial="<?= esc(old('regency', $student->regency)) ?>" disabled>
                                        <option value="">-- Pilih Kabupaten/Kota --</option>
                                    </select>
                                    <?php if (session('errors.regency')): ?>
                                        <span class="invalid-feedback d-block"><?= session('errors.regency') ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="control-label font-weight-bold">Kecamatan <span class="text-danger">*</span></label>
                                    <select class="form-control select2 <?= session('errors.subdistrict') ? 'is-invalid' : '' ?>" id="select_district" name="subdistrict" data-initial="<?= esc(old('subdistrict', $student->subdistrict)) ?>" disabled>
                                        <option value="">-- Pilih Kecamatan --</option>
                                    </select>
                                    <?php if (session('errors.subdistrict')): ?>
                                        <span class="invalid-feedback d-block"><?= session('errors.subdistrict') ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12 col-lg-6 mb-3">
                                    <label class="control-label font-weight-bold">Kelurahan / Desa <span class="text-danger">*</span></label>
                                    <select class="form-control select2 <?= session('errors.village') ? 'is-invalid' : '' ?>" id="select_village" name="village" data-initial="<?= esc(old('village', $student->village)) ?>" disabled>
                                        <option value="">-- Pilih Kelurahan/Desa --</option>
                                    </select>
                                    <?php if (session('errors.village')): ?>
                                        <span class="invalid-feedback d-block"><?= session('errors.village') ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="control-label font-weight-bold">Alamat Lengkap <span class="text-danger">*</span></label>
                                    <textarea class="form-control <?= session('errors.address') ? 'is-invalid' : '' ?>" name="address" rows="3" placeholder="Jl. Contoh No. 123, RT/RW 01/02"><?= old('address', $student->address) ?></textarea>
                                    <?php if (session('errors.address')): ?>
                                        <span class="invalid-feedback d-block"><?= session('errors.address') ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="tile-footer d-flex flex-row align-items-center mt-4 pt-3 border-top gap-2">
                                <button class="btn btn-primary px-4 font-weight-bold shadow-sm w-sm-100 w-auto" type="submit">
                                    <i class="fa fa-check-circle mr-1"></i> Simpan Biodata
                                </button>
                                <a class="btn btn-secondary px-4 w-sm-100 w-auto" href="<?= base_url('student/profile?tab=biodata') ?>">Batal</a>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane fade <?= $activeTab === 'photo' ? 'show active' : '' ?>">
                        <form action="<?= base_url('student/profile/update-photo') ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>

                            <h5 class="font-weight-bold mb-2 text-dark border-left-primary-title pl-2">Unggah Pas Foto Resmi</h5>
                            <p class="text-small-c text-secondary-c mb-4">Gunakan pas foto formal berlatar belakang rapi sesuai dengan ketentuan kampus.</p>

                            <div class="alert alert-danger bg-danger-soft border border-danger text-danger p-3 p-md-4 rounded mb-4">
                                <h6 class="font-weight-bold mb-2 text-danger">
                                    <i class="fa fa-info-circle mr-1"></i> Ketentuan Foto:
                                </h6>
                                <ol class="pl-3 mb-3 text-xs-c" style="line-height: 1.6;">
                                    <li>Background foto berwarna <strong>merah</strong>.</li>
                                    <li>Rasio/Ukuran foto <strong>4x6</strong> (posisi portrait).</li>
                                    <li>Mengenakan kemeja, jas almamater, serta <strong>dasi</strong> (khusus laki-laki).</li>
                                    <li>Wajah menghadap lurus ke depan, terlihat jelas, dan tidak memakai kacamata hitam/aksesoris berlebih.</li>
                                    <li>Ukuran file foto maksimal <strong>1.5 MB</strong> (format JPG/JPEG/PNG).</li>
                                </ol>

                                <div class="border-top border-danger-subtle pt-3">
                                    <span class="font-weight-bold d-block mb-2 small text-danger">Contoh Pas Foto Resmi:</span>
                                    <div class="d-flex gap-2">
                                        <div class="text-center">
                                            <div class="p-1 bg-white border border-danger rounded shadow-sm">
                                                <img src="<?= base_url('assets/images/person/male.jpg') ?>"
                                                    alt="Contoh Laki-laki"
                                                    class="img-fluid rounded"
                                                    style="aspect-ratio: 4/6; object-fit: cover; width: 100px;">
                                            </div>
                                            <small class="d-block mt-1 font-weight-bold">Laki-laki</small>
                                        </div>
                                        <div class="text-center">
                                            <div class="p-1 bg-white border border-danger rounded shadow-sm">
                                                <img src="<?= base_url('assets/images/person/female.jpg') ?>"
                                                    alt="Contoh Perempuan"
                                                    class="img-fluid rounded"
                                                    style="aspect-ratio: 4/6; object-fit: cover; width: 100px;">
                                            </div>
                                            <small class="d-block mt-1 font-weight-bold">Perempuan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="file" name="profile_real" id="profile_real_input" class="d-none" accept="image/png, image/jpeg, image/jpg">

                            <?= view('components/image_cropper', [
                                'name'            => 'profile',
                                'label'           => 'Pilih File Foto Profil',
                                'required'        => false,
                                'aspectRatio'     => 1,
                                'previewSize'     => '140px',
                                'currentImage'    => $student->profile,
                                'defaultImage'    => 'assets/images/profile/profile-default.png',
                                'placeholderText' => 'Belum ada foto',
                                'cropWidth'       => 400,
                                'cropHeight'      => 600
                            ]) ?>

                            <?php if (session('errors.profile')): ?>
                                <span class="invalid-feedback d-block mt-2"><?= session('errors.profile') ?></span>
                            <?php endif; ?>

                            <div class="tile-footer d-flex flex-row align-items-center mt-4 pt-3 border-top gap-2">
                                <button class="btn btn-primary px-4 font-weight-bold shadow-sm w-sm-100 w-auto" type="submit">
                                    <i class="fa fa-upload mr-1"></i> Simpan Pas Foto
                                </button>
                                <a class="btn btn-secondary px-4 w-sm-100 w-auto" href="<?= base_url('student/profile?tab=photo') ?>">Batal</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document.body).ready(function() {
        const computedStatus = "<?= $computedStatus ?>";
        const targetTimeMs = <?= !empty($active_event['target_time_ms']) ? $active_event['target_time_ms'] : 'null' ?>;
        let serverTimeMs = <?= !empty($active_event['server_time_ms']) ? $active_event['server_time_ms'] : 'null' ?>;

        if (computedStatus === 'CLOSED' || !targetTimeMs) {
            $('#modalClosedNotice').modal('show');
        }

        if (targetTimeMs && serverTimeMs && computedStatus !== 'CLOSED') {
            const timerInterval = setInterval(function() {
                serverTimeMs += 1000;
                const distance = targetTimeMs - serverTimeMs;

                if (distance <= 0) {
                    clearInterval(timerInterval);
                    $('#modalClosedNotice').modal('show');
                    $('#countdown-board').html('<span class="badge badge-danger p-2">Waktu Telah Habis</span>');
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                $('#cd-days').text(String(days).padStart(2, '0'));
                $('#cd-hours').text(String(hours).padStart(2, '0'));
                $('#cd-minutes').text(String(minutes).padStart(2, '0'));
                $('#cd-seconds').text(String(seconds).padStart(2, '0'));
            }, 1000);
        }
    });
</script>
<?= $this->endSection() ?>