<?php
$session = session();
$redirectUrl = base_url('/');
$btnLabel = 'Kembali ke Beranda';

if ($session->get('is_admin_logged_in') && $session->get('admin_id')) {
    $redirectUrl = base_url('admin/dashboard');
    $btnLabel = 'Kembali ke Dashboard Admin';
} elseif ($session->get('is_student_logged_in')) {
    $redirectUrl = base_url('student/dashboard');
    $btnLabel = 'Kembali ke Dashboard Mahasiswa';
} else {
    $redirectUrl = base_url('auth/login');
    $btnLabel = 'Kembali ke Halaman Login';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <link rel="stylesheet" href="<?= base_url('assets/font/font-style.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/errors/page_404.css'); ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://unpkg.com/split-type"></script>
</head>

<body>
    <div class="error-wrapper">
        <div class="error-card">
            <div class="error-header">
                <a href="<?= base_url('/') ?>" class="brand-logo">
                    <img src="<?= base_url('assets/images/logo/logo-fa.png') ?>" alt="Logo">
                </a>
                <span class="header-badge">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>Forum Asisten</span>
                </span>
            </div>

            <div class="error-content">
                <div class="error-text-section left-content-gsap">
                    <span class="error-tag fade-up-gsap">Error 404</span>
                    <h1 class="error-title headline-animate-gsap">
                        Oops! Halaman <span>Tidak Ditemukan</span>
                    </h1>
                    <p class="error-description fade-up-gsap">
                        Halaman yang Anda tuju mungkin telah dihapus, diubah namanya, atau tidak tersedia saat ini. Silakan kembali ke halaman utama sistem Anda.
                    </p>
                    <div class="fade-up-gsap" style="width: 100%;">
                        <a href="<?= esc($redirectUrl) ?>" class="btn-redirect">
                            <i class="fa-solid fa-arrow-left"></i>
                            <span><?= esc($btnLabel) ?></span>
                        </a>
                    </div>
                </div>

                <div class="error-illustration fade-up-gsap">
                    <div class="browser-mockup">
                        <div class="browser-header">
                            <div class="dot dot-red"></div>
                            <div class="dot dot-yellow"></div>
                            <div class="dot dot-green"></div>
                        </div>
                        <div class="browser-body">
                            <div class="big-code">404</div>
                            <div class="sub-code">Page Not Found</div>

                            <div class="graphic-barrier">
                                <div class="barrier-stripe"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="fade-up-gsap">
                <p>&copy; <span id="year_c"></span> <span class="text-primary-c">Forum Asisten</span> Universitas Amikom Purwokerto</p>
            </footer>
        </div>

    </div>

    <script src="<?= base_url('assets/js/animation/animation-gsap.js') ?>"></script>
    <script>
        document.getElementById("year_c").innerHTML = new Date().getFullYear();
    </script>
</body>

</html>