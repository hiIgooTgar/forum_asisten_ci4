<?= $this->extend('layouts/student') ?>

<?= $this->section('content') ?>
<?php
$appProfile    = $appProfile ?? (object)[];
$appName       = $appProfile->application_name ?? 'Sistem Asisten Praktikum';
$appTitle      = $appProfile->company_application_main ?? 'Lab Center';;

$logoImg = (!empty($appProfile->logo) && file_exists(FCPATH . 'uploads/logo/' . $appProfile->logo))
    ? base_url('uploads/logo/' . $appProfile->logo)
    : base_url('assets/images/logo/' . ($appProfile->logo ?? 'logo-fa.png'));

$studentData  = (isset($student) && is_object($student)) ? $student : null;
$docData      = (isset($documents) && is_object($documents)) ? $documents : null;
$eventData    = (isset($active_event) && is_array($active_event)) ? $active_event : null;
$percentage   = isset($completion_percentage) ? (int)$completion_percentage : 0;
$studentName  = esc($studentData->full_name ?? session()->get('full_name') ?? 'Mahasiswa');

$canVerify = $canVerify ?? false;

$eventData      = (isset($active_event) && is_array($active_event)) ? $active_event : null;
$computedStatus = $eventData['computed_status'] ?? 'CLOSED';
$targetTime     = $eventData['target_time'] ?? null;
$eventName      = $eventData['event_name'] ?? 'Pendaftaran Asisten Praktikum';
$badgeText      = $eventData['badge_text'] ?? 'Tutup';
$badgeColor     = $eventData['badge_color'] ?? 'bg-danger text-white';
$statusLabel    = $eventData['status_label'] ?? 'Pendaftaran Ditutup';
$isExtraTime    = (int)($eventData['is_extra_time'] ?? 0) === 1;
?>

<div class="app-title shadow-sm bg-white rounded p-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
    <div>
        <h1 class="h4 font-weight-bold mb-1 text-dark d-flex align-items-center">
            <i class="fa fa-dashboard mr-2 text-primary"></i> Forum Asisten AMIKOM Purwokerto
        </h1>
        <p class="mb-0">Panel Informasi &amp; Layanan Calon Asisten Praktikum</p>
    </div>
    <ul class="app-breadcrumb breadcrumb side bg-transparent p-0 m-0 mt-2 mt-sm-0">
        <li class="breadcrumb-item"><a href="<?= base_url('student/dashboard'); ?>"><i class="fa fa-home text-muted"></i></a></li>
        <li class="breadcrumb-item active text-primary font-weight-semibold">Dashboard</li>
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
                        <h4 class="mb-0 text-white font-weight-bold">
                            <?= esc($eventName) ?>
                        </h4>
                    </div>
                    <p class="mb-0 text-white-50 text-small-c" style="line-height: 1.6;">
                        <?php if ($eventData): ?>
                            <?= esc($eventData['description'] ?? 'Periode pendaftaran calon asisten praktikum.') ?>
                        <?php else: ?>
                            Saat ini belum ada periode pendaftaran aktif yang dibuka.
                        <?php endif; ?>
                    </p>
                    <?php if ($isExtraTime): ?>
                        <small class="badge badge-light text-dark font-weight-bold py-1 px-2" style="font-size: 0.65rem; margin-top: 0.5rem;">Perpanjangan</small>
                    <?php endif; ?>
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

