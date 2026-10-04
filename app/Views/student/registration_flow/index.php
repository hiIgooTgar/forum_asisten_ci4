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

$logoImg = (!empty($appProfile->logo) && file_exists(FCPATH . 'uploads/logo/' . $appProfile->logo))
    ? base_url('uploads/logo/' . $appProfile->logo)
    : base_url('assets/images/logo/' . ($appProfile->logo ?? 'logo-fa.png'));

$logoImgWhite = (!empty($appProfile->logo_white) && file_exists(FCPATH . 'uploads/logo/' . $appProfile->logo_white))
    ? base_url('uploads/logo/' . $appProfile->logo_white)
    : base_url('assets/images/logo/' . ($appProfile->logo_white ?? 'logo-fa.png'));

$activeTab = $activeTab ?? 'requirements';
?>

<?= $this->extend('layouts/student') ?>

<?= $this->section('content') ?>

<div class="app-title shadow-sm bg-white rounded p-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center">
    <div>
        <h1 class="h4 font-weight-bold mb-1 text-dark d-flex align-items-center">
            <i class="fa fa-user-circle text-primary mr-2"></i> Informasi & Alur Pendaftaran Asisten
        </h1>
        <p class="mb-0">Panduan lengkap persyaratan, alur pendaftaran, dan penawaran mata kuliah aktif</p>
    </div>
    <ul class="app-breadcrumb breadcrumb side bg-transparent p-0 m-0 mt-2 mt-sm-0">
        <li class="breadcrumb-item"><a href="<?= base_url('student/dashboard'); ?>"><i class="fa fa-home text-muted"></i></a></li>
        <li class="breadcrumb-item active text-primary font-weight-semibold">Alur Pendaftaran</li>
    </ul>
</div>


