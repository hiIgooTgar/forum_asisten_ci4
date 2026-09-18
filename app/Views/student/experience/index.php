<?= $this->extend('layouts/student') ?>

<?= $this->section('content') ?>
<?php
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

$typeBadges = [
    'work'                => [
        'label' => 'Pekerjaan / Magang / PKL',
        'class' => 'badge-soft-primary-c',
        'icon'  => 'fa-briefcase'
    ],
    'organizational'      => [
        'label' => 'Organisasi / Komunitas / UKM',
        'class' => 'badge-soft-success',
        'icon'  => 'fa-users'
    ],
    'teaching_assistant'   => [
        'label' => 'Asisten Praktikum / Tutor / Mentoring',
        'class' => 'badge-soft-warning',
        'icon'  => 'fa-chalkboard-teacher'
    ],
    'volunteering'         => [
        'label' => 'Sukarelawan / Volunteer / Aksi Sosial',
        'class' => 'badge-soft-info',
        'icon'  => 'fa-hands-helping'
    ],
    'certification'        => [
        'label' => 'Sertifikasi / Pelatihan / BootCamp',
        'class' => 'badge-soft-purple',
        'icon'  => 'fa-certificate'
    ],
    'competition'          => [
        'label' => 'Kompetisi / Hackathon / Award',
        'class' => 'badge-soft-danger',
        'icon'  => 'fa-trophy'
    ],
    'project'              => [
        'label' => 'Proyek Independen / Freelance / Riset',
        'class' => 'badge-soft-teal',
        'icon'  => 'fa-laptop-code'
    ],
    'community_service'    => [
        'label' => 'Pengabdian Masyarakat / Bakti Sosial',
        'class' => 'badge-soft-dark',
        'icon'  => 'fa-heart'
    ],
    'other'                => [
        'label' => 'Lainnya / Kegiatan Lain',
        'class' => 'badge-soft-secondary',
        'icon'  => 'fa-asterisk'
    ],
];

$allExperiences = $experiences ?? [];
$currentYear = (int) date('Y');
$validationErrors = session()->getFlashdata('errors');

$keyword = trim($_GET['keyword'] ?? '');
if (!empty($keyword)) {
    $allExperiences = array_filter($allExperiences, function ($exp) use ($keyword, $typeBadges) {
        $expObj = (object) $exp;
        $title = $expObj->title ?? '';
        $org   = $expObj->organization_name ?? '';
        $desc  = $expObj->description ?? '';
        $year  = $expObj->year_occurred ?? '';
        $type  = $expObj->experience_type ?? '';
        $badgeLabel = $typeBadges[$type]['label'] ?? '';

        return (stripos($title, $keyword) !== false) ||
            (stripos($org, $keyword) !== false) ||
            (stripos($desc, $keyword) !== false) ||
            (stripos((string)$year, $keyword) !== false) ||
            (stripos($badgeLabel, $keyword) !== false);
    });
}

$searchParams = !empty($keyword) ? '&keyword=' . urlencode($keyword) : '';

$perPage = 5;
$totalExperiences = count($allExperiences);
$totalPages = (int) ceil($totalExperiences / $perPage);

$requestedPage = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$currentPage = $requestedPage < 1 ? 1 : $requestedPage;
$isPageNotFound = ($totalPages > 0 && $currentPage > $totalPages);

$offset = ($currentPage - 1) * $perPage;
$pagedExperiences = array_slice($allExperiences, $offset, $perPage);
?>

<div class="app-title shadow-sm bg-white rounded p-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
    <div>
        <h1 class="h4 font-weight-bold mb-1 text-dark d-flex align-items-center">
            <i class="fa fa-briefcase text-primary mr-2"></i> Pengalaman &amp; Portofolio
        </h1>
        <p class="mb-0">Kelola riwayat pekerjaan, organisasi, asistensi, dan kegiatan kemahasiswaan Anda</p>
    </div>
    <ul class="app-breadcrumb breadcrumb side bg-transparent p-0 m-0 mt-2 mt-sm-0">
        <li class="breadcrumb-item"><a href="<?= base_url('student/dashboard'); ?>"><i class="fa fa-home text-muted"></i></a></li>
        <li class="breadcrumb-item active text-primary font-weight-semibold">Pengalaman</li>
    </ul>
</div>

