<?= $this->extend('layouts/student') ?>

<?= $this->section('content') ?>
<?php
$appProfile    = $appProfile ?? (object)[];
$eventSettings = $eventSettings ?? $event ?? [];

$appName       = $appProfile->application_name ?? 'Sistem Asisten Praktikum';
$appTitle      = $appProfile->company_application_main ?? 'Lab Center';
$rawPhone      = $appProfile->phone_number ?? '-';
$waNumber      = preg_replace('/[^0-9]/', '', $rawPhone);
$waSdmUrl      = !empty($waNumber) ? "https://wa.me/" . $waNumber : '#';
$appEmail      = $appProfile->email ?? '';
$appInstagram      = $appProfile->instagram_url ?? '';

$rawUrl = '';
if (is_array($eventSettings)) {
    $rawUrl = $eventSettings['url_supporting'] ?? '';
} elseif (is_object($eventSettings)) {
    $rawUrl = $eventSettings->url_supporting ?? '';
}

$waGroupUrl = '';
if (!empty($rawUrl)) {
    $rawUrl = trim($rawUrl);
    if (!preg_match("~^(?:f|ht)tps?://~i", $rawUrl)) {
        $waGroupUrl = "https://" . $rawUrl;
    } else {
        $waGroupUrl = $rawUrl;
    }
}

$logoImg = (!empty($appProfile->logo) && file_exists(FCPATH . 'uploads/logo/' . $appProfile->logo))
    ? base_url('uploads/logo/' . $appProfile->logo)
    : base_url('assets/images/logo/' . ($appProfile->logo ?? 'logo-fa.png'));

$logoImgWhite = (!empty($appProfile->logo_white) && file_exists(FCPATH . 'uploads/logo/' . $appProfile->logo_white))
    ? base_url('uploads/logo/' . $appProfile->logo_white)
    : base_url('assets/images/logo/' . ($appProfile->logo_white ?? 'logo-fa.png'));

$student          = (object) ($student ?? []);
$takenCourses     = $takenCourses ?? [];
$isActive         = $isActive ?? false;
$isPublished      = $isPublished ?? false;
$targetTime       = $targetTime ?? null;
$membershipStatus = strtolower($student->membership_status ?? 'candidate');

$profileIncomplete        = $profileIncomplete ?? false;
$hasNoTakenCourses        = $hasNoTakenCourses ?? false;
$documentsIncomplete      = $documentsIncomplete ?? false;
$isVerifiedCompleted      = $isVerifiedCompleted ?? false;
$isRegistrationIncomplete = $isRegistrationIncomplete ?? false;

$timezone     = new \DateTimeZone('Asia/Jakarta');
$serverNow    = (new \DateTime('now', $timezone))->getTimestamp() * 1000;
$targetTimeMs = !empty($targetTime) ? (new \DateTime($targetTime, $timezone))->getTimestamp() * 1000 : 0;
?>


<div class="app-title shadow-sm bg-white rounded p-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
    <div>
        <h1 class="h4 font-weight-bold mb-1 text-dark d-flex align-items-center">
            <i class="fa fa-bullhorn text-primary mr-2"></i> Pengumuman Hasil Seleksi
        </h1>
        <p class="mb-0">Hasil akhir seleksi pendaftaran Asisten Praktikum</p>
    </div>
    <ul class="app-breadcrumb breadcrumb side bg-transparent p-0 m-0 mt-2 mt-sm-0">
        <li class="breadcrumb-item"><a href="<?= base_url('student/dashboard'); ?>"><i class="fa fa-home text-muted"></i></a></li>
        <li class="breadcrumb-item active text-primary font-weight-semibold">Pendaftaran Mata Kuliah</li>
    </ul>
</div>

