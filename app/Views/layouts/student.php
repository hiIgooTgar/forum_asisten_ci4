<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="Forum Asisten - Student Panel Universitas Amikom Purwokerto">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?= esc($title ?? 'Student Panel'); ?> - Forum Asisten</title>

    <link rel="stylesheet" href="<?= base_url('assets/layout/css/base.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layout/css/components-form.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layout/css/components-page.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layout/css/components-table.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layout/css/components-notify.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layout/css/components-variables.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layout/css/navbar.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layout/css/sidebar.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layout/css/main.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/font/font-style.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layout/css/dataTables.bootstrap.min.css'); ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />

    <link rel="stylesheet" href="<?= base_url('assets/layout/css/student/profile.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layout/css/student/experience.css'); ?>">

    <link rel="stylesheet" href="<?= base_url('assets/layout/css/custom/template_student.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layout/css/custom/layout.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layout/css/custom/cropper.css'); ?>">

</head>

<body class="app sidebar-mini">
    <header class="app-header">
        <a class="app-header__logo" href="<?= base_url('student/dashboard'); ?>">
            <img src="<?= base_url('assets/images/logo/logo-fa-white-v2.png'); ?>" alt="Forum Asisten" style="height: 30px; padding: 2px 0;">
            <span class="d-none d-sm-inline ml-2" style="font-size: 16px; font-weight: 600;">Forum Asisten</span>
        </a>
        <a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>

        <ul class="app-nav">
            <li class="dropdown">
                <a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Show notifications">
                    <i class="fa fa-bell fa-lg"></i>
                    <span class="badge badge-danger badge-pill notification-badge">3</span>
                </a>
                <ul class="app-notification dropdown-menu dropdown-menu-right">
                    <li class="app-notification__title">Anda memiliki 3 notifikasi baru.</li>
                    <div class="app-notification__content">
                        <li>
                            <a class="app-notification__item" href="#">
                                <span class="app-notification__icon">
                                    <span class="fa-stack fa-lg">
                                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                                        <i class="fa fa-bullhorn fa-stack-1x fa-inverse"></i>
                                    </span>
                                </span>
                                <div>
                                    <p class="app-notification__message">Pengumuman seleksi berkas</p>
                                    <p class="app-notification__meta">10 menit lalu</p>
                                </div>
                            </a>
                        </li>
                    </div>
                    <li class="app-notification__footer"><a href="#">Lihat semua notifikasi.</a></li>
                </ul>
            </li>

            <li class="dropdown">
                <a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu">
                    <i class="fa fa-user fa-lg"></i>
                    <span class="user-name d-none d-md-inline ml-1">
                        <?= esc(session()->get('full_name') ?? 'Mahasiswa'); ?>
                    </span>
                </a>
                <ul class="dropdown-menu settings-menu dropdown-menu-right">
                    <li><a class="dropdown-item" href="<?= base_url('student/profile'); ?>"><i class="fa fa-user fa-lg"></i> Profil Saya</a></li>
                    <li><a class="dropdown-item" href="<?= base_url('student/settings'); ?>"><i class="fa fa-cog fa-lg"></i> Pengaturan</a></li>
                    <li class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="<?= base_url('auth/logout'); ?>">
                            <i class="fa fa-sign-out fa-lg"></i> Keluar
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </header>

    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>

    <aside class="app-sidebar">
        <div class="app-sidebar__user">
            <?php
            $profileImg = session()->get('profile');
            $avatar = (empty($profileImg) || $profileImg === 'profile-default.png')
                ? base_url('assets/images/profile/profile-default.png')
                : base_url('uploads/profile_student/' . $profileImg);

            $fullName = session()->get('full_name') ?: 'Mahasiswa';
            ?>
            <img class="app-sidebar__user-avatar" src="<?= $avatar; ?>" alt="User Image">
            <div class="app-sidebar__user-info">
                <p class="app-sidebar__user-name" title="<?= esc($fullName); ?>">
                    <?= esc($fullName); ?>
                </p>
                <p class="app-sidebar__user-designation">Mahasiswa</p>
            </div>
        </div>

        <?php $uri = service('uri'); ?>
        <ul class="app-menu">
            <li>
                <a class="app-menu__item <?= ($uri->getSegment(2) == 'dashboard' || $uri->getSegment(2) == '') ? 'active' : ''; ?>" href="<?= base_url('student/dashboard'); ?>">
                    <i class="app-menu__icon fa fa-dashboard"></i>
                    <span class="app-menu__label">Dashboard</span>
                </a>
            </li>

            <li class="treeview <?= in_array($uri->getSegment(2), ['profile', 'documents', 'experiences']) ? 'is-expanded' : ''; ?>">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-id-card"></i>
                    <span class="app-menu__label">Biodata Mahasiswa</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a class="treeview-item <?= ($uri->getSegment(2) == 'profile') ? 'active' : ''; ?>" href="<?= base_url('student/profile'); ?>">
                            <i class="icon fa fa-circle-o"></i> Profil Diri
                        </a>
                    </li>
                    <li>
                        <a class="treeview-item <?= ($uri->getSegment(2) == 'experiences') ? 'active' : ''; ?>" href="<?= base_url('student/experiences'); ?>">
                            <i class="icon fa fa-circle-o"></i> Pengalaman Organisasi
                        </a>
                    </li>
                </ul>
            </li>

            <li class="treeview <?= in_array($uri->getSegment(2), ['registration', 'history']) ? 'is-expanded' : ''; ?>">
                <a class="app-menu__item" href="#" data-toggle="treeview">
                    <i class="app-menu__icon fa fa-file-text"></i>
                    <span class="app-menu__label">Pendaftaran Asisten</span>
                    <i class="treeview-indicator fa fa-angle-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a class="treeview-item <?= ($uri->getSegment(2) == 'registration') ? 'active' : ''; ?>" href="<?= base_url('student/registration'); ?>">
                            <i class="icon fa fa-circle-o"></i> Daftar Matakuliah
                        </a>
                    </li>
                    <li>
                        <a class="treeview-item <?= ($uri->getSegment(2) == 'documents') ? 'active' : ''; ?>" href="<?= base_url('student/documents'); ?>">
                            <i class="icon fa fa-circle-o"></i> Dokumen Berkas
                        </a>
                    </li>
                    <li>
                        <a class="treeview-item <?= ($uri->getSegment(2) == 'history') ? 'active' : ''; ?>" href="<?= base_url('student/history'); ?>">
                            <i class="icon fa fa-circle-o"></i> Riwayat Pendaftaran
                        </a>
                    </li>
                </ul>
            </li>

            <li>
                <a class="app-menu__item <?= ($uri->getSegment(2) == 'announcements') ? 'active' : ''; ?>" href="<?= base_url('student/announcements'); ?>">
                    <i class="app-menu__icon fa fa-bullhorn"></i>
                    <span class="app-menu__label">Pengumuman</span>
                </a>
            </li>
        </ul>
    </aside>

    <main class="app-content">
        <?= $this->include('partials/toast') ?>
        <?= $this->include('components/confirm_modal') ?>
        <?= $this->renderSection('content') ?>
    </main>

    <div class="modal fade" id="globalCropModal" tabindex="-1" role="dialog" aria-labelledby="globalCropModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content crop-modal-content">

                <div class="modal-header crop-modal-header align-items-center">
                    <h5 class="modal-title crop-modal-title d-flex align-items-center gap-2" id="globalCropModalLabel">
                        <i class="fa fa-crop mr-2"></i> Potong Gambar
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body p-0 crop-modal-body">
                    <div class="crop-img-container">
                        <img id="globalImageToCrop" src="" alt="Crop Image Target">
                    </div>
                </div>

                <div class="modal-footer crop-modal-footer">
                    <button type="button" class="btn crop-btn-action crop-btn-save" id="globalCropBtn">
                        <i class="fa fa-check mr-1"></i> Potong & Simpan
                    </button>
                    <button type="button" class="btn crop-btn-action crop-btn-cancel" data-dismiss="modal">
                        Batal
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
        window.baseUrl = "<?= base_url() ?>";
    </script>

    <script src="<?= base_url('assets/layout/js/jquery-3.3.1.min.js'); ?>"></script>
    <script src="<?= base_url('assets/layout/js/popper.min.js'); ?>"></script>
    <script src="<?= base_url('assets/layout/js/bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('assets/layout/js/main.js'); ?>"></script>
    <script src="<?= base_url('assets/layout/js/plugins/pace.min.js'); ?>"></script>
    <script src="<?= base_url('assets/layout/js/plugins/dataTables.bootstrap.min.js'); ?>"></script>
    <script src="<?= base_url('assets/layout/js/plugins/jquery.dataTables.min.js'); ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <script src="<?= base_url('assets/layout/js/custom/layout-admin.js'); ?>"></script>
    <script src="<?= base_url('assets/layout/js/custom/cropper.js'); ?>"></script>
    <script src="<?= base_url('assets/layout/js/custom/wilayah.js'); ?>"></script>

    <script src="<?= base_url('assets/layout/js/student/profile.js'); ?>"></script>
    <script src="<?= base_url('assets/layout/js/student/experience.js'); ?>"></script>

    <?= $this->include('components/confirm_modal') ?>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "-- Pilih Data --",
                allowClear: true,
                width: '100%'
            });
        });
    </script>

    <?= $this->renderSection('scripts') ?>
</body>

</html>