<?php if (!empty($validationErrors) && is_array($validationErrors)): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm p-3 p-md-4 mb-4 position-relative overflow-hidden" role="alert" style="background-color: #fdf2f2; border-left: 4px solid #e53e3e !important; border-top: 1px solid rgba(229, 62, 62, 0.5) !important; border-right: 1px solid rgba(229, 62, 62, 0.5) !important; border-bottom: 1px solid rgba(229, 62, 62, 0.5) !important;">
        <div class="d-flex align-items-start" style="gap: 0.9rem">
            <div class="alert-icon-wrapper bg-soft-danger text-danger rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 38px; height: 38px; background-color: rgba(229, 62, 62, 0.12);">
                <i class="fa fa-exclamation-circle fa-lg"></i>
            </div>

            <div class="pr-4 flex-grow-1">
                <h6 class="font-weight-bold text-danger mb-1" style="font-size: 0.95rem; letter-spacing: -0.2px;">
                    Terjadi Kesalahan Input Data
                </h6>
                <p class="text-muted small mb-2" style="font-size: 0.825rem;">
                    Mohon periksa kembali formulir Anda dan perbaiki beberapa kesalahan berikut:
                </p>
                <ul class="mb-0 pl-3 text-dark small" style="font-size: 0.85rem; line-height: 1.6;">
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
                $borderClass = $isActive ? 'border-warning text-warning' : 'border-warning text-warning';
                $statusText  = $isActive ? 'Active' : 'Inactive';
                ?>

                <span class="badge bg-transparent border <?= $borderClass ?> font-weight-normal px-2 py-1" style="font-size: 0.68rem; letter-spacing: 0.3px;">
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
                    <div id="realtime-day-date" class="text-muted small font-weight-bold" style="font-size: 0.8rem;">
                        --
                    </div>
                    <div id="realtime-clock" class="clock-time-display font-weight-bold mt-1" style="font-size: 0.8rem;">
                        00:00:00 WIB
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="px-1">
                <small class="text-muted d-block font-weight-bold text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Ringkasan</small>
                <div class="d-flex justify-content-between align-items-center text-xs-c">
                    <span class="text-secondary">Total Pengalaman:</span>
                    <span class="font-weight-bold badge badge-primary px-2.5 py-1"><?= $totalExperiences ?></span>
                </div>
            </div>

        </div>
    </div>

    <div class="col-xl-9 col-lg-8 col-12">
        <div class="tile p-3 p-md-4 bg-white shadow-sm rounded border-0">

            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center pb-3 mb-4 border-bottom gap-4">
                <div class="mb-3 mb-sm-0">
                    <h5 class="font-weight-bold mb-1 text-dark border-left-primary-title pl-2">Daftar Pengalaman</h5>
                    <p class="text-secondary text-small-c mb-0">Pengalaman yang tercatat akan memperkuat rekam jejak akademik dan portofolio profesional Anda.</p>
                </div>
                <button type="button" class="btn btn-primary font-weight-bold shadow-sm rounded-lg px-3 py-2 text-nowrap" data-toggle="modal" data-target="#modalAddExperience">
                    <i class="fa fa-plus-circle mr-1.5"></i> Tambah Pengalaman
                </button>
            </div>

            <div class="p-3 bg-soft-primary border-left-primary mb-4">
                <div class="d-flex align-items-center mb-1">
                    <i class="fa fa-info-circle text-primary mr-2"></i>
                    <h6 class="font-weight-bold mb-0 text-dark">Informasi Data Pengalaman</h6>
                </div>
                <small class="text-xs-c text-secondary-c d-block">
                    Pengisian riwayat rekam jejak ini bersifat <strong class="text-primary">opsional (tidak wajib)</strong>. Namun, menambahkan pengalaman kerja, organisasi, sertifikasi, atau kepanitiaan sangat disarankan untuk melengkapi portofolio dan profil Anda.
                </small>
            </div>

            <form action="" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="keyword" class="form-control rounded-left" placeholder="Cari judul, instansi, jenis, tahun..." value="<?= esc($keyword) ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary px-3" type="submit">
                            <i class="fa fa-search mr-1"></i> Cari
                        </button>
                        <?php if (!empty($keyword)): ?>
                            <a href="<?= base_url('student/experiences'); ?>" class="btn btn-warning px-3" title="Reset Pencarian">
                                <i class="fa fa-refresh"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>

            <?php if (empty($allExperiences)): ?>
                <div class="text-center py-5 my-3">
                    <div class="mb-3">
                        <i class="fa fa-folder-open text-primary" style="font-size: 2.3rem;"></i>
                    </div>
                    <h6 class="font-weight-bold text-dark">Belum ada pengalaman tercatat</h6>
                    <p class="text-secondary-c text-xs-c max-w-md mx-auto">
                        Jika Anda memiliki riwayat kegiatan organisasi, pekerjaan, atau sertifikasi, klik tombol <strong class="text-primary">"Tambah Pengalaman"</strong> di atas.
                    </p>
                </div>

            <?php elseif ($isPageNotFound ?? ($currentPage > $totalPages)): ?>
                <div class="text-center py-5 my-3">
                    <div class="mb-3">
                        <i class="fa fa-exclamation-triangle text-primary" style="font-size: 2.3rem;"></i>
                    </div>
                    <h5 class="font-weight-bold text-dark">Halaman Tidak Ditemukan</h5>
                    <p class="text-secondary text-sm max-w-md mx-auto">
                        Halaman ke-<span class="text-primary font-weight-semibold"><?= esc($_GET['page'] ?? $currentPage ?? 1) ?></span> tidak tersedia. Total halaman yang ada adalah <span class="text-primary font-weight-semibold"><?= $totalPages ?? 1 ?></span>.
                    </p>
                    <a href="<?= base_url('student/experiences?page=1'); ?>" class="btn btn-primary btn-xs mt-2 font-weight-semibold">
                        Kembali ke Halaman Utama
                    </a>
                </div>

            <?php else: ?>
                <div class="row pt-2">
                    <?php foreach ($pagedExperiences as $exp): ?>
                        <?php
                        $expData = (object) $exp;
                        $expType = $expData->experience_type ?? 'other';
                        $expCode = $expData->experience_code ?? '';
                        $expId   = $expData->id ?? mt_rand(100, 999);
                        $badge   = $typeBadges[$expType] ?? $typeBadges['other'];
                        ?>
                        <div class="col-12 mb-4">
                            <div class="card card-experience rounded-lg p-3 p-md-4">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start">
                                    <div class="w-100 pr-md-3">
                                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                            <span class="badge px-3 py-2 font-weight-normal <?= $badge['class'] ?>">
                                                <i class="fa <?= $badge['icon'] ?> mr-1"></i><?= $badge['label'] ?>
                                            </span>

                                            <?php if (!empty($expData->is_current)): ?>
                                                <span class="badge badge-primary px-3 py-2 font-weight-normal">
                                                    <i class="fa fa-circle mr-1" style="font-size: 0.5rem;"></i>Masih Berlangsung
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <h5 class="font-weight-bold text-dark my-3"><?= esc($expData->title) ?></h5>
                                        <h6 class="text-primary font-weight-semibold mb-2">
                                            <i class="fa fa-building mr-1"></i><?= esc($expData->organization_name) ?>
                                        </h6>

                                        <p class="text-xs-c text-secondary-c mb-1">
                                            <i class="fa fa-calendar-alt mr-1"></i>Tahun Pelaksanaan: <strong class="text-primary"><?= esc($expData->year_occurred) ?></strong>
                                        </p>

                                        <?php if (!empty($expData->description)): ?>
                                            <div class="text-xs-c text-secondary border-0" style="white-space: pre-line; line-height: 1.5;">
                                                <?= esc($expData->description) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="action-btns d-flex gap-2 mt-3 mt-md-0 align-self-end align-self-md-start">
                                        <button class="btn btn-sm btn-warning text-dark font-weight-semibold rounded"
                                            data-toggle="modal"
                                            data-target="#modalEditExperience<?= $expId ?>">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger font-weight-semibold rounded"
                                            onclick="confirmDeleteExperience('<?= base_url('student/experiences/delete/' . $expCode) ?>', '<?= esc($expData->title, 'js') ?>', '<?= esc($expData->organization_name, 'js') ?>')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modalEditExperience<?= $expId ?>" tabindex="-1" role="dialog" aria-labelledby="modalEditExperienceLabel<?= $expId ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow-lg rounded-lg overflow-hidden">

                                    <div class="modal-header bg-light border-bottom-0 pb-3 pt-4 px-3 px-md-4 d-flex align-items-center justify-content-between">
                                        <h5 class="modal-title font-weight-bold text-primary d-flex align-items-center gap-1 mb-0">
                                            <span class="avatar-icon-wrapper bg-soft-primary text-primary rounded-circle mr-2 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: rgba(10, 36, 129, 0.2)">
                                                <i class="fa fa-briefcase"></i>
                                            </span>
                                            Edit Pengalaman
                                        </h5>
                                        <button type="button" class="close close-btn-c p-2 m-0" data-dismiss="modal" aria-label="Close" style="outline: none;">
                                            <span class="text-primary" aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                                        </button>
                                    </div>

                                    <div class="px-3 px-md-4 mt-3 text-muted small d-flex align-items-center gap-2">
                                        <i class="fa fa-info-circle text-primary mr-1.5"></i>
                                        <p class="p-0 m-0">Perbarui formulir di bawah ini. Tanda (<span class="text-danger font-weight-bold mx-0.5">*</span>) menunjukkan bidang yang wajib diisi.</p>
                                    </div>

                                    <form action="<?= base_url('student/experiences/update/' . $expCode) ?>" method="POST" class="needs-validation">
                                        <?= csrf_field() ?>

                                        <div class="modal-body p-3 p-md-4">
                                            <div class="row">
                                                <div class="col-12 col-md-6 mb-3">
                                                    <label class="font-weight-bold text-small-c text-dark">Judul Peran / Posisi <span class="text-danger">*</span></label>
                                                    <input type="text" name="title" class="form-control <?= session('errors.title') ? 'is-invalid' : '' ?>" placeholder="Contoh: Ketua Himpunan / Frontend Dev" value="<?= old('title', $expData->title) ?>">
                                                    <?php if (session('errors.title')): ?>
                                                        <span class="invalid-feedback d-block"><?= session('errors.title') ?></span>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="col-12 col-md-6 mb-3">
                                                    <label class="font-weight-bold text-small-c text-dark">Nama Instansi / Organisasi <span class="text-danger">*</span></label>
                                                    <input type="text" name="organization_name" class="form-control <?= session('errors.organization_name') ? 'is-invalid' : '' ?>" placeholder="Contoh: BEM / PT. Tech Solutions" value="<?= old('organization_name', $expData->organization_name) ?>">
                                                    <?php if (session('errors.organization_name')): ?>
                                                        <span class="invalid-feedback d-block"><?= session('errors.organization_name') ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12 col-md-6 mb-3">
                                                    <label class="font-weight-bold text-small-c text-dark">Jenis Pengalaman <span class="text-danger">*</span></label>
                                                    <select name="experience_type" class="form-control select2 <?= session('errors.experience_type') ? 'is-invalid' : '' ?>" style="width: 100%;">
                                                        <option value="" disabled>-- Pilih Jenis Pengalaman --</option>
                                                        <?php foreach ($typeBadges as $key => $type): ?>
                                                            <option value="<?= $key ?>" <?= old('experience_type', $expType) === $key ? 'selected' : '' ?>>
                                                                <?= $type['label'] ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <?php if (session('errors.experience_type')): ?>
                                                        <span class="invalid-feedback d-block"><?= session('errors.experience_type') ?></span>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="col-12 col-md-6 mb-3">
                                                    <label class="font-weight-bold text-small-c text-dark">Tahun Pelaksanaan <span class="text-danger">*</span></label>
                                                    <select name="year_occurred" class="form-control select2 <?= session('errors.year_occurred') ? 'is-invalid' : '' ?>" style="width: 100%;">
                                                        <option value="" disabled>-- Pilih Tahun --</option>
                                                        <?php for ($y = $currentYear; $y >= 1990; $y--): ?>
                                                            <option value="<?= $y ?>" <?= old('year_occurred', $expData->year_occurred) == $y ? 'selected' : '' ?>>
                                                                <?= $y ?>
                                                            </option>
                                                        <?php endfor; ?>
                                                    </select>
                                                    <?php if (session('errors.year_occurred')): ?>
                                                        <span class="invalid-feedback d-block"><?= session('errors.year_occurred') ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>

                                            <div class="form-group mb-3 p-3 bg-light rounded-lg border-0">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="is_current_edit_<?= $expId ?>" name="is_current" value="1" <?= old('is_current', !empty($expData->is_current)) ? 'checked' : '' ?>>
                                                    <label class="custom-control-label font-weight-semibold text-dark text-xs-c cursor-pointer" for="is_current_edit_<?= $expId ?>">
                                                        Saya masih aktif / kegiatan ini berlangsung sampai sekarang
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="form-group mb-0">
                                                <label class="font-weight-bold text-small-c text-dark">Deskripsi Kegiatan</label>
                                                <textarea name="description" class="form-control <?= session('errors.description') ? 'is-invalid' : '' ?>" rows="4" placeholder="Jelaskan peran dan tanggung jawab Anda..."><?= old('description', $expData->description) ?></textarea>
                                                <?php if (session('errors.description')): ?>
                                                    <span class="invalid-feedback d-block"><?= session('errors.description') ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="modal-footer bg-light px-3 px-md-4 pt-3 pb-4 border-top-0 d-flex flex-row justify-content-end align-items-center gap-2">
                                            <button type="button" class="btn btn-outline-secondary px-2 px-sm-4 font-weight-semibold btn-modal-batal" data-dismiss="modal">
                                                Batal
                                            </button>
                                            <button type="submit" class="btn btn-primary px-2 px-sm-4 font-weight-bold shadow-sm btn-modal-simpan">
                                                <i class="fa fa-check-circle mr-1.5"></i> Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination-container flex-column flex-sm-row gap-3 mt-4">
                        <div class="pagination-info pb-sm-0 pb-3">
                            Menampilkan <span class="font-weight-bold text-primary"><?= $offset + 1 ?></span> - <span class="font-weight-bold text-primary"><?= min($offset + $perPage, $totalExperiences) ?></span> dari <span class="font-weight-bold text-primary"><?= $totalExperiences ?></span> pengalaman
                        </div>

                        <ul class="pagination-nav m-0 p-0 d-flex align-items-center">
                            <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= $currentPage > 1 ? '?page=' . ($currentPage - 1) : '#' ?>" aria-label="Previous">
                                    <i class="fa fa-chevron-left" style="font-size: 0.75rem;"></i>
                                </a>
                            </li>

                            <div class="d-none d-md-flex align-items-center">
                                <?php if ($totalPages <= 5): ?>
                                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                        <li class="page-item <?= $p === $currentPage ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $p ?>"><?= $p ?></a>
                                        </li>
                                    <?php endfor; ?>
                                <?php else: ?>
                                    <li class="page-item <?= $currentPage === 1 ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=1">1</a>
                                    </li>
                                    <li class="page-item <?= $currentPage === 2 ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=2">2</a>
                                    </li>

                                    <?php if ($currentPage > 2 && $currentPage < $totalPages - 1): ?>
                                        <li class="page-item active">
                                            <a class="page-link" href="?page=<?= $currentPage ?>"><?= $currentPage ?></a>
                                        </li>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php else: ?>
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    <?php endif; ?>

                                    <li class="page-item <?= $currentPage === ($totalPages - 1) ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $totalPages - 1 ?>"><?= $totalPages - 1 ?></a>
                                    </li>
                                    <li class="page-item <?= $currentPage === $totalPages ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $totalPages ?>"><?= $totalPages ?></a>
                                    </li>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex d-md-none align-items-center">
                                <?php if ($totalPages <= 3): ?>
                                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                                        <li class="page-item <?= $p === $currentPage ? 'active' : '' ?>">
                                            <a class="page-link" href="?page=<?= $p ?>"><?= $p ?></a>
                                        </li>
                                    <?php endfor; ?>
                                <?php else: ?>
                                    <li class="page-item <?= $currentPage === 1 ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=1">1</a>
                                    </li>

                                    <?php if ($currentPage > 1 && $currentPage < $totalPages): ?>
                                        <li class="page-item active">
                                            <a class="page-link" href="?page=<?= $currentPage ?>"><?= $currentPage ?></a>
                                        </li>
                                    <?php endif; ?>

                                    <li class="page-item disabled"><span class="page-link">...</span></li>

                                    <li class="page-item <?= $currentPage === $totalPages ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $totalPages ?>"><?= $totalPages ?></a>
                                    </li>
                                <?php endif; ?>
                            </div>

                            <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= $currentPage < $totalPages ? '?page=' . ($currentPage + 1) : '#' ?>" aria-label="Next">
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