<?php if ($isRegistrationIncomplete): ?>
    <div class="countdown-hero-card p-4 p-md-5 text-center mb-4">
        <div class="brand-hero-pill-transparent mb-3">
            <img src="<?= $logoImgWhite; ?>" alt="<?= esc($appName); ?>" onerror="this.onerror=null; this.src='<?= base_url('assets/images/profile/profile-default.png'); ?>';">
        </div>

        <h5 class="font-weight-bold text-white mb-2">Pendaftaran Belum Diverifikasi</h5>
        <p class="text-white-50 mb-3 mx-auto text-small-c" style="max-width: 650px; line-height: 1.6;">
            Anda belum dapat melihat hasil pengumuman seleksi dikarenakan berkas atau data pendaftaran Anda belum memenuhi syarat verifikasi akhir.
        </p>

        <div class="p-3 mx-auto rounded text-left mb-4" style="max-width: 550px; background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.2);">
            <div class="text-white font-weight-bold small mb-2 text-uppercase tracking-wider">
                <i class="fa fa-tasks mr-1"></i> Status Kelengkapan Berkas:
            </div>
            <ul class="list-unstyled mb-0 text-white small" style="line-height: 1.8;">
                <li>
                    <i class="fa <?= !$profileIncomplete ? 'fa-check-circle text-success' : 'fa-times-circle text-warning' ?> mr-2"></i>
                    Foto Profil & Biodata Diri: <?= !$profileIncomplete ? 'Lengkap' : '<strong class="text-warning">Belum Lengkap</strong>' ?>
                </li>
                <li>
                    <i class="fa <?= !$hasNoTakenCourses ? 'fa-check-circle text-success' : 'fa-times-circle text-warning' ?> mr-2"></i>
                    Pilihan Mata Kuliah: <?= !$hasNoTakenCourses ? 'Sudah Dipilih' : '<strong class="text-warning">Belum Ada MK Dipilih</strong>' ?>
                </li>
                <li>
                    <i class="fa <?= !$documentsIncomplete ? 'fa-check-circle text-success' : 'fa-times-circle text-warning' ?> mr-2"></i>
                    Dokumen Persyaratan: <?= !$documentsIncomplete ? 'Lengkap (6 Berkas)' : '<strong class="text-warning">Belum Lengkap</strong>' ?>
                </li>
                <li>
                    <i class="fa <?= $isVerifiedCompleted ? 'fa-check-circle text-success' : 'fa-times-circle text-warning' ?> mr-2"></i>
                    Submit Verifikasi Final: <?= $isVerifiedCompleted ? 'Sudah Diverifikasi' : '<strong class="text-warning">Belum Melakukan Submit Verifikasi</strong>' ?>
                </li>
            </ul>
        </div>

        <a href="<?= base_url('student/verification'); ?>" class="btn btn-light font-weight-bold text-primary px-4 py-2 rounded shadow-sm">
            <i class="fa fa-user-edit mr-1"></i> Lengkapi & Verifikasi Sekarang
        </a>
    </div>

<?php elseif (!$isActive): ?>
    <div class="countdown-hero-card p-4 p-md-5 text-center mb-4">
        <div class="brand-hero-pill-transparent mb-3">
            <img src="<?= $logoImgWhite; ?>" alt="<?= esc($appName); ?>" onerror="this.onerror=null; this.src='<?= base_url('assets/images/profile/profile-default.png'); ?>';">
        </div>
        <h4 class="font-weight-bold text-white mb-3">Pengumuman Belum Tersedia</h4>
        <p class="text-white-50 mb-0 mx-auto text-small-c" style="max-width: 600px;">
            Fitur pengumuman hasil seleksi saat ini sedang ditutup atau belum diaktifkan oleh panitia penyelenggara. Silakan cek kembali secara berkala.
        </p>
    </div>

