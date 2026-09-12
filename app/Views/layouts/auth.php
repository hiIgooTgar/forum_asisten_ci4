<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Authentication' ?> - Forum Asisten Universitas Amikom Purwokerto</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="<?= base_url('assets/font/font-style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/tailwind/output.css') ?>">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://unpkg.com/split-type"></script>
</head>

<body class="bg-white antialiased overflow-x-hidden font-sans">

    <?= $this->include('partials/toast') ?>

    <section class="flex flex-col md:flex-row w-full min-h-screen relative overflow-x-hidden bg-white">
        <div class="left-content-gsap flex flex-col justify-between flex-[1_1_100%] md:flex-[1_1_50%] lg:flex-[1_1_55%] min-h-screen relative text-white p-6 sm:p-10 md:p-12 lg:p-[5%] overflow-hidden bg-gradient-to-br from-brand-primary via-brand-primary_combine_v2 to-brand-primary_hover_v2">

            <div class="absolute -bottom-16 -left-16 sm:-bottom-20 sm:-left-20 md:-bottom-24 md:-left-24 lg:-bottom-28 lg:-left-28 
						w-[280px] h-[280px] sm:w-[380px] sm:h-[380px] md:w-[460px] md:h-[460px] lg:w-[540px] lg:h-[540px] 
						border-[25px] sm:border-[33px] md:border-[38px] lg:border-[46px] border-white/5 rounded-full pointer-events-none z-0"></div>

            <div class="absolute -top-16 -right-16 sm:-top-20 sm:-right-20 md:-top-24 md:-right-24 lg:-top-28 lg:-right-28 
						w-[280px] h-[280px] sm:w-[380px] sm:h-[380px] md:w-[460px] md:h-[460px] lg:w-[540px] lg:h-[540px] 
						border-[25px] sm:border-[33px] md:border-[38px] lg:border-[46px] border-white/5 rounded-full pointer-events-none z-0"></div>

            <div class="z-10 w-full space-y-4 sm:space-y-6">
                <div>
                    <a href="<?= base_url(); ?>" class="text-white hover:opacity-90 transition-opacity inline-block">
                        <img src="<?= base_url('assets/images/logo/logo-fa-white.png'); ?>" class="w-[105px] sm:w-[115px] h-auto" alt="Forum Asisten Logo" onerror="this.style.display='none'">
                    </a>
                </div>

                <div class="space-y-2 sm:space-y-3">
                    <h2 class="text-xl sm:text-2xl lg:text-3xl font-black tracking-tight leading-snug">
                        Portal Rekrutmen & Seleksi Calon Asisten Dosen
                    </h2>
                    <p class="text-xs sm:text-sm leading-relaxed font-light text-justify opacity-85">
                        Bergabunglah menjadi bagian dari Asisten Dosen Universitas Amikom Purwokerto. Kembangkan potensi akademik, wawasan kepemimpinan, dan tingkatkan pemahaman teknis Anda melalui ruang kolaborasi Forum Asisten.
                    </p>
                </div>

                <div class="flex items-center space-x-2 pt-1">
                    <span class="banner-dot cursor-pointer h-[4px] inline-block transition-all duration-500 bg-brand-primary_light w-[24px] rounded-full"></span>
                    <span class="banner-dot cursor-pointer h-[4px] inline-block transition-all duration-500 bg-white/30 hover:bg-white/60 w-[12px] rounded-full"></span>
                    <span class="banner-dot cursor-pointer h-[4px] inline-block transition-all duration-500 bg-white/30 hover:bg-white/60 w-[12px] rounded-full"></span>
                </div>

                <div class="relative z-10 w-full my-4 sm:my-6 md:mt-8 flex-1 flex items-center justify-center">
                    <div class="banner-slide w-full transition-all duration-700 block opacity-100 scale-100">
                        <img src="<?= base_url('assets/images/banner/fa_ganjil_25.jpg'); ?>" alt="Suasana Praktikum Laboratorium" class="w-full h-[180px] sm:h-[220px] md:h-[240px] lg:h-[320px] object-cover object-center rounded-md shadow-2xl border border-white/10">
                    </div>
                    <div class="banner-slide w-full transition-all duration-700 hidden opacity-0 scale-95">
                        <img src="<?= base_url('assets/images/banner/fa_ganjil_25.jpg'); ?>" alt="Kolaborasi Forum Asisten" class="w-full h-[180px] sm:h-[220px] md:h-[240px] lg:h-[320px] object-cover object-center rounded-md shadow-2xl border border-white/10">
                    </div>
                    <div class="banner-slide w-full transition-all duration-700 hidden opacity-0 scale-95">
                        <img src="<?= base_url('assets/images/banner/fa_ganjil_25.jpg'); ?>" alt="Pengajaran dan Pendampingan Mahasiswa" class="w-full h-[180px] sm:h-[220px] md:h-[240px] lg:h-[320px] object-cover object-center rounded-md shadow-2xl border border-white/10">
                    </div>
                </div>
            </div>

            <div class="z-10 flex justify-center md:hidden pt-4 pb-2">
                <button type="button" onclick="document.getElementById('auth-form-section').scrollIntoView({ behavior: 'smooth' })"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-xs font-bold hover:bg-white/25 transition-all active:scale-95 shadow-lg">
                    <span><i class="fa-solid fa-computer-mouse"></i></span>
                    <svg class="w-3.5 h-3.5 animate-bounce text-[#fff]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div id="auth-form-section" class="flex-[1_1_100%] md:flex-[1_1_50%] lg:flex-[1_1_45%] flex flex-col justify-center min-h-screen p-6 sm:p-10 md:p-12 lg:p-[5%] bg-white scroll-mt-6">
            <?= $this->renderSection('content') ?>
        </div>
    </section>

    <script src="<?= base_url('assets/js/auth/auth.js'); ?>"></script>
    <script src="<?= base_url('assets/js/animation/animation-gsap.js'); ?>"></script>
    <?= $this->renderSection('script') ?>
</body>

</html>