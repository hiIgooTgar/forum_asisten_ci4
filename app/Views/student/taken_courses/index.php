<?= $this->extend('layouts/student') ?>

<?= $this->section('content') ?>
<?php
$profileIncomplete = $profileIncomplete ?? false;
$canVerify         = $canVerify ?? false;
$availableCourses  = $availableCourses ?? [];

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

$allTaken = $takenCourses ?? [];
$totalTaken = count($allTaken);
$maxQuota = 3;
$validationErrors = session()->getFlashdata('errors');

$keyword = trim($_GET['keyword'] ?? '');
if (!empty($keyword)) {
    $allTaken = array_filter($allTaken, function ($item) use ($keyword) {
        $obj = (object) $item;
        return (stripos($obj->course_name ?? '', $keyword) !== false) ||
            (stripos($obj->course_code ?? '', $keyword) !== false) ||
            (stripos((string)($obj->semester ?? ''), $keyword) !== false) ||
            (stripos($obj->program_name ?? '', $keyword) !== false) ||
            (stripos($obj->grade ?? '', $keyword) !== false);
    });
}

$searchParams = !empty($keyword) ? '&keyword=' . urlencode($keyword) : '';

$perPage = 5;
$totalItems = count($allTaken);
$totalPages = (int) ceil($totalItems / $perPage);

$requestedPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$currentPage = $requestedPage < 1 ? 1 : $requestedPage;
$isPageNotFound = ($totalPages > 0 && $currentPage > $totalPages);
$offset = ($currentPage - 1) * $perPage;
$pagedTaken = array_slice($allTaken, $offset, $perPage);
?>

<div class="app-title shadow-sm bg-white rounded p-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
    <div>
        <h1 class="h4 font-weight-bold mb-1 text-dark d-flex align-items-center">
            <i class="fa fa-book text-primary mr-2"></i> Pendaftaran Mata Kuliah
        </h1>
        <p class="mb-0">Kelola pengambilan rencana mata kuliah akademik Anda secara mandiri</p>
    </div>
    <ul class="app-breadcrumb breadcrumb side bg-transparent p-0 m-0 mt-2 mt-sm-0">
        <li class="breadcrumb-item"><a href="<?= base_url('student/dashboard'); ?>"><i class="fa fa-home text-muted"></i></a></li>
        <li class="breadcrumb-item active text-primary font-weight-semibold">Pendaftaran Mata Kuliah</li>
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
                    Anda belum dapat menambah pendaftaran mata kuliah karena terdapat data profil pengguna yang masih kosong. Silakan lengkapi profil Anda terlebih dahulu pada menu Biodata Mahasiswa bagian <a href="<?= base_url('student/profile'); ?>" class="text-primary" style="font-weight: 500;">Profil Diri</a>.
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($canVerify): ?>
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

