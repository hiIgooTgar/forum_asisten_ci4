<?= $this->extend('layouts/student') ?>

<?= $this->section('content') ?>
<?php

$validationErrors = session()->getFlashdata('errors');

?>

<div class="app-title shadow-sm bg-white rounded p-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
    <div>
        <h1 class="h4 font-weight-bold mb-1 text-dark d-flex align-items-center">
            <i class="fa fa-user-shield text-primary mr-2"></i> Pengaturan Akun &amp; Keamanan
        </h1>
        <p class="mb-0 text-muted">Kelola kata sandi akun Anda dan pengaturan privasi penghapusan akun</p>
    </div>
    <ul class="app-breadcrumb breadcrumb side bg-transparent p-0 m-0 mt-2 mt-sm-0">
        <li class="breadcrumb-item"><a href="<?= base_url('student/dashboard'); ?>"><i class="fa fa-home text-muted"></i></a></li>
        <li class="breadcrumb-item active text-primary font-weight-semibold">Pengaturan Akun</li>
    </ul>
</div>

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
    <div class="col-12">
        <div class="tile p-3 p-md-4 bg-white shadow-sm rounded border-0 mb-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center pb-3 mb-4 border-bottom gap-4">
                <div>
                    <h5 class="font-weight-bold mb-1 text-dark border-left-primary pl-2">Ubah Kata Sandi (Password)</h5>
                    <p class="text-secondary text-small-c mb-0">Perbarui kata sandi Anda secara berkala untuk menjaga keamanan akun.</p>
                </div>
            </div>

            <form id="formChangePassword" action="<?= base_url('student/account-settings/change-password') ?>" method="POST" novalidate>
                <?= csrf_field() ?>

                <div class="p-3 bg-soft-primary border-left-primary mb-4 rounded-right">
                    <div class="d-flex align-items-center mb-1">
                        <i class="fa fa-info-circle text-primary mr-2"></i>
                        <h6 class="font-weight-bold mb-0 text-dark">Ketentuan Kata Sandi Pendaftaran</h6>
                    </div>
                    <ul class="mb-0 pl-4 text-secondary" style="font-size: 0.85rem; line-height: 1.6;">
                        <li><small class="text-xs-c d-block">Panjang kata sandi minimal <strong class="text-primary">8 karakter</strong>.</small></li>
                        <li><small class="text-xs-c d-block">Wajib mengombinasikan minimal <strong class="text-primary">1 huruf kapital (A-Z)</strong>, <strong class="text-primary">1 angka (0-9)</strong>, dan <strong class="text-primary">1 karakter simbol/spesial</strong> (contoh: `@`, `#`, `$`, `!`, `_`).</small></li>
                        <li><small class="text-xs-c d-block">Kata sandi baru <strong class="text-primary">tidak boleh sama</strong> dengan kata sandi saat ini.</small></li>
                        <li><small class="text-xs-c d-block">astikan Anda mengingat kata sandi baru Anda sebelum melakukan penyimpanan.</small></li>
                    </ul>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-4 mb-3">
                        <label class="control-label font-weight-bold">Kata Sandi Saat Ini <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input class="form-control <?= session('errors.current_password') ? 'is-invalid' : '' ?>"
                                type="password"
                                id="current_password"
                                name="current_password"
                                placeholder="Masukkan password saat ini">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary toggle-pwd" type="button" data-target="current_password">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <?php if (session('errors.current_password')): ?>
                            <span class="invalid-feedback d-block"><?= session('errors.current_password') ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="col-12 col-lg-4 mb-3">
                        <label class="control-label font-weight-bold">Kata Sandi Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input class="form-control <?= session('errors.new_password') ? 'is-invalid' : '' ?>"
                                type="password"
                                id="new_password"
                                name="new_password"
                                placeholder="Masukkan password baru Anda">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary toggle-pwd" type="button" data-target="new_password">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <?php if (session('errors.new_password')): ?>
                            <span class="invalid-feedback d-block"><?= session('errors.new_password') ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="col-12 col-lg-4 mb-3">
                        <label class="control-label font-weight-bold">Konfirmasi Kata Sandi Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input class="form-control <?= session('errors.confirm_new_password') ? 'is-invalid' : '' ?>"
                                type="password"
                                id="confirm_new_password"
                                name="confirm_new_password"
                                placeholder="Ulangi password baru">
                            <div class="input-group-append">
                                <button class="btn btn-outline-primary toggle-pwd" type="button" data-target="confirm_new_password">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <?php if (session('errors.confirm_new_password')): ?>
                            <span class="invalid-feedback d-block"><?= session('errors.confirm_new_password') ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="tile-footer d-flex flex-row align-items-center mt-3 pt-3 border-top gap-2">
                    <button type="button" class="btn text-white px-4 font-weight-bold shadow-sm" style="background-color: #0a2481;" data-toggle="modal" data-target="#modalConfirmChangePassword">
                        <i class="fa fa-save mr-1"></i> Perbarui Kata Sandi
                    </button>
                    <a class="btn btn-secondary px-4" href="<?= base_url('student/account-settings') ?>">Batal</a>
                </div>

                <div class="modal fade modal-modern" id="modalConfirmChangePassword" tabindex="-1" role="dialog" aria-labelledby="modalChangePwdTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content border-0 shadow-lg">

                            <div class="modal-header text-white" style="background-color: #0a2481;">
                                <h5 class="modal-title font-weight-bold d-flex align-items-center" id="modalChangePwdTitle" style="font-size: 1rem;">
                                    <i class="fa fa-key mr-2"></i> Konfirmasi Perubahan Kata Sandi
                                </h5>
                                <button type="button" class="close close-btn-c text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body p-4 text-center">
                                <div class="modal-primary-icon-wrapper">
                                    <i class="fa fa-shield-halved"></i>
                                </div>

                                <h5 class="font-weight-bold text-dark mb-2">Perbarui Kata Sandi Akun?</h5>
                                <p class="text-muted text-small-c mb-3">
                                    Pastikan kata sandi baru yang Anda ketikkan sudah sesuai dan mudah diingat sebelum melanjutkan.
                                </p>

                                <div class="modal-alert-box">
                                    <div class="d-flex align-items-start">
                                        <i class="fa fa-circle-info text-primary mr-2 mt-1" style="font-size: 0.85rem; color: #0a2481 !important;"></i>
                                        <p class="text-xs-c text-secondary mb-0 text-left" style="line-height: 1.45;">
                                            Sesi login Anda akan <strong class="text-primary" style="color: #0a2481 !important;">tetap aktif</strong>, namun Anda wajib menggunakan <strong class="text-dark">kata sandi baru</strong> ini saat melakukan login di perangkat lain atau sesi berikutnya.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer bg-light border-top-0 px-4 py-3 d-flex justify-content-end" style="gap: 8px;">
                                <button type="button" class="btn btn-light border px-4 font-weight-semibold" data-dismiss="modal">
                                    Batal
                                </button>
                                <button type="submit" form="formChangePassword" class="btn text-white px-4 font-weight-bold shadow-xs" style="background-color: #0a2481;">
                                    <i class="fa fa-check mr-1.5"></i> Ya, Perbarui Sekarang
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="tile p-3 p-md-4 bg-white shadow-sm rounded-lg border-left-danger-custom" style="border-left: 5px solid #dc3545; border-top: 1px solid #f8d7da; border-right: 1px solid #f8d7da; border-bottom: 1px solid #f8d7da;">
            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between mb-4 pb-3 border-bottom">
                <div class="d-flex align-items-center mb-2 mb-sm-0">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0" style="width: 46px; height: 46px; background-color: #fff5f5; color: #dc3545; border: 1px solid #feb2b2;">
                        <i class="fa fa-triangle-exclamation fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="font-weight-bold mb-1 text-danger">Zona Bahaya: Hapus Akun</h5>
                        <span class="text-muted text-small-c">Tindakan ini bersifat permanen dan tidak dapat dibatalkan</span>
                    </div>
                </div>
                <span class="badge badge-danger px-2 py-2 font-weight-semibold text-uppercase mt-1 mt-sm-0" style="font-size: 0.67rem; letter-spacing: 0.5px;">
                    Aksi Permanen
                </span>
            </div>

            <div class="p-3 mb-4 rounded" style="background-color: #fff5f5; border: 1px dashed #f5c6cb;">
                <div class="d-flex align-items-center mb-1">
                    <i class="fa fa-info-circle text-danger mr-2"></i>
                    <h6 class="font-weight-bold mb-0 text-dark">Apabila Anda menghapus akun ini, sistem akan membersihkan</h6>
                </div>
                <ul class="mb-0 pl-4 text-secondary" style="font-size: 0.85rem; line-height: 1.6;">
                    <li><small class="text-xs-c d-block">Seluruh berkas dokumen pendaftaran dan foto profil Anda.</small></li>
                    <li><small class="text-xs-c d-block">Riwayat pendaftaran mata kuliah dan verifikasi seleksi Asisten Praktikum.</small></li>
                    <li><small class="text-xs-c d-block">Akses masuk (login) ke portal mahasiswa secara permanen.</small></li>
                </ul>
            </div>

            <div class="d-flex justify-content-xs-center justify-content-sm-start">
                <button type="button" class="btn btn-danger font-weight-bold px-4 py-2 shadow-xs d-inline-flex align-items-center justify-content-center" data-toggle="modal" data-target="#modalDeleteAccount" style="gap: 8px;">
                    <i class="fa fa-trash-can"></i>
                    <span>Hapus Akun Saya Permanen</span>
                </button>
            </div>

            <div class="modal fade modal-modern" id="modalDeleteAccount" tabindex="-1" role="dialog" aria-labelledby="modalDeleteTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title font-weight-bold d-flex align-items-center" id="modalDeleteTitle" style="font-size: 1rem;">
                                <i class="fa fa-triangle-exclamation mr-2"></i> Konfirmasi Hapus Akun Permanen
                            </h5>
                            <button type="button" class="close close-btn-c text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form action="<?= base_url('student/account-settings/delete-account') ?>" method="POST" class="m-0">
                            <?= csrf_field() ?>

                            <div class="modal-body p-4 text-center">
                                <div class="modal-warning-icon-wrapper" style="background-color: rgba(220, 53, 69, 0.12); color: #dc3545;">
                                    <i class="fa fa-user-slash"></i>
                                </div>

                                <h5 class="font-weight-bold text-dark mb-2">Tindakan Ini Tidak Dapat Dibatalkan!</h5>
                                <p class="text-muted text-small-c mb-3">
                                    Seluruh data pendaftaran, riwayat mata kuliah, dan berkas Anda akan dihapus secara permanen dari sistem.
                                </p>

                                <div class="modal-alert-box mb-3" style="background-color: #fff5f5; border: 1px solid #feb2b2;">
                                    <div class="d-flex align-items-start text-left">
                                        <i class="fa fa-circle-exclamation text-danger mr-2 mt-1" style="font-size: 0.85rem;"></i>
                                        <p class="text-xs-c text-secondary mb-0" style="line-height: 1.45;">
                                            Menghapus akun <strong class="text-danger"><?= esc($student->student_number ?? '') ?></strong> akan mencabut seluruh status kepesertaan seleksi Asisten Praktikum Anda secara <strong class="text-danger">permanen</strong>.
                                        </p>
                                    </div>
                                </div>

                                <div class="form-group text-left mb-0">
                                    <label class="font-weight-bold text-dark small mb-1">
                                        Kata Sandi Konfirmasi <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password"
                                            name="delete_confirm_password"
                                            id="delete_confirm_password"
                                            class="form-control <?= (isset($errors['delete_confirm_password'])) ? 'is-invalid' : '' ?>"
                                            placeholder="Masukkan kata sandi untuk verifikasi">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-primary toggle-pwd" type="button" data-target="delete_confirm_password">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </div>
                                        <?php if (isset($errors['delete_confirm_password'])): ?>
                                            <div class="invalid-feedback d-block">
                                                <?= esc($errors['delete_confirm_password']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                            </div>

                            <div class="modal-footer bg-light border-top-0 px-4 py-3 d-flex justify-content-end" style="gap: 8px;">
                                <button type="button" class="btn btn-light border px-4 font-weight-semibold" data-dismiss="modal">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-danger px-4 font-weight-bold shadow-xs">
                                    <i class="fa fa-trash-can mr-1.5"></i> Ya, Hapus Akun Permanen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>