<div class="row">
    <div class="col-12">
        <div class="tile p-3 p-md-4 bg-white shadow-sm rounded border-0">
            <ul class="nav nav-pills custom-pills mb-4 border-bottom pb-3 flex-column flex-md-row gap-2" id="guideTabs">
                <li class="nav-item">
                    <a class="nav-link <?= $activeTab === 'requirements' ? 'active' : '' ?> font-weight-bold text-center text-md-left" href="<?= base_url('student/registration-flow?tab=requirements') ?>">
                        <i class="fa fa-file-text mr-2"></i>1. Syarat & Ketentuan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activeTab === 'workflow' ? 'active' : '' ?> font-weight-bold text-center text-md-left" href="<?= base_url('student/registration-flow?tab=workflow') ?>">
                        <i class="fa fa-sitemap mr-2"></i>2. Alur & Langkah Pendaftaran
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activeTab === 'courses' ? 'active' : '' ?> font-weight-bold text-center text-md-left" href="<?= base_url('student/registration-flow?tab=courses') ?>">
                        <i class="fa fa-list-alt mr-2"></i>3. Penawaran Mata Kuliah
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade <?= $activeTab === 'requirements' ? 'show active' : '' ?>">
                    <div class="p-3 bg-soft-primary-custom-v3 border-left-primary rounded mb-4">
                        <h6 class="font-weight-bold text-dark mb-1 d-flex align-items-center">
                            <i class="fa fa-info-circle text-primary mr-2"></i> Ketentuan Umum Pendaftaran
                        </h6>
                        <small class="text-xs-c text-secondary-c d-block" style="line-height: 1.6;">
                            Forum Asisten membuka pendaftaran bagi mahasiswa aktif yang memenuhi kriteria kualifikasi akademik dan administratif.
                        </small>
                    </div>

                    <h5 style="margin-top: 2.6rem;" class="font-weight-bold text-dark mb-3 border-left-primary pl-2">Persyaratan Kualifikasi Akademik</h5>

                    <div class="row mb-4">
                        <div class="col-md-6 col-12 mb-3">
                            <div class="d-flex align-items-start p-3 border rounded bg-light h-100">
                                <div class="req-icon-box mr-3"><i class="fa fa-graduation-cap fa-lg"></i></div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-dark">IPK Minimal</h6>
                                    <p class="mb-0 text-muted text-xs-c">Mahasiswa memiliki Indeks Prestasi Kumulatif (IPK) minimal <strong class="text-primary">3.00</strong>.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mb-3">
                            <div class="d-flex align-items-start p-3 border rounded bg-light h-100">
                                <div class="req-icon-box mr-3"><i class="fa fa-history fa-lg"></i></div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-dark">Minimal Semester</h6>
                                    <p class="mb-0 text-muted text-xs-c">Telah menyelesaikan perkuliahan minimal hingga <strong class="text-primary">Semester 2</strong>.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mb-3">
                            <div class="d-flex align-items-start p-3 border rounded bg-light h-100">
                                <div class="req-icon-box mr-3"><i class="fa fa-book fa-lg"></i></div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-dark">Nilai Kelulusan Matakuliah</h6>
                                    <p class="mb-0 text-muted text-xs-c">Mata kuliah yang dilamar telah lulus dengan nilai minimal <strong class="text-primary">"B"</strong> (Bukan hasil Semester Pendek).</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 mb-3">
                            <div class="d-flex align-items-start p-3 border rounded bg-light h-100">
                                <div class="req-icon-box mr-3"><i class="fa fa-user-shield fa-lg"></i></div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-dark">Komitmen & Integritas</h6>
                                    <p class="mb-0 text-muted text-xs-c">Bersedia mentaati seluruh peraturan Forum Asisten dan mengisi data pendaftaran dengan benar.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="font-weight-bold text-dark mb-2 border-left-primary pl-2">6 Dokumen Persyaratan Berkas (Terpisah)</h5>
                    <p class="text-muted text-small-c mb-3">Setiap dokumen diunggah secara mandiri dalam bentuk file PDF pada menu <strong class="text-primary">Upload Berkas</strong>:</p>

                    <div class="row">
                        <div class="col-lg-4 col-sm-6 col-12 mb-3">
                            <div class="p-3 border rounded h-100 bg-white">
                                <span class="badge badge-primary px-2 py-1 mb-2">Dokumen 1</span>
                                <h6 class="font-weight-bold text-dark mb-1">Kartu Tanda Mahasiswa (KTM)</h6>
                                <p class="text-muted text-xs-c mb-0">Hasil pemindaian Kartu Tanda Mahasiswa (KTM) aktif atau tangkapan layar dasbor profil mahasiswa resmi.</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12 mb-3">
                            <div class="p-3 border rounded h-100 bg-white">
                                <span class="badge badge-primary px-2 py-1 mb-2">Dokumen 2</span>
                                <h6 class="font-weight-bold text-dark mb-1">Surat Lamaran</h6>
                                <p class="text-muted text-xs-c mb-0">Surat lamaran resmi yang ditujukan kepada Pengelola Forum Asisten Universitas Amikom Purwokerto.</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12 mb-3">
                            <div class="p-3 border rounded h-100 bg-white">
                                <span class="badge badge-primary px-2 py-1 mb-2">Dokumen 3</span>
                                <h6 class="font-weight-bold text-dark mb-1">Curriculum Vitae (CV)</h6>
                                <p class="text-muted text-xs-c mb-0">Daftar riwayat hidup terbaru yang memuat informasi akademis, pengalaman, serta kualifikasi diri.</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12 mb-3">
                            <div class="p-3 border rounded h-100 bg-white">
                                <span class="badge badge-primary px-2 py-1 mb-2">Dokumen 4</span>
                                <h6 class="font-weight-bold text-dark mb-1">Transkrip Nilai Terakhir</h6>
                                <p class="text-muted text-xs-c mb-0">Transkrip nilai terbaru dengan penandaan (highlight) pada mata kuliah yang dilamar serta ditandatangani mahasiswa.</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12 mb-3">
                            <div class="p-3 border rounded h-100 bg-white">
                                <span class="badge badge-primary px-2 py-1 mb-2">Dokumen 5</span>
                                <h6 class="font-weight-bold text-dark mb-1">Surat Pernyataan</h6>
                                <p class="text-muted text-xs-c mb-0">Surat pernyataan resmi yang dibubuhi materai Rp10.000 (diperbolehkan menggunakan materai fisik maupun e-Materai / materai digital).</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6 col-12 mb-3">
                            <div class="p-3 border rounded h-100 bg-white">
                                <span class="badge badge-primary px-2 py-1 mb-2">Dokumen 6</span>
                                <h6 class="font-weight-bold text-dark mb-1">Formulir Pendaftaran</h6>
                                <p class="text-muted text-xs-c mb-0">Formulir pendaftaran seleksi calon asisten praktikum yang telah diisi secara lengkap dan benar.</p>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-primary border shadow-md p-3 p-md-4 rounded mt-2 d-flex align-items-md-center align-items-start flex-column flex-md-row" style="margin-bottom: 2.1rem; background-color: rgba(10, 36, 129, 0.1); border-left: 4px solid #0a2481 !important;">
                        <i class="fa fa-envelope-open-text fa-2x text-primary mr-md-3 mr-0 mb-md-0 mb-3"></i>
                        <div class="mb-md-0 mb-3">
                            <h6 class="d-block text-dark mb-1">Template Berkas Pendaftaran</h6>
                            <span class="text-xs-c text-muted">Unduh format surat lamaran, pernyataan, dan formulir pendaftaran resmi melalui tombol berikut.</span>
                        </div>
                        <a href="#" class="btn btn-primary font-weight-bold ml-auto flex-shrink-0">
                            <i class="fa fa-download mr-1"></i> Download Template
                        </a>
                    </div>

                    <div class="sdm-contact-card mb-1">
                        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
                            <div class="d-flex flex-column flex-md-row align-items-center text-center text-md-left" style="gap: 0.8rem;">
                                <div class="d-flex align-items-center justify-content-center rounded-circle bg-white shadow mb-2 mb-md-0 p-3 flex-shrink-0" style="width: 50px; height: 50px;">
                                    <i class="fa fa-headset fa-xl" style="color: var(--primary);"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold text-dark mb-1">Layanan Bantuan & Pusat Informasi SDM</h6>
                                    <p class="text-muted text-xs-c2 mb-0" style="line-height: 1.6;">
                                        Jika terdapat pertanyaan seputar hasil penetapan atau kendala teknis, silakan hubungi SDM <strong><?= esc($appName); ?></strong> via WhatsApp <strong>+<?= esc($rawPhone); ?></strong>.
                                    </p>
                                </div>
                            </div>

                            <div class="flex-shrink-0 my-2 my-md-0">
                                <a href="<?= esc($waSdmUrl); ?>" target="_blank" class="btn-sdm-contact">
                                    <i class="fa-brands fa-whatsapp fa-lg"></i>
                                    <span>Hubungi SDM</span>
                                </a>
                            </div>
                        </div>

                        <?php
                        $hasSocials = !empty($appProfile->instagram_url) || !empty($appProfile->linkedin_url) || !empty($appProfile->youtube_url) || !empty($appProfile->tiktok_url) || !empty($appProfile->website_url);
                        if ($hasSocials):
                        ?>
                            <div class="mt-3 pt-3 border-top d-flex align-items-center justify-content-center justify-content-md-end">
                                <div class="d-flex align-items-center gap-2">
                                    <?php if (!empty($appProfile->instagram_url)): ?>
                                        <a href="<?= esc($appProfile->instagram_url); ?>" target="_blank" class="social-link-circle" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($appProfile->linkedin_url)): ?>
                                        <a href="<?= esc($appProfile->linkedin_url); ?>" target="_blank" class="social-link-circle" title="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($appProfile->youtube_url)): ?>
                                        <a href="<?= esc($appProfile->youtube_url); ?>" target="_blank" class="social-link-circle" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($appProfile->tiktok_url)): ?>
                                        <a href="<?= esc($appProfile->tiktok_url); ?>" target="_blank" class="social-link-circle" title="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                                    <?php endif; ?>
                                    <?php if (!empty($appProfile->website_url)): ?>
                                        <a href="<?= esc($appProfile->website_url); ?>" target="_blank" class="social-link-circle" title="Website Resmi"><i class="fa fa-globe"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="tab-pane fade <?= $activeTab === 'workflow' ? 'show active' : '' ?>">

                    <div class="p-3 bg-soft-primary-custom-v3 border-left-primary rounded mb-4">
                        <h6 class="font-weight-bold text-dark mb-1 d-flex align-items-center">
                            <i class="fa fa-route text-primary mr-2"></i> Petunjuk & Alur Pendaftaran Calon Asisten
                        </h6>
                        <small class="text-xs-c text-secondary-c d-block" style="line-height: 1.6;">
                            Selesaikan 6 tahapan berikut secara berurutan untuk memastikan berkas pendaftaran Anda terproses secara resmi.
                        </small>
                    </div>

                    <div class="timeline-responsive my-4">
                        <div class="timeline-item">
                            <div class="timeline-badge">1</div>
                            <div class="timeline-content">
                                <div class="timeline-header d-flex align-items-center justify-content-start mb-2">
                                    <span class="timeline-step-tag">Tahap Utama</span>
                                </div>
                                <h6 class="font-weight-bold text-dark mb-2">Pengisian Biodata Diri & Unggah Pas Foto Formal</h6>
                                <p class="text-muted text-xs-c mb-0" style="line-height: 1.6;">
                                    Lengkapi seluruh informasi data pribadi, domisili, dan data akademik pada menu <strong class="text-primary">Profil Diri</strong>. Pastikan Anda mengunggah pas foto resmi terbaru berlatar belakang merah (rasio 4x6) dalam format JPG/JPEG/PNG.
                                </p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-badge">2</div>
                            <div class="timeline-content">
                                <div class="timeline-header d-flex align-items-center justify-content-start mb-2">
                                    <span class="timeline-step-tag badge-optional-tag">Tahap Pengayaan (Opsional)</span>
                                </div>
                                <h6 class="font-weight-bold text-dark mb-2">Pengisian Pengalaman Organisasi & Prestasi</h6>
                                <p class="text-muted text-xs-c mb-0" style="line-height: 1.6;">
                                    Tambahkan riwayat pengalaman organisasi, kepanitiaan, atau sertifikasi keahlian pendukung. Tahapan ini bersifat <em class="text-primary"><strong>opsional</strong></em> (boleh diisi atau dilewati), namun dapat menjadi nilai tambah kualifikasi pada proses seleksi.
                                </p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-badge">3</div>
                            <div class="timeline-content">
                                <div class="timeline-header d-flex align-items-center justify-content-start mb-2">
                                    <span class="timeline-step-tag">Tahap Akademik</span>
                                </div>
                                <h6 class="font-weight-bold text-dark mb-2">Pemilihan Mata Kuliah Lamaran</h6>
                                <p class="text-muted text-xs-c mb-0" style="line-height: 1.6;">
                                    Akses menu <strong class="text-primary">Penawaran Mata Kuliah</strong> dan pilih mata kuliah praktikum yang ingin Anda lamar. Pastikan Anda telah mengontrak mata kuliah tersebut sebelumnya dengan nilai kelulusan minimal <strong class="text-primary">"B"</strong> (Bukan hasil Semester Pendek).
                                </p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-badge">4</div>
                            <div class="timeline-content">
                                <div class="timeline-header d-flex align-items-center justify-content-start mb-2">
                                    <span class="timeline-step-tag">Tahap Berkas</span>
                                </div>
                                <h6 class="font-weight-bold text-dark mb-2">Unggah 6 Dokumen Persyaratan Berkas</h6>
                                <p class="text-muted text-xs-c mb-0" style="line-height: 1.6;">
                                    Unggah 6 berkas persyaratan wajib (KTM, Surat Lamaran, CV, Transkrip Nilai Terakhir, Surat Pernyataan Bermaterai, dan Formulir Pendaftaran) secara mandiri dalam format PDF sesuai ketentuan.
                                </p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-badge">5</div>
                            <div class="timeline-content">
                                <div class="timeline-header d-flex align-items-center justify-content-start mb-2">
                                    <span class="timeline-step-tag">Tahap Validasi</span>
                                </div>
                                <h6 class="font-weight-bold text-dark mb-2">Verifikasi & Finalisasi Pendaftaran</h6>
                                <p class="text-muted text-xs-c mb-0" style="line-height: 1.6;">
                                    Lakukan pemeriksaan ulang terhadap seluruh isian data dan dokumen yang diunggah. Jika data sudah benar dan lengkap, tekan tombol <strong class="text-primary">"Verifikasi Pendaftaran"</strong> untuk mengunci berkas pendaftaran Anda secara permanen.
                                </p>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-badge"><i class="fa fa-bullhorn" style="font-size:0.85rem;"></i></div>
                            <div class="timeline-content">
                                <div class="timeline-header d-flex align-items-center justify-content-start mb-2">
                                    <span class="timeline-step-tag badge-success-tag">Tahap Pengumuman</span>
                                </div>
                                <h6 class="font-weight-bold text-dark mb-2">Pengumuman Kelulusan Seleksi Anggota Forum Asisten</h6>
                                <p class="text-muted text-xs-c mb-0" style="line-height: 1.6;">
                                    Pengumuman resmi daftar calon asisten yang dinyatakan lulus seleksi akan dipublikasikan melalui akun Instagram resmi Forum Asisten (<strong class="text-primary">@forum_asisten</strong>) serta situs web resmi Forum Asisten pada halaman <strong class="text-primary">Pengumuman</strong>.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="tab-pane fade <?= $activeTab === 'courses' ? 'show active' : '' ?>">
                    <div class="p-3 bg-soft-primary-custom-v3 border-left-primary rounded mb-4">
                        <h6 class="font-weight-bold text-dark mb-1 d-flex align-items-center">
                            <i class="fa fa-bullhorn text-primary mr-2"></i> Penawaran Mata Kuliah Aktif
                        </h6>
                        <small class="text-xs-c text-secondary-c d-block" style="line-height: 1.6;">
                            Daftar mata kuliah praktikum yang sedang dibuka pendaftarannya berdasarkan Fakultas dan Program Studi.
                        </small>
                    </div>

                    <?php if (empty($groupedCourses)): ?>
                        <div class="text-center py-5 border rounded bg-light">
                            <i class="fa fa-folder-open text-primary mb-3" style="font-size: 2.3rem;"></i>
                            <h6 class="font-weight-bold text-dark">Belum Ada Penawaran Mata Kuliah</h6>
                            <p class="text-secondary-c text-xs-c mb-0">Saat ini belum ada mata kuliah aktif yang dibuka untuk pendaftaran.</p>
                        </div>
                    <?php else: ?>

                        <?php foreach ($groupedCourses as $facultyName => $programs): ?>
                            <div class="card mb-4 border shadow-none">
                                <div class="card-header bg-primary py-3 mb-2">
                                    <h6 class="font-weight-bold mb-0 text-white d-flex align-items-center">
                                        <i class="fa fa-university mr-2"></i> Fakultas <?= esc($facultyName) ?>
                                    </h6>
                                </div>
                                <div class="card-body p-3">

                                    <?php foreach ($programs as $programName => $courses): ?>
                                        <div class="mb-4 last-mb-0">
                                            <h6 class="font-weight-bold text-dark border-bottom pb-2 mb-3">
                                                <i class="fa fa-graduation-cap text-primary mr-1"></i> Program Studi: <?= esc($programName) ?>
                                            </h6>

                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered table-custom-responsive-registration align-middle mb-0">
                                                    <thead class="bg-primary text-white">
                                                        <tr class="text-center small font-weight-bold text-uppercase">
                                                            <th style="width: 50px;">No</th>
                                                            <th style="width: 120px;">Kode MK</th>
                                                            <th>Nama Mata Kuliah</th>
                                                            <th style="width: 110px;">Semester</th>
                                                            <th style="width: 90px;">SKS</th>
                                                            <th style="width: 160px;">Kebutuhan Asisten</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($courses as $index => $course): ?>
                                                            <tr>
                                                                <td class="text-center font-weight-bold text-muted small"><?= $index + 1 ?></td>
                                                                <td style="font-size: 0.85rem;" class="text-center"><span class="badge badge-light border font-weight-bold"><?= esc($course->course_code) ?></span></td>
                                                                <td style="font-size: 0.85rem;" class="font-weight-semibold col-course-name text-dark"><?= esc($course->course_name) ?></td>
                                                                <td style="font-size: 0.88rem;" class="text-center"><span class="badge badge-warning py-1 px-2">Semester <?= esc($course->semester) ?></span></td>
                                                                <td style="font-size: 0.8rem;" class="text-center"><?= esc($course->credits) ?> SKS</td>
                                                                <td style="font-size: 0.88rem;" class="text-center">
                                                                    <span class="badge badge-quota px-2 py-1">
                                                                        <i class="fa fa-users mr-1"></i> Kuota: <?= esc($course->quota_needed) ?>
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                </div>
                            </div>
                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>