<?php if (!empty($validationErrors) && is_array($validationErrors)): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm p-3 p-md-4 mb-4 position-relative overflow-hidden" role="alert" style="background-color: #fdf2f2; border-left: 4px solid #e53e3e !important; border-top: 1px solid rgba(229, 62, 62, 0.5) !important; border-right: 1px solid rgba(229, 62, 62, 0.5) !important; border-bottom: 1px solid rgba(229, 62, 62, 0.5) !important;">
        <div class="d-flex align-items-start" style="gap: 0.9rem">
            <div class="alert-icon-wrapper bg-soft-danger text-danger rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; background-color: rgba(229, 62, 62, 0.12);">
                <i class="fa fa-exclamation-circle fa-lg"></i>
            </div>
            <div class="pr-4 flex-grow-1">
                <h6 class="font-weight-bold text-danger mb-2" style="font-size: 0.95rem;">
                    Terjadi Kesalahan Input Data
                </h6>
                <p class="text-muted small mb-2" style="font-size: 0.825rem;">
                    Mohon periksa kembali formulir Anda dan perbaiki beberapa kesalahan berikut:
                </p>
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
                $isActive = ($student->status_account ?? '') === 'active';
                $statusText  = $isActive ? 'Active' : 'Inactive';
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
                <small class="text-muted d-block font-weight-bold text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Ringkasan Kuota</small>
                <div class="d-flex justify-content-between align-items-center text-xs-c mb-2">
                    <span class="text-secondary">Diambil:</span>
                    <span class="font-weight-bold badge badge-primary px-2.5 py-1"><?= $totalTaken ?> / <?= $maxQuota ?></span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?= ($totalTaken / $maxQuota) * 100 ?>%;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-9 col-lg-8 col-12">
        <div class="tile p-3 p-md-4 bg-white shadow-sm rounded border-0">

            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center pb-3 mb-4 border-bottom gap-4">
                <div class="mb-3 mb-sm-0">
                    <h5 class="font-weight-bold mb-1 text-dark border-left-primary pl-2">Mata Kuliah Terdaftar</h5>
                    <p class="text-secondary text-small-c mb-0">Daftar mata kuliah yang Anda daftarkan pada semester berjalan ini.</p>
                </div>

                <button type="button"
                    class="btn btn-primary font-weight-bold shadow-sm rounded-lg px-3 py-2 text-nowrap"
                    data-toggle="modal"
                    data-target="#modalAddCourse"
                    <?= ($profileIncomplete || $totalTaken >= $maxQuota) ? '' : '' ?>>
                    <i class="fa fa-plus-circle mr-1.5"></i> Tambah Mata Kuliah
                </button>
            </div>

            <div class="p-3 bg-soft-primary border-left-primary mb-4">
                <div class="d-flex align-items-center mb-1">
                    <i class="fa fa-info-circle text-primary mr-2"></i>
                    <h6 class="font-weight-bold mb-0 text-dark">Informasi Pengambilan Mata Kuliah</h6>
                </div>
                <small class="text-xs-c text-secondary d-block">
                    Pendaftaran mata kuliah terbatas maksimal <strong class="text-primary">3 (tiga) mata kuliah</strong> per mahasiswa. Anda dapat mengambil seluruh mata kuliah yang tersedia di lingkup fakultas Anda.
                </small>
            </div>

            <form action="" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control rounded-left" placeholder="Cari nama mata kuliah, kode, semester, grade..." value="<?= esc($keyword) ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary px-3" type="submit">
                            <i class="fa fa-search mr-1"></i> Cari
                        </button>
                        <?php if (!empty($keyword)): ?>
                            <a href="<?= base_url('student/taken-courses'); ?>" class="btn btn-warning px-3" title="Reset Pencarian">
                                <i class="fa fa-refresh"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>

            <?php if (empty($allTaken)): ?>
                <div class="text-center py-5 my-3">
                    <div class="mb-3">
                        <i class="fa fa-book text-primary" style="font-size: 2.3rem;"></i>
                    </div>
                    <h6 class="font-weight-bold text-dark">Belum Ada Mata Kuliah Didaftarkan</h6>
                    <p class="text-secondary text-xs-c max-w-md mx-auto">
                        Klik tombol <strong class="text-primary">"Tambah Mata Kuliah"</strong> di atas untuk mendaftarkan mata kuliah semester Anda.
                    </p>
                </div>

            <?php elseif ($isPageNotFound): ?>
                <div class="text-center py-5 my-3">
                    <div class="mb-3">
                        <i class="fa fa-exclamation-triangle text-primary" style="font-size: 2.3rem;"></i>
                    </div>
                    <h5 class="font-weight-bold text-dark">Halaman Tidak Ditemukan</h5>
                    <p class="text-secondary text-sm max-w-md mx-auto">
                        Halaman ke-<span class="text-primary font-weight-semibold"><?= esc($_GET['page'] ?? $currentPage) ?></span> tidak tersedia. Total halaman yang ada adalah <span class="text-primary font-weight-semibold"><?= $totalPages ?></span>.
                    </p>
                    <a href="<?= base_url('student/taken-courses?page=1' . $searchParams); ?>" class="btn btn-primary btn-xs mt-2 font-weight-semibold">
                        Kembali ke Halaman Utama
                    </a>
                </div>

            <?php else: ?>
                <div class="row pt-2">
                    <?php foreach ($pagedTaken as $item): ?>
                        <?php $row = (object) $item; ?>
                        <div class="col-12 mb-4">
                            <div class="card card-experience rounded-lg p-3 p-md-4">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start">
                                    <div class="w-100 pr-md-3">
                                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                            <span class="badge badge-soft-primary-c2 px-3 py-2 font-weight-normal">
                                                <i class="fa fa-hashtag mr-1"></i><?= esc($row->course_code) ?>
                                            </span>
                                            <span class="badge badge-soft-info px-3 py-2 font-weight-normal">
                                                <i class="fa fa-calendar-alt mr-1"></i>Semester <?= esc($row->semester) ?>
                                            </span>
                                            <span class="badge badge-soft-success px-3 py-2 font-weight-normal">
                                                <i class="fa fa-credit-card mr-1"></i><?= esc($row->credits) ?> SKS
                                            </span>
                                        </div>

                                        <h5 class="font-weight-bold text-dark my-3"><?= esc($row->course_name) ?></h5>
                                        <h6 class="text-primary font-weight-semibold mb-2">
                                            <i class="fa fa-graduation-cap mr-1"></i><?= esc($row->program_name) ?>
                                        </h6>

                                        <p class="text-xs-c text-secondary mb-1">
                                            Target Grade: <strong class="text-primary"><?= esc($row->grade) ?></strong>
                                        </p>
                                    </div>

                                    <div class="action-btns d-flex gap-2 mt-3 mt-md-0 align-self-end align-self-md-start">
                                        <button type="button"
                                            class="btn btn-sm btn-warning text-dark font-weight-semibold rounded"
                                            data-toggle="modal"
                                            data-target="#modalEditCourse<?= $row->id ?>">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <?php if ($profileIncomplete): ?>
                                            <button type="button"
                                                class="btn btn-sm btn-danger font-weight-semibold rounded"
                                                onclick="handleIncompleteProfileDelete()">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        <?php else: ?>
                                            <button type="button"
                                                class="btn btn-sm btn-danger font-weight-semibold rounded"
                                                onclick="confirmDeleteCourse('<?= base_url('student/taken-courses/delete/' . $row->taken_course_code) ?>', '<?= esc($row->course_name, 'js') ?>', '<?= esc($row->program_name, 'js') ?>')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade modal-main-content" id="modalEditCourse<?= $row->id ?>" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow-lg rounded-lg overflow-hidden">
                                    <div class="modal-header bg-light border-bottom-0 pb-3 pt-4 px-3 px-md-4 d-flex align-items-center justify-content-between">
                                        <h5 class="modal-title font-weight-bold text-primary d-flex align-items-center gap-1 mb-0">
                                            <span class="avatar-icon-wrapper bg-soft-primary text-primary rounded-circle mr-2 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                                <i class="fa fa-edit"></i>
                                            </span>
                                            Edit Data Mata Kuliah
                                        </h5>
                                        <button type="button" class="close close-btn-c p-2 m-0" data-dismiss="modal" aria-label="Close" style="outline: none;">
                                            <span class="text-primary" aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                                        </button>
                                    </div>

                                    <div class="px-3 px-md-4 mt-3 text-muted text-xs-c2 d-flex align-items-center gap-2">
                                        <?php if ($profileIncomplete): ?>
                                            <p class="py-2 px-3 m-0 alert alert-danger w-100 text-justify text-sm-start">
                                                <i class="fa fa-info-circle mr-1"></i>
                                                <span class="font-weight-bold">Profil Anda belum lengkap. Silakan lengkapi profil Anda terlebih dahulu.</span>
                                            </p>
                                        <?php else: ?>
                                            <p class="p-0 m-0">
                                                <i class="fa fa-info-circle text-primary mr-1"></i>
                                                Pilih mata kuliah dari Fakultas Anda. Tanda (<span class="text-danger font-weight-bold mx-0.5">*</span>) wajib diisi.
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <form action="<?= base_url('student/taken-courses/update/' . $row->taken_course_code) ?>" method="POST">
                                        <?= csrf_field() ?>
                                        <div class="modal-body p-3 p-md-4">

                                            <div class="form-group mb-3">
                                                <label class="font-weight-bold text-small-c text-dark">Pilih Mata Kuliah <span class="text-danger">*</span></label>

                                                <?php if (!$profileIncomplete): ?>
                                                    <select name="course_id" class="form-control select2 select_courses <?= session('errors.course_id') ? 'is-invalid' : '' ?>" style="width: 100%;">
                                                        <option value="" disabled <?= old('course_id', $row->course_id ?? '') ? '' : 'selected' ?>>-- Pilih Mata Kuliah --</option>
                                                        <?php foreach ($availableCourses as $course): ?>
                                                            <?php
                                                            $quotaNeeded = (int)($course->quota_needed ?? 0);
                                                            $totalTaken  = (int)($course->total_taken ?? 0);
                                                            $isFull      = ($quotaNeeded > 0 && $totalTaken >= $quotaNeeded);
                                                            $isSelected  = old('course_id', $row->course_id ?? null) == $course->id;
                                                            $isDisabled  = $isFull && !$isSelected;
                                                            ?>
                                                            <option value="<?= $course->id ?>"
                                                                <?= $isSelected ? 'selected' : '' ?>
                                                                <?= $isDisabled ? 'disabled' : '' ?>
                                                                data-is-full="<?= $isFull ? 'true' : 'false' ?>"
                                                                style="<?= $isFull ? 'color: red;' : '' ?>">
                                                                [<?= esc($course->program_name) ?>] <?= esc($course->course_name) ?> - <?= esc($course->course_code) ?> - Semester <?= esc($course->semester) ?> - <?= esc($course->credits) ?> SKS
                                                                <?php if ($quotaNeeded > 0): ?>
                                                                    (Kuota: <?= $totalTaken ?>/<?= $quotaNeeded ?>) <?= $isFull ? ' - [KUOTA PENUH]' : '' ?>
                                                                <?php else: ?>
                                                                    (Kuota: Tanpa Batas)
                                                                <?php endif; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>

                                                    <?php if (session('errors.course_id')): ?>
                                                        <span class="invalid-feedback d-block"><?= session('errors.course_id') ?></span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <select class="form-control select2 bg-light" disabled style="width: 100%;">
                                                        <option value="" selected disabled>-- Lengkapi profil Anda terlebih dahulu --</option>
                                                    </select>
                                                <?php endif; ?>
                                            </div>

                                            <div class="form-group mb-0">
                                                <label class="font-weight-bold text-small-c text-dark">Target Grade / Nilai <span class="text-danger">*</span></label>

                                                <?php if (!$profileIncomplete): ?>
                                                    <select name="grade" class="form-control select2 <?= session('errors.grade') ? 'is-invalid' : '' ?>" style="width: 100%;">
                                                        <option value="" disabled <?= old('grade', $row->grade ?? '') ? '' : 'selected' ?>>-- Pilih Grade --</option>
                                                        <?php foreach (['A', 'A-', 'B+', 'B'] as $g): ?>
                                                            <option value="<?= $g ?>" <?= old('grade', $row->grade ?? null) === $g ? 'selected' : '' ?>><?= $g ?></option>
                                                        <?php endforeach; ?>
                                                    </select>

                                                    <?php if (session('errors.grade')): ?>
                                                        <span class="invalid-feedback d-block"><?= session('errors.grade') ?></span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <select class="form-control select2 bg-light" disabled style="width: 100%;">
                                                        <option value="" selected disabled>-- Lengkapi profil Anda terlebih dahulu --</option>
                                                    </select>
                                                <?php endif; ?>
                                            </div>

                                        </div>

                                        <div class="modal-footer bg-light px-3 px-md-4 pt-3 pb-4 border-top-0 d-flex flex-row justify-content-end align-items-center gap-2">
                                            <button type="button" class="btn btn-outline-secondary btn-modal-batal px-4 font-weight-semibold" data-dismiss="modal">Batal</button>
                                            <?php if ($profileIncomplete): ?>
                                                <button type="button" class="btn btn-primary btn-modal-simpan px-4 font-weight-bold shadow-sm" onclick="handleIncompleteProfileUpdate()">
                                                    <i class="fa fa-check-circle mr-1.5"></i> Simpan Data
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" class="btn btn-primary btn-modal-simpan px-4 font-weight-bold shadow-sm">
                                                    <i class="fa fa-check-circle mr-1.5"></i> Simpan Data
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination-container flex-column flex-sm-row gap-3 mt-4 d-flex justify-content-between align-items-center">
                        <div class="pagination-info pb-sm-0 pb-3">
                            Menampilkan <span class="font-weight-bold text-primary"><?= $offset + 1 ?></span> - <span class="font-weight-bold text-primary"><?= min($offset + $perPage, $totalItems) ?></span> dari <span class="font-weight-bold text-primary"><?= $totalItems ?></span> mata kuliah
                        </div>

                        <ul class="pagination-nav m-0 p-0 d-flex align-items-center list-unstyled">
                            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= $currentPage > 1 ? '?page=' . ($currentPage - 1) . $searchParams : '#' ?>" aria-label="Previous">
                                    <i class="fa fa-chevron-left" style="font-size: 0.75rem;"></i>
                                </a>
                            </li>

                            <div class="d-none d-md-flex align-items-center">
                                <?php if ($totalPages <= 5): ?>
                                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                        <li class="page-item <?= $p === $currentPage ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $p . $searchParams ?>"><?= $p ?></a>
                                        </li>
                                    <?php endfor; ?>
                                <?php else: ?>
                                    <li class="page-item <?= $currentPage === 1 ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=1<?= $searchParams ?>">1</a>
                                    </li>
                                    <li class="page-item <?= $currentPage === 2 ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=2<?= $searchParams ?>">2</a>
                                    </li>

                                    <?php if ($currentPage > 2 && $currentPage < $totalPages - 1): ?>
                                        <li class="page-item active">
                                            <a class="page-link" href="?page=<?= $currentPage . $searchParams ?>"><?= $currentPage ?></a>
                                        </li>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php else: ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>

                                    <li class="page-item <?= $currentPage === ($totalPages - 1) ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= ($totalPages - 1) . $searchParams ?>"><?= $totalPages - 1 ?></a>
                                    </li>
                                    <li class="page-item <?= $currentPage === $totalPages ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $totalPages . $searchParams ?>"><?= $totalPages ?></a>
                                    </li>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex d-md-none align-items-center">
                                <?php if ($totalPages <= 3): ?>
                                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                        <li class="page-item <?= $p === $currentPage ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $p . $searchParams ?>"><?= $p ?></a>
                                        </li>
                                    <?php endfor; ?>
                                <?php else: ?>
                                    <li class="page-item <?= $currentPage === 1 ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=1<?= $searchParams ?>">1</a>
                                    </li>

                                    <?php if ($currentPage > 1 && $currentPage < $totalPages): ?>
                                        <li class="page-item active">
                                            <a class="page-link" href="?page=<?= $currentPage . $searchParams ?>"><?= $currentPage ?></a>
                                        </li>
                                    <?php endif; ?>

                                    <li class="page-item disabled"><span class="page-link">...</span></li>

                                    <li class="page-item <?= $currentPage === $totalPages ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $totalPages . $searchParams ?>"><?= $totalPages ?></a>
                                    </li>
                                <?php endif; ?>
                            </div>

                            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= $currentPage < $totalPages ? '?page=' . ($currentPage + 1) . $searchParams : '#' ?>" aria-label="Next">
                                    <i class="fa fa-chevron-right" style="font-size: 0.75rem;"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    </div>