<?php elseif (!$isPublished): ?>
    <div class="countdown-hero-card p-4 p-md-5 text-center mb-4">
        <div class="brand-hero-pill-transparent mb-3">
            <img src="<?= $logoImgWhite; ?>" alt="<?= esc($appName); ?>" onerror="this.onerror=null; this.src='<?= base_url('assets/images/profile/profile-default.png'); ?>';">
        </div>

        <h4 class="font-weight-bold text-white mb-3">Pengumuman Belum Dibuka</h4>
        <p class="text-white-50 mb-4 mx-auto text-small-c" style="max-width: 600px;">
            Hasil seleksi penerimaan Asisten Praktikum saat ini masih dalam proses penyegelan. Akses pengumuman akan terbuka secara otomatis begitu hitung mundur selesai.
        </p>

        <div class="countdown-grid mb-3" id="countdownContainer">
            <div class="countdown-box-item">
                <div class="countdown-val-num" id="cd-days">00</div>
                <div class="countdown-lbl-txt">Hari</div>
            </div>
            <div class="countdown-box-item">
                <div class="countdown-val-num" id="cd-hours">00</div>
                <div class="countdown-lbl-txt">Jam</div>
            </div>
            <div class="countdown-box-item">
                <div class="countdown-val-num" id="cd-minutes">00</div>
                <div class="countdown-lbl-txt">Menit</div>
            </div>
            <div class="countdown-box-item">
                <div class="countdown-val-num" id="cd-seconds">00</div>
                <div class="countdown-lbl-txt">Detik</div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var serverNow = <?= (float)$serverNow ?>;
            let targetTimeMs = <?= (float)$targetTimeMs ?>;

            if (!targetTimeMs || serverNow >= targetTimeMs) {
                return;
            }

            let clientStartPerf = performance.now();
            let hasReloaded = false;

            let timer = setInterval(function() {
                let elapsedMs = performance.now() - clientStartPerf;
                let currentServerTime = serverNow + elapsedMs;
                let distance = targetTimeMs - currentServerTime;

                if (distance <= 0) {
                    clearInterval(timer);
                    if (!hasReloaded) {
                        hasReloaded = true;
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                    return;
                }

                let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                let seconds = Math.floor((distance % (1000 * 60)) / 1000);

                if (document.getElementById("cd-days")) document.getElementById("cd-days").innerText = days < 10 ? "0" + days : days;
                if (document.getElementById("cd-hours")) document.getElementById("cd-hours").innerText = hours < 10 ? "0" + hours : hours;
                if (document.getElementById("cd-minutes")) document.getElementById("cd-minutes").innerText = minutes < 10 ? "0" + minutes : minutes;
                if (document.getElementById("cd-seconds")) document.getElementById("cd-seconds").innerText = seconds < 10 ? "0" + seconds : seconds;
            }, 1000);
        });
    </script>

