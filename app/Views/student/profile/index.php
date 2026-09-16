<?= $this->extend('layouts/student') ?>

<?= $this->section('content') ?>
<?php

$faculties     = $faculties ?? [];
$studyPrograms = $studyPrograms ?? [];
$classGroups   = $classGroups ?? [];

if (!isset($student) || empty($student)) {
    $student = (object) [
        'student_number'   => '',
        'full_name'        => '',
        'email'            => '',
        'phone_number'     => '',
        'faculty_id'       => '',
        'study_program_id' => '',
        'class_id'         => '',
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
                                <select class="form-control select2 <?= session('errors.faculty_id') ? 'is-invalid' : '' ?>" id="faculty_id" name="faculty_id" style="width: 100%;">
                                    <option value="" disabled <?= old('faculty_id', $student->faculty_id) ? '' : 'selected' ?>>-- Pilih Fakultas --</option>
                                    <?php foreach ($faculties as $faculty): ?>
                                        <option value="<?= $faculty->id ?>" <?= old('faculty_id', $student->faculty_id) == $faculty->id ? 'selected' : '' ?>>
                                            <?= esc($faculty->faculty_name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (session('errors.faculty_id')): ?>
                                    <span class="invalid-feedback d-block"><?= session('errors.faculty_id') ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 col-lg-6 mb-3">
                                <label class="control-label font-weight-bold">Program Studi <span class="text-danger">*</span></label>
                                <select class="form-control select2 <?= session('errors.study_program_id') ? 'is-invalid' : '' ?>" id="study_program_id" name="study_program_id" style="width: 100%;" <?= empty($studyPrograms) ? 'disabled' : '' ?>>
                                    <option value="" disabled <?= old('study_program_id', $student->study_program_id) ? '' : 'selected' ?>>-- Pilih Program Studi --</option>
                                    <?php foreach ($studyPrograms as $program): ?>
                                        <option value="<?= $program->id ?>" <?= old('study_program_id', $student->study_program_id) == $program->id ? 'selected' : '' ?>>
                                            <?= esc($program->program_name) ?> (<?= esc($program->degree_level ?? 'S1') ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (session('errors.study_program_id')): ?>
                                    <span class="invalid-feedback d-block"><?= session('errors.study_program_id') ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-lg-6 mb-3">
                                <label class="control-label font-weight-bold">Kelas Group <span class="text-danger">*</span></label>
                                <select class="form-control select2 <?= session('errors.class_id') ? 'is-invalid' : '' ?>" id="class_id" name="class_id" style="width: 100%;" <?= empty($classGroups) ? 'disabled' : '' ?>>
                                    <option value="" disabled <?= old('class_id', $student->class_id) ? '' : 'selected' ?>>-- Pilih Kelas --</option>
                                    <?php foreach ($classGroups as $class): ?>
                                        <option value="<?= $class->id ?>" <?= old('class_id', $student->class_id) == $class->id ? 'selected' : '' ?>>
                                            <?= esc($class->class_name) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (session('errors.class_id')): ?>
                                    <span class="invalid-feedback d-block"><?= session('errors.class_id') ?></span>
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
                                <li>Mengenakan kemeja putih, jas almamater, serta <strong>dasi</strong> (khusus laki-laki).</li>
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

<?= $this->endSection() ?>