<?php if ($canVerify && ($student->verification_status ?? '') !== 'completed'): ?>
    <div class="alert alert-primary border-0 shadow-sm p-3 p-md-4 mb-4 rounded-lg" style="background-color: #ffffff; border-left: 4px solid var(--primary) !important;">
        <div class="d-flex align-items-start" style="gap: 0.9rem">
            <div class="text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; background-color: rgba(10, 36, 129, 0.15);">
                <i class="fa fa-check-circle fa-lg"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="font-weight-bold text-primary mb-1" style="font-size: 0.95rem;">
                    Berkas &amp; Persyaratan Lengkap!
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
    <div class="col-12 mb-4 mb-md-1">
        <div class="tile shadow-sm p-3 p-md-4 bg-white rounded border-left-primary">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
                <div class="mb-2 mb-sm-0">
                    <h2 class="h5 font-weight-bold text-dark mb-1">
                        Selamat Datang, <span class="text-primary"><?= $studentName; ?></span>!
                    </h2>
                    <p class="text-muted text-small-c mb-0">
                        Selamat beraktivitas di sistem <strong>Forum Asisten Universitas Amikom Purwokerto</strong>. Silakan periksa kelengkapan berkas &amp; data Anda.
                    </p>
                </div>
                <div class="mt-2 mt-sm-0">
                    <span class="badge badge-soft-primary px-3 py-2 font-weight-normal rounded-pill d-inline-flex align-items-center">
                        <i class="fa fa-university mr-2 text-primary"></i> Universitas Amikom Purwokerto
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="tile shadow-sm h-100 d-flex flex-column justify-content-between p-3 p-md-4 bg-white border rounded">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                    <h4 class="tile-title text-dark font-weight-bold mb-0" style="font-size: 1rem;">
                        <i class="fa fa-user-check text-primary mr-2"></i>Kelengkapan Profil
                    </h4>
                    <span class="badge badge-light text-muted">Persentase</span>
                </div>

                <div class="tile-body d-flex flex-column align-items-center justify-content-center text-center pt-3 my-4">
                    <h2 class="display-4 font-weight-bold text-primary mb-2 d-flex align-items-center justify-content-center">
                        <?= $percentage ?>%
                    </h2>

                    <?php
                    $barColor = 'bg-danger';
                    if ($percentage >= 80) {
                        $barColor = 'bg-success';
                    } elseif ($percentage >= 60) {
                        $barColor = 'bg-warning';
                    } elseif ($percentage >= 40) {
                        $barColor = 'bg-orange';
                    }
                    ?>

                    <div class="progress w-100 mb-3" style="height: 10px; border-radius: 5px; background-color: #e9ecef;">
                        <div class="progress-bar <?= $barColor; ?> progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?= $percentage ?>%;" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>

                    <p class="small text-muted mb-0" style="font-size: 0.825rem; line-height: 1.5;">
                        Persentase diakumulasikan secara otomatis berdasarkan kelengkapan <strong>Data Diri (maks. 70%)</strong> serta <strong>Dokumen Pendukung (maks. 30%)</strong>.
                    </p>
                </div>
            </div>

            <div class="tile-footer border-top-0 p-0 text-center mt-3">
                <a href="<?= base_url('student/profile') ?>" class="btn btn-sm btn-primary btn-block font-weight-bold py-2 shadow-xs">
                    <i class="fa fa-edit mr-1"></i> Lengkapi Profil Saya
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 mb-4">
        <div class="tile shadow-sm h-100 d-flex flex-column justify-content-between p-3 p-md-4 bg-white border rounded">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                    <h4 class="tile-title text-dark font-weight-bold mb-0" style="font-size: 1rem;">
                        <i class="fa fa-id-card text-primary mr-2"></i>Informasi Akademik
                    </h4>
                    <span class="badge badge-light text-muted">Data Diri</span>
                </div>
                <div class="tile-body">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted"><i class="fa fa-user mr-1"></i> NIM</span>
                            <strong class="text-dark"><?= esc($studentData->student_number ?? '-') ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted"><i class="fa fa-building mr-1"></i> Fakultas</span>
                            <strong class="text-dark text-right text-truncate" style="max-width: 60%;" title="<?= esc($studentData->faculty_name ?? '-') ?>">
                                <?= esc($studentData->faculty_name ?? '-') ?>
                            </strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted"><i class="fa fa-graduation-cap mr-1"></i> Program Studi</span>
                            <strong class="text-dark text-right text-truncate" style="max-width: 60%;" title="<?= esc($studentData->program_name ?? '-') ?>">
                                <?= esc($studentData->program_name ?? '-') ?>
                            </strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted"><i class="fa fa-venus-mars mr-1"></i> Gender</span>
                            <strong class="text-dark">
                                <?php
                                $gender = strtolower($studentData->gender ?? '');
                                if ($gender === 'male' || $gender === 'l') echo 'Laki-laki';
                                elseif ($gender === 'female' || $gender === 'p') echo 'Perempuan';
                                else echo '-';
                                ?>
                            </strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-muted"><i class="fa fa-star mr-1"></i> IPK Terakhir</span>
                            <span class="badge badge-primary font-weight-bold px-2 py-1" style="font-size: 0.7rem;">
                                <?= esc(number_format((float)($studentData->gpa ?? 0), 2)) ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tile-footer border-top-0 p-0 text-center mt-3">
                <a href="<?= base_url('student/profile') ?>" class="btn btn-sm btn-outline-primary btn-block font-weight-bold py-2">
                    <i class="fa fa-user-edit mr-1"></i> Edit Akademik
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-md-12 mb-4">
        <div class="tile shadow-sm h-100 d-flex flex-column justify-content-between p-3 p-md-4 bg-white border rounded">
            <div>
                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                    <h4 class="tile-title text-dark font-weight-bold mb-0" style="font-size: 1rem;">
                        <i class="fa fa-folder text-primary mr-2"></i>Status Dokumen
                    </h4>
                    <span class="badge badge-light text-muted">Berkas</span>
                </div>
                <div class="tile-body">
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-truncate mr-2"><i class="fa fa-file-text-o text-muted mr-1"></i> KTM / Kartu Mahasiswa</span>
                            <?= (!empty($docData->student_card_file)) ? '<span class="badge badge-success px-2 py-1"><i class="fa fa-check mr-1"></i> Sudah</span>' : '<span class="badge badge-secondary px-2 py-1">Belum</span>' ?>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-truncate mr-2"><i class="fa fa-file-text-o text-muted mr-1"></i> Surat Lamaran</span>
                            <?= (!empty($docData->application_letter_file)) ? '<span class="badge badge-success px-2 py-1"><i class="fa fa-check mr-1"></i> Sudah</span>' : '<span class="badge badge-secondary px-2 py-1">Belum</span>' ?>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-truncate mr-2"><i class="fa fa-file-text-o text-muted mr-1"></i> Curriculum Vitae (CV)</span>
                            <?= (!empty($docData->cv_file)) ? '<span class="badge badge-success px-2 py-1"><i class="fa fa-check mr-1"></i> Sudah</span>' : '<span class="badge badge-secondary px-2 py-1">Belum</span>' ?>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-truncate mr-2"><i class="fa fa-file-text-o text-muted mr-1"></i> Transkrip Nilai</span>
                            <?= (!empty($docData->latest_transcript_file)) ? '<span class="badge badge-success px-2 py-1"><i class="fa fa-check mr-1"></i> Sudah</span>' : '<span class="badge badge-secondary px-2 py-1">Belum</span>' ?>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-truncate mr-2"><i class="fa fa-file-text-o text-muted mr-1"></i> Surat Pernyataan</span>
                            <?= (!empty($docData->statement_letter_file)) ? '<span class="badge badge-success px-2 py-1"><i class="fa fa-check mr-1"></i> Sudah</span>' : '<span class="badge badge-secondary px-2 py-1">Belum</span>' ?>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                            <span class="text-truncate mr-2"><i class="fa fa-file-text-o text-muted mr-1"></i> Formulir Pendaftaran</span>
                            <?= (!empty($docData->registration_form_file)) ? '<span class="badge badge-success px-2 py-1"><i class="fa fa-check mr-1"></i> Sudah</span>' : '<span class="badge badge-secondary px-2 py-1">Belum</span>' ?>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tile-footer border-top-0 p-0 text-center mt-3">
                <a href="<?= base_url('student/documents') ?>" class="btn btn-sm btn-outline-primary btn-block font-weight-bold py-2">
                    <i class="fa fa-folder-open mr-1"></i> Kelola Dokumen
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document.body).ready(function() {
        const targetTimeMs = <?= !empty($active_event['target_time_ms']) ? $active_event['target_time_ms'] : 'null' ?>;
        let serverTimeMs = <?= !empty($active_event['server_time_ms']) ? $active_event['server_time_ms'] : 'null' ?>;

        if (targetTimeMs && serverTimeMs !== 'CLOSED') {
            const timerInterval = setInterval(function() {
                serverTimeMs += 1000;
                const distance = targetTimeMs - serverTimeMs;

                if (distance <= 0) {
                    clearInterval(timerInterval);
                    window.location.reload();
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