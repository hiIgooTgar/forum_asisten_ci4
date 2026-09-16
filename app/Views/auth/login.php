<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="flex flex-col mb-6">
	<a href="<?= base_url(); ?>" class="left-content-gsap mb-4 sm:mb-6 inline-block w-fit">
		<img src="<?= base_url('assets/images/logo/logo-fa.png'); ?>" class="w-[70px] sm:w-[80px] h-auto" alt="Forum Asisten Logo" onerror="this.style.display='none'">
	</a>

	<h1 class="headline-animate-gsap text-2xl sm:text-3xl font-black text-brand-dark tracking-tight leading-tight">
		Selamat Datang di <span class="inline-block bg-gradient-to-r from-brand-primary to-brand-primary_light bg-clip-text text-transparent">Forum Asisten</span>
	</h1>

	<p class="fade-up-gsap text-xs sm:text-sm mt-2 text-slate-500 leading-relaxed font-normal">
		Silakan masuk ke akun Anda untuk mengakses portal rekrutmen, pengumpulan berkas seleksi, jadwal ujian, dan status pendaftaran calon Asisten Dosen.
	</p>
</div>

<form action="<?= base_url('auth/loginProcess'); ?>" method="POST" class="w-full space-y-4 sm:space-y-5">
	<?= csrf_field() ?>
	<div class="fade-up-gsap flex flex-col w-full">
		<label for="email" class="mb-1.5 text-xs sm:text-sm font-bold text-brand-dark">
			Email <span class="text-rose-500">*</span>
		</label>
		<input type="text" id="email" name="email" value="<?= old('email'); ?>" autocomplete="off"
			placeholder="nim@students.amikom.ac.id"
			class="w-full border border-slate-300 py-2.5 sm:py-3 px-3.5 sm:px-4 text-xs sm:text-sm outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 bg-white text-brand-dark rounded-md transition-all placeholder:text-slate-400">
	</div>

	<div class="fade-up-gsap flex flex-col w-full">
		<label for="password" class="mb-1.5 text-xs sm:text-sm font-bold text-brand-dark">
			Password <span class="text-rose-500">*</span>
		</label>
		<div class="relative w-full password-wrapper">
			<input type="password" id="password" name="password"
				placeholder="Masukkan password akun Anda"
				class="w-full border border-slate-300 py-2.5 sm:py-3 pl-3.5 sm:pl-4 pr-11 sm:pr-12 text-xs sm:text-sm outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 bg-white text-brand-dark rounded-md transition-all placeholder:text-slate-400">

			<button type="button" aria-label="Tampilkan atau sembunyikan password"
				class="toggle-password-btn absolute right-3 sm:right-3.5 top-1/2 -translate-y-1/2 cursor-pointer text-slate-400 hover:text-brand-primary transition-colors flex items-center justify-center p-1 select-none bg-transparent border-none">
				<i class="fa-regular fa-eye-slash toggle-icon text-sm sm:text-base"></i>
			</button>
		</div>
	</div>

	<div class="fade-up-gsap flex items-center justify-between text-xs sm:text-sm py-1">
		<div class="flex items-center gap-2">
			<input type="checkbox" id="remember" name="remember"
				class="accent-brand-primary w-4 h-4 border-slate-300 rounded cursor-pointer focus:ring-brand-primary">
			<label for="remember" class="cursor-pointer text-slate-600 font-medium select-none text-xs sm:text-sm">
				Ingat saya
			</label>
		</div>
		<a href="<?= base_url('auth/forget-password'); ?>" class="text-brand-primary font-semibold hover:text-brand-primary_combine_v2 hover:underline transition-colors text-xs sm:text-sm">
			Lupa Password?
		</a>
	</div>

	<div class="fade-up-gsap">
		<button type="submit"
			class="w-full py-3 sm:py-3.5 bg-brand-primary hover:bg-brand-primary_combine_v2 text-white text-xs sm:text-sm font-bold flex items-center justify-center gap-2.5 rounded-md transition-all shadow-lg shadow-brand-primary/25 cursor-pointer mt-2">
			<span>Masuk Portal Forum Asisten</span>
			<i class="fa-solid fa-right-to-bracket text-xs sm:text-sm"></i>
		</button>
	</div>
</form>

<div class="fade-up-gsap mt-5 p-3 sm:p-3.5 bg-slate-50 border border-slate-200/80 rounded-md flex items-center justify-between text-xs sm:text-sm text-slate-600">
	<div class="flex items-center gap-2">
		<i class="fa-solid fa-envelope-circle-check text-brand-primary text-sm sm:text-base"></i>
		<span>Belum verifikasi email?</span>
	</div>
	<a href="<?= base_url('auth/resend-verification'); ?>" class="text-brand-primary font-bold hover:text-brand-primary_combine_v2 hover:underline transition-all">
		Kirim Ulang
	</a>
</div>

<p class="fade-up-gsap text-center text-xs sm:text-sm mt-5 sm:mt-6 text-slate-500 font-medium">
	Belum memiliki akun calon asisten?
	<a href="<?= base_url('auth/register'); ?>" class="text-brand-primary font-bold hover:text-brand-primary_combine_v2 hover:underline transition-all ml-1">
		Daftar Sekarang
	</a>
</p>
<?= $this->endSection() ?>