<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<?php
$request = \Config\Services::request();
$token_val = $token ?? $request->getGet('token') ?? '';
$email_val = $email ?? $request->getGet('email') ?? '';
?>

<div class="flex flex-col mb-5 sm:mb-6">
	<a href="<?= base_url(); ?>" class="left-content-gsap mb-4 sm:mb-6 inline-block w-fit">
		<img src="<?= base_url('assets/images/logo/logo-fa.png'); ?>" class="w-[70px] sm:w-[80px] h-auto" alt="Forum Asisten Logo" onerror="this.style.display='none'">
	</a>

	<h1 class="headline-animate-gsap text-2xl sm:text-3xl font-black text-brand-dark tracking-tight leading-tight">
		Atur Ulang <span class="inline-block bg-gradient-to-r from-brand-primary to-brand-primary_light bg-clip-text text-transparent">Password</span>
	</h1>

	<p class="fade-up-gsap text-xs sm:text-sm mt-1.5 text-slate-500 leading-relaxed font-normal">
		Masukkan password baru Anda untuk akun portal calon asisten.
	</p>
</div>

<form action="<?= base_url('auth/processResetPassword'); ?>" method="POST" class="w-full space-y-4">
	<?= csrf_field() ?>
	<input type="hidden" name="token" value="<?= esc($token_val); ?>">

	<div class="fade-up-gsap flex flex-col w-full">
		<label for="email" class="mb-1 text-xs sm:text-sm font-bold text-brand-dark">
			Email <span class="text-rose-500">*</span>
		</label>
		<input type="text" id="email" name="email" value="<?= esc($email_val); ?>" required readonly
			class="w-full border border-slate-300 py-2.5 sm:py-3 px-3.5 sm:px-4 text-xs sm:text-sm bg-slate-100 text-brand-dark rounded-md outline-none cursor-not-allowed">
	</div>

	<div class="fade-up-gsap flex flex-col w-full">
		<label for="password" class="mb-1 text-xs sm:text-sm font-bold text-brand-dark">
			Password Baru <span class="text-rose-500">*</span>
		</label>
		<div class="relative w-full password-wrapper">
			<input type="password" id="password" name="password" required placeholder="Minimal 8 karakter"
				class="w-full border border-slate-300 py-2.5 sm:py-3 pl-3.5 sm:pl-4 pr-11 sm:pr-12 text-xs sm:text-sm outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 bg-white text-brand-dark rounded-md transition-all placeholder:text-slate-400">
			<button type="button" aria-label="Tampilkan atau sembunyikan password" class="toggle-password-btn absolute right-3 sm:right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-brand-primary p-1 bg-transparent border-none">
				<i class="fa-regular fa-eye-slash toggle-icon text-sm sm:text-base"></i>
			</button>
		</div>
	</div>

	<div class="fade-up-gsap flex flex-col w-full">
		<label for="password_confirmation" class="mb-1 text-xs sm:text-sm font-bold text-brand-dark">
			Konfirmasi Password Baru <span class="text-rose-500">*</span>
		</label>
		<div class="relative w-full password-wrapper">
			<input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password baru"
				class="w-full border border-slate-300 py-2.5 sm:py-3 pl-3.5 sm:pl-4 pr-11 sm:pr-12 text-xs sm:text-sm outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 bg-white text-brand-dark rounded-md transition-all placeholder:text-slate-400">
			<button type="button" aria-label="Tampilkan atau sembunyikan password" class="toggle-password-btn absolute right-3 sm:right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-brand-primary p-1 bg-transparent border-none">
				<i class="fa-regular fa-eye-slash toggle-icon text-sm sm:text-base"></i>
			</button>
		</div>
	</div>

	<div class="fade-up-gsap">
		<button type="submit"
			class="w-full py-3 sm:py-3.5 bg-brand-primary hover:bg-brand-primary_hover text-white text-xs sm:text-sm font-bold flex items-center justify-center gap-2.5 rounded-md transition-all shadow-lg shadow-brand-primary/25 cursor-pointer !mt-5">
			<span>Simpan Password Baru</span>
			<i class="fa-solid fa-key text-xs sm:text-sm"></i>
		</button>
	</div>
</form>

<div class="fade-up-gsap flex items-center justify-center text-xs sm:text-sm mt-6 sm:mt-8 text-slate-500 font-medium">
	<a href="<?= base_url('auth/login'); ?>" class="inline-flex items-center gap-2 text-slate-600 hover:text-brand-primary font-bold transition-colors group">
		<i class="fa-solid fa-arrow-left text-xs sm:text-sm text-slate-500 group-hover:text-brand-primary transition-transform group-hover:-translate-x-1"></i>
		<span>Batal & Kembali ke Halaman Login</span>
	</a>
</div>
<?= $this->endSection() ?>