<?php else: ?>

    <?php if ($membershipStatus === 'member'): ?>
        <div class="status-alert-banner pass-banner mb-4">
            <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between" style="gap: 1rem;">
                <div class="d-flex align-items-start align-items-lg-center flex-column flex-lg-row gap-2">
                    <div class="status-badge-icon mb-2 mb-lg-0">
                        <i class="fa fa-user-check text-white"></i>
                    </div>
                    <div>
                        <h5 class="font-weight-bold mb-1">Selamat! Anda Dinyatakan Lolos</h5>
                        <p class="mb-0 text-white-50 text-small-c" style="line-height: 1.6;">
                            Selamat bergabung sebagai bagian dari Asisten Praktikum di <strong><?= esc($appName); ?></strong>. Silakan periksa rincian mata kuliah yang diterima di bawah ini dan ikuti grup koordinasi resmi.
                        </p>
                    </div>
                </div>

                <?php if (!empty($waGroupUrl)): ?>
                    <div class="mt-3 mt-lg-0 flex-shrink-0">
                        <a href="<?= esc($waGroupUrl); ?>" target="_blank" class="btn-wa-group">
                            <i class="fa-brands fa-whatsapp fa-xl"></i>
                            <span>Grup Forum Asisten</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php elseif ($membershipStatus === 'failed'): ?>
        <div class="status-alert-banner fail-banner mb-4">
            <div class="d-flex align-items-start align-items-lg-center flex-column flex-lg-row gap-2">
                <div class="status-badge-icon mb-2 mb-lg-0">
                    <i class="fa fa-user-times text-white"></i>
                </div>
                <div>
                    <h5 class="font-weight-bold mb-1">Mohon Maaf, Anda Belum Dinyatakan Lolos</h5>
                    <p class="mb-0 text-white-50 text-small-c" style="line-height: 1.6;">
                        Terima kasih telah berpartisipasi dalam seluruh proses seleksi. Tetap semangat dan jangan berkecil hati untuk menguji kemampuan Anda pada periode penerimaan mendatang.
                    </p>
                </div>
            </div>
        </div>

    <?php else: ?>
        <div class="status-alert-banner wait-banner mb-4">
            <div class="d-flex align-items-start align-items-lg-center flex-column flex-lg-row gap-2">
                <div class="status-badge-icon mb-2 mb-lg-0">
                    <i class="fa fa-user-clock text-white"></i>
                </div>
                <div>
                    <h5 class="font-weight-bold mb-1">Status Penilaian Dalam Verifikasi (Pending)</h5>
                    <p class="mb-0 text-white-50 text-small-c" style="line-height: 1.6;">
                        Berkas dan rekapitulasi penilaian akhir seleksi Anda sedang dalam verifikasi akhir oleh tim penguji. Silakan muat ulang halaman ini secara berkala.
                    </p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="card-modern-v2 p-4 mb-4">
        <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between mb-4 gap-2">
            <div>
                <h5 class="font-weight-bold mb-1 text-dark">Rincian Hasil Seleksi Per Mata Kuliah</h5>
                <p class="text-muted text-small-c2 mb-0">Daftar rekomendasi dan penetapan mata kuliah praktikum</p>
            </div>
            <div>
                <span class="badge px-3 py-2" style="background: rgba(10, 36, 129, 0.15); color: #0a2481; font-weight: 700; border-radius: 4px;">
                    Total: <?= count($takenCourses) ?> Mata Kuliah
                </span>
            </div>
        </div>

        <?php if (!empty($takenCourses)): ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered table-custom-responsive-announcement align-middle mb-0">
                    <thead class="bg-primary text-white">
                        <tr class="text-center small font-weight-bold text-uppercase">
                            <th style="width: 50px;">No</th>
                            <th style="width: 120px;">Kode MK</th>
                            <th>Nama Mata Kuliah</th>
                            <th style="width: 130px;">Nilai MK</th>
                            <th style="width: 200px;">Status Seleksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($takenCourses as $idx => $course):
                            $statusSel = strtolower($course->status_selection ?? 'pending');
                        ?>
                            <tr>
                                <td class="text-center font-weight-bold text-muted small"><?= $idx + 1 ?></td>
                                <td style="font-size: 0.85rem;" class="text-center">
                                    <span class="badge badge-primary px-2 py-2 font-weight-bold"><?= esc($course->course_code) ?></span>
                                </td>
                                <td style="font-size: 0.85rem;" class="font-weight-semibold col-course-name text-dark">
                                    <?= esc($course->course_name) ?>
                                </td>
                                <td style="font-size: 0.85rem;" class="text-center">
                                    <span class="tag-content-c font-weight-bold">
                                        <?= esc($course->grade ?? '-') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if ($statusSel === 'passed'): ?>
                                        <span class="status-pill-v2 pill-pass">
                                            <i class="fa fa-check-circle"></i> Lolos
                                        </span>
                                    <?php elseif ($statusSel === 'failed'): ?>
                                        <span class="status-pill-v2 pill-fail">
                                            <i class="fa fa-times-circle"></i> Tidak Lolos
                                        </span>
                                    <?php else: ?>
                                        <span class="status-pill-v2 pill-wait">
                                            <i class="fa fa-clock"></i> Dalam Proses
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="fa fa-folder-open fa-3x mb-3 text-light"></i>
                <p class="mb-0">Tidak ada data mata kuliah yang terdaftar.</p>
            </div>
        <?php endif; ?>
    </div>


<?php endif; ?>


<?= $this->endSection() ?>