<div class="modal fade" id="modalAddExperience" tabindex="-1" role="dialog" aria-labelledby="modalAddExperienceLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg rounded-lg overflow-hidden">

            <div class="modal-header bg-light border-bottom-0 pb-3 pt-4 px-3 px-md-4 d-flex align-items-center justify-content-between">
                <h5 class="modal-title font-weight-bold text-primary d-flex align-items-center gap-1 mb-0" id="modalAddExperienceLabel">
                    <span class="avatar-icon-wrapper bg-soft-primary text-primary rounded-circle mr-2 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px; background-color: rgba(10, 36, 129, 0.2);">
                        <i class="fa fa-briefcase"></i>
                    </span>
                    Tambah Pengalaman Baru
                </h5>
                <button type="button" class="close close-btn-c p-2 m-0" data-dismiss="modal" aria-label="Close" style="outline: none;">
                    <span class="text-primary" aria-hidden="true" style="font-size: 1.5rem;">&times;</span>
                </button>
            </div>

            <div class="px-3 px-md-4 mt-3 text-muted small d-flex align-items-center gap-2">
                <i class="fa fa-info-circle text-primary mr-1.5"></i>
                <p class="p-0 m-0">Lengkapi formulir di bawah ini. Tanda (<span class="text-danger font-weight-bold mx-0.5">*</span>) menunjukkan bidang yang wajib diisi.</p>
            </div>

            <form action="<?= base_url('student/experiences/store') ?>" method="POST" class="needs-validation">
                <?= csrf_field() ?>

                <div class="modal-body p-3 p-md-4">
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label class="font-weight-bold text-small-c text-dark">Judul Peran / Posisi <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control <?= session('errors.title') ? 'is-invalid' : '' ?>" placeholder="Contoh: Ketua Himpunan / Frontend Dev" value="<?= old('title') ?>">
                            <?php if (session('errors.title')): ?>
                                <span class="invalid-feedback d-block"><?= session('errors.title') ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="font-weight-bold text-small-c text-dark">Nama Instansi / Organisasi <span class="text-danger">*</span></label>
                            <input type="text" name="organization_name" class="form-control <?= session('errors.organization_name') ? 'is-invalid' : '' ?>" placeholder="Contoh: BEM / PT. Tech Solutions" value="<?= old('organization_name') ?>">
                            <?php if (session('errors.organization_name')): ?>
                                <span class="invalid-feedback d-block"><?= session('errors.organization_name') ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label class="font-weight-bold text-small-c text-dark">Jenis Pengalaman <span class="text-danger">*</span></label>
                            <select name="experience_type" class="form-control select2 <?= session('errors.experience_type') ? 'is-invalid' : '' ?>" style="width: 100%;">
                                <option value="" disabled <?= old('experience_type') ? '' : 'selected' ?>>-- Pilih Jenis Pengalaman --</option>
                                <?php foreach ($typeBadges as $key => $type): ?>
                                    <option value="<?= $key ?>" <?= old('experience_type') === $key ? 'selected' : '' ?>>
                                        <?= $type['label'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (session('errors.experience_type')): ?>
                                <span class="invalid-feedback d-block"><?= session('errors.experience_type') ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <label class="font-weight-bold text-small-c text-dark">Tahun Pelaksanaan <span class="text-danger">*</span></label>
                            <select name="year_occurred" class="form-control select2 <?= session('errors.year_occurred') ? 'is-invalid' : '' ?>" style="width: 100%;">
                                <option value="" disabled <?= old('year_occurred') ? '' : 'selected' ?>>-- Pilih Tahun --</option>
                                <?php for ($y = $currentYear; $y >= 1990; $y--): ?>
                                    <option value="<?= $y ?>" <?= old('year_occurred', $currentYear) == $y ? 'selected' : '' ?>>
                                        <?= $y ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <?php if (session('errors.year_occurred')): ?>
                                <span class="invalid-feedback d-block"><?= session('errors.year_occurred') ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group mb-3 p-3 bg-light rounded-lg border-0">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_current_add" name="is_current" value="1" <?= old('is_current') ? 'checked' : '' ?>>
                            <label class="custom-control-label font-weight-semibold text-dark text-xs-c cursor-pointer" for="is_current_add">
                                Saya masih aktif / kegiatan ini berlangsung sampai sekarang
                            </label>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="font-weight-bold text-small-c text-dark">Deskripsi Kegiatan</label>
                        <textarea name="description" class="form-control <?= session('errors.description') ? 'is-invalid' : '' ?>" rows="4" placeholder="Jelaskan secara singkat tugas dan tanggung jawab Anda..."><?= old('description') ?></textarea>
                        <?php if (session('errors.description')): ?>
                            <span class="invalid-feedback d-block"><?= session('errors.description') ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="modal-footer bg-light px-3 px-md-4 pt-3 pb-4 border-top-0 d-flex flex-row justify-content-end align-items-center gap-2">
                    <button type="button" class="btn btn-outline-secondary px-2 px-sm-4 font-weight-semibold btn-modal-batal" data-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-2 px-sm-4 font-weight-bold shadow-sm btn-modal-simpan">
                        <i class="fa fa-check-circle mr-1.5"></i> Simpan Data
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<?= $this->endSection() ?>