</div>

<div class="modal fade modal-main-content" id="modalAddCourse" tabindex="-1" role="dialog" aria-labelledby="modalAddCourseLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg overflow-hidden">
            <div class="modal-header bg-light border-bottom-0 pb-3 pt-4 px-3 px-md-4 d-flex align-items-center justify-content-between">
                <h5 class="modal-title font-weight-bold text-primary d-flex align-items-center gap-1 mb-0" id="modalAddCourseLabel">
                    <span class="avatar-icon-wrapper bg-soft-primary text-primary rounded-circle mr-2 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="fa fa-book"></i>
                    </span>
                    Tambah Mata Kuliah
                </h5>
                <button type="button" class="close close-btn-c p-2 m-0" data-dismiss="modal" aria-label="Close" style="outline: none;">
                    <span class="text-primary" aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                </button>
            </div>

            <div class="px-3 px-md-4 mt-3 text-muted text-xs-c2 d-flex align-items-center gap-2">
                <?php if ($profileIncomplete): ?>
                    <p class="py-2 px-3 m-0 alert alert-danger w-100 text-justify text-sm-start">
                        <i class="fa fa-info-circle mr-1"></i>
                        <span class="font-weight-bold">Profil Anda belum lengkap. Silakan lengkapi profil Anda terlebih dahulu.</span>
                    </p>
                <?php else: ?>
                    <p class="p-0 m-0 ">
                        <i class="fa fa-info-circle text-primary mr-1"></i>
                        Pilih mata kuliah dari Fakultas Anda. Tanda (<span class="text-danger font-weight-bold mx-0.5">*</span>) wajib diisi.
                    </p>
                <?php endif; ?>
            </div>

            <form action="<?= base_url('student/taken-courses/store') ?>" method="POST" class="needs-validation">
                <?= csrf_field() ?>
                <div class="modal-body p-3 p-md-4">

                    <div class="form-group mb-3">
                        <label class="font-weight-bold text-small-c text-dark">Pilih Mata Kuliah <span class="text-danger">*</span></label>

                        <?php if (!$profileIncomplete): ?>
                            <select name="course_id" class="form-control select2 select_courses <?= session('errors.course_id') ? 'is-invalid' : '' ?>" style="width: 100%;">
                                <option value="" disabled <?= old('course_id') ? '' : 'selected' ?>>-- Pilih Mata Kuliah --</option>
                                <?php foreach ($availableCourses as $course): ?>
                                    <?php
                                    $quotaNeeded = (int)($course->quota_needed ?? 0);
                                    $totalTaken  = (int)($course->total_taken ?? 0);
                                    $isFull      = ($quotaNeeded > 0 && $totalTaken >= $quotaNeeded);
                                    $isSelected  = old('course_id') == $course->id;
                                    $isDisabled  = $isFull;
                                    ?>
                                    <option value="<?= $course->id ?>"
                                        <?= $isSelected ? 'selected' : '' ?>
                                        <?= $isDisabled ? 'disabled' : '' ?>
                                        data-is-full="<?= $isFull ? 'true' : 'false' ?>">
                                        [<?= esc($course->program_name) ?>] <?= esc($course->course_name) ?> - <?= esc($course->course_code) ?> - Semester <?= esc($course->semester) ?> - <?= esc($course->credits) ?> SKS
                                        <?php if ($quotaNeeded > 0): ?>
                                            (Kuota: <?= $totalTaken ?>/<?= $quotaNeeded ?>) <?= $isFull ? ' - [KUOTA PENUH]' : '' ?>
                                        <?php else: ?>
                                            (Kuota: Tanpa Batas)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <?php if (session('errors.course_id')): ?>
                                <span class="invalid-feedback d-block"><?= session('errors.course_id') ?></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <select class="form-control select2 bg-light" disabled style="width: 100%;">
                                <option value="" selected disabled>-- Lengkapi profil Anda terlebih dahulu --</option>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-small-c text-dark">Target Grade / Nilai <span class="text-danger">*</span></label>

                        <?php if (!$profileIncomplete): ?>
                            <select name="grade" class="form-control select2 <?= session('errors.grade') ? 'is-invalid' : '' ?>" style="width: 100%;">
                                <option value="" disabled selected>-- Pilih Grade --</option>
                                <?php foreach (['A', 'A-', 'B+', 'B'] as $g): ?>
                                    <option value="<?= $g ?>" <?= old('grade') === $g ? 'selected' : '' ?>><?= $g ?></option>
                                <?php endforeach; ?>
                            </select>

                            <?php if (session('errors.grade')): ?>
                                <span class="invalid-feedback d-block"><?= session('errors.grade') ?></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <select class="form-control select2 bg-light" disabled style="width: 100%;">
                                <option value="" selected disabled>-- Lengkapi profil Anda terlebih dahulu --</option>
                            </select>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="modal-footer bg-light px-3 px-md-4 pt-3 pb-4 border-top-0 d-flex flex-row justify-content-end align-items-center gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-modal-batal px-4 font-weight-semibold" data-dismiss="modal">Batal</button>

                    <?php if ($profileIncomplete): ?>
                        <button type="button" class="btn btn-primary btn-modal-simpan px-4 font-weight-bold shadow-sm" onclick="handleIncompleteProfileSubmit()">
                            <i class="fa fa-check-circle mr-1.5"></i> Simpan Data
                        </button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-primary btn-modal-simpan px-4 font-weight-bold shadow-sm">
                            <i class="fa fa-check-circle mr-1.5"></i> Simpan Data
                        </button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>