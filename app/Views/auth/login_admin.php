<?= $this->extend('layouts/auth_admin') ?>

<?= $this->section('content') ?>
<div id="auth-form-section" class="flex-[1_1_100%] md:flex-[1_1_50%] lg:flex-[1_1_45%] flex flex-col justify-between min-h-screen p-6 sm:p-10 md:p-12 lg:p-[5%] bg-white order-2 md:order-1 scroll-mt-6">

    <div class="flex flex-col my-auto">
        <a href="<?= base_url(); ?>" class="left-content-gsap mb-4 sm:mb-6 inline-block w-fit">
            <img src="<?= base_url('assets/images/logo/logo-fa.png'); ?>" class="w-[70px] sm:w-[80px] h-auto" alt="Forum Asisten Logo" onerror="this.style.display='none'">
        </a>
        <h1 class="headline-animate-gsap text-2xl sm:text-3xl font-black text-brand-dark tracking-tight leading-tight">
            Selamat Datang <span class="inline-block bg-gradient-to-r from-brand-primary to-brand-primary_light bg-clip-text text-transparent">Administrator</span>
        </h1>
        <p class="fade-up-gsap text-xs sm:text-sm mt-2 mb-6 text-slate-500 leading-relaxed font-normal">
            Silakan masuk ke akun Anda untuk mengakses portal pengelolaan rekrutmen dan sistem Forum Asisten.
        </p>

        <form action="<?= base_url('auth/login-process'); ?>" method="POST" class="w-full space-y-4 sm:space-y-5" x-data="{ showPassword: false }">
            <?= csrf_field() ?>

            <div class="fade-up-gsap flex flex-col w-full">
                <label for="username_email" class="mb-1.5 text-xs sm:text-sm font-bold text-brand-dark">
                    Email atau Username <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="username_email" name="username_email" value="<?= old('username_email'); ?>" autocomplete="off"
                    placeholder="admin@amikom.ac.id atau username"
                    class="w-full border border-slate-300 py-2.5 sm:py-3 px-3.5 sm:px-4 text-xs sm:text-sm outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 bg-white text-brand-dark rounded-md transition-all placeholder:text-slate-400">
            </div>

            <div class="fade-up-gsap flex flex-col w-full">
                <label for="password" class="mb-1.5 text-xs sm:text-sm font-bold text-brand-dark">
                    Password <span class="text-rose-500">*</span>
                </label>
                <div class="relative w-full password-wrapper">
                    <input :type="showPassword ? 'text' : 'password'" id="password" name="password"
                        placeholder="Masukkan password akun Anda"
                        class="w-full border border-slate-300 py-2.5 sm:py-3 pl-3.5 sm:pl-4 pr-11 sm:pr-12 text-xs sm:text-sm outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 bg-white text-brand-dark rounded-md transition-all placeholder:text-slate-400">

                    <button type="button" @click="showPassword = !showPassword" aria-label="Tampilkan atau sembunyikan password"
                        class="toggle-password-btn absolute right-3 sm:right-3.5 top-1/2 -translate-y-1/2 cursor-pointer text-slate-400 hover:text-brand-primary transition-colors flex items-center justify-center p-1 select-none bg-transparent border-none">
                        <i :class="showPassword ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'" class="toggle-icon text-sm sm:text-base"></i>
                    </button>
                </div>
            </div>

            <div class="fade-up-gsap">
                <button type="submit"
                    class="w-full py-3 sm:py-3.5 bg-brand-primary hover:bg-brand-primary_combine_v2 text-white text-xs sm:text-sm font-bold flex items-center justify-center gap-2.5 rounded-md transition-all shadow-lg shadow-brand-primary/25 active:scale-[0.98] cursor-pointer mt-4">
                    <span>Masuk Dashboard Admin</span>
                    <i class="fa-solid fa-right-to-bracket text-xs sm:text-sm"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="fade-up-gsap pt-6 text-center text-xs text-slate-500 font-medium border-t border-slate-100 mt-6">
        <p>Copyright &copy; <?= date('Y') ?> <span class="text-brand-primary">Forum Asisten</span> Universitas Amikom Purwokerto</p>
    </div>
</div>

<div class="left-content-gsap flex flex-col justify-between flex-[1_1_100%] md:flex-[1_1_50%] lg:flex-[1_1_55%] min-h-screen relative text-white p-6 sm:p-10 md:p-12 lg:p-[5%] overflow-hidden grid-pattern-bg order-1 md:order-2">
    <div class="animate-ambient-aura absolute -bottom-20 -left-20 w-[280px] h-[280px] sm:w-[380px] sm:h-[380px] md:w-[460px] md:h-[460px] lg:w-[540px] lg:h-[540px] bg-brand-primary/30 rounded-full blur-[100px] pointer-events-none z-0"></div>
    <div class="animate-ambient-aura absolute -top-20 -right-20 w-[280px] h-[280px] sm:w-[380px] sm:h-[380px] md:w-[460px] md:h-[460px] lg:w-[540px] lg:h-[540px] bg-sky-500/20 rounded-full blur-[100px] pointer-events-none z-0" style="animation-delay: -3s;"></div>
    <div class="absolute inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="animate-smooth-shine absolute top-0 left-0 w-1/2 h-full bg-gradient-to-r from-transparent via-white/15 to-transparent"></div>
    </div>

    <div class="z-10 w-full space-y-4 sm:space-y-6 my-auto">
        <div>
            <a href="<?= base_url(); ?>" class="text-white hover:opacity-90 transition-opacity inline-block">
                <img src="<?= base_url('assets/images/logo/logo-fa-white.png'); ?>" class="w-[105px] sm:w-[115px] h-auto" alt="Forum Asisten Logo White" onerror="this.style.display='none'">
            </a>
        </div>

        <div class="space-y-2 sm:space-y-3">
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-black tracking-tight leading-snug text-white">
                Dashboard Administrasi & Kelola Sistem Forum Asisten
            </h2>
            <p class="text-xs sm:text-sm leading-relaxed font-light text-justify text-slate-300 opacity-90">
                Pusat kendali operasional rekrutmen, validasi berkas seleksi, penjadwalan ujian, dan pengelolaan data calon Asisten Dosen Universitas Amikom Purwokerto secara terpadu.
            </p>
        </div>

        <div class="relative z-10 w-full my-4 sm:my-6 md:mt-8 flex items-center justify-center">
            <div id="admin-hero-card" class="w-full p-5 sm:p-6 rounded-md bg-white/[0.05] backdrop-blur-md border border-white/10 shadow-2xl transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-md bg-brand-primary/30 border border-white/20 flex items-center justify-center text-white shrink-0">
                        <i class="fa-solid fa-user-shield text-xl sm:text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm sm:text-base font-bold text-white tracking-tight">Akses Administrator Terproteksi</h4>
                        <p class="text-xs text-sky-200/80 font-normal mt-0.5">Pastikan kredensial login Anda valid dan terotorisasi.</p>
                    </div>
                </div>
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
<?= $this->endSection() ?>