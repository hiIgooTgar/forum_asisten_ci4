<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="flex flex-col mb-5 sm:mb-6">
	<a href="<?= base_url(); ?>" class="left-content-gsap mb-4 sm:mb-6 inline-block w-fit">
		<img src="<?= base_url('assets/images/logo/logo-fa.png'); ?>" class="w-[70px] sm:w-[80px] h-auto" alt="Forum Asisten Logo" onerror="this.style.display='none'">
	</a>

	<h1 class="headline-animate-gsap text-2xl sm:text-3xl font-black text-brand-dark tracking-tight leading-tight">
		Registrasi <span class="inline-block bg-gradient-to-r from-brand-primary to-brand-primary_light bg-clip-text text-transparent">Calon Asisten</span>
	</h1>

	<p class="fade-up-gsap text-xs sm:text-sm mt-1.5 text-slate-500 leading-relaxed font-normal">
		Lengkapi formulir di bawah ini untuk membuat akun baru dan memulai tahapan pendaftaran seleksi Asisten Dosen Universitas Amikom Purwokerto.
	</p>
</div>

<form action="<?= base_url('auth/processRegister'); ?>" method="POST" class="w-full space-y-3.5 sm:space-y-4">
	<?= csrf_field() ?>
	<div class="fade-up-gsap flex flex-col w-full">
		<label for="student_number" class="mb-1 text-xs sm:text-sm font-bold text-brand-dark">
			Nomor Induk Mahasiswa <span class="text-rose-500">*</span>
		</label>
		<input type="text" id="student_number" name="student_number" value="<?= esc(old('student_number')); ?>"
			placeholder="Contoh: 24SA11A159"
			class="w-full border border-slate-300 py-2.5 sm:py-3 px-3.5 sm:px-4 text-xs sm:text-sm outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 bg-white text-brand-dark rounded-md transition-all placeholder:text-slate-400">
	</div>

	<div class="fade-up-gsap flex flex-col w-full">
		<label for="full_name" class="mb-1 text-xs sm:text-sm font-bold text-brand-dark">
			Nama Lengkap <span class="text-rose-500">*</span>
		</label>
		<input type="text" id="full_name" name="full_name" value="<?= esc(old('full_name')); ?>"
			placeholder="Masukkan nama lengkap sesuai KTM"
			class="w-full border border-slate-300 py-2.5 sm:py-3 px-3.5 sm:px-4 text-xs sm:text-sm outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 bg-white text-brand-dark rounded-md transition-all placeholder:text-slate-400">
	</div>

	<div class="fade-up-gsap flex flex-col w-full">
		<label for="email" class="mb-1 text-xs sm:text-sm font-bold text-brand-dark">
			Email <span class="text-rose-500">*</span>
		</label>
		<input type="text" id="email" name="email" value="<?= esc(old('email')); ?>"
			placeholder="nim@students.amikom.ac.id"
			class="w-full border border-slate-300 py-2.5 sm:py-3 px-3.5 sm:px-4 text-xs sm:text-sm outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 bg-white text-brand-dark rounded-md transition-all placeholder:text-slate-400">
	</div>

	<div class="fade-up-gsap flex flex-col w-full">
		<label for="password" class="mb-1 text-xs sm:text-sm font-bold text-brand-dark">
			Password <span class="text-rose-500">*</span>
		</label>

		<div class="relative w-full password-wrapper">
			<input type="password" id="password" name="password"
				placeholder="Buat password minimal 8 karakter"
				class="w-full border border-slate-300 py-2.5 sm:py-3 pl-3.5 sm:pl-4 pr-11 sm:pr-12 text-xs sm:text-sm outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 bg-white text-brand-dark rounded-md transition-all placeholder:text-slate-400">

			<button type="button" aria-label="Tampilkan atau sembunyikan password"
				class="toggle-password-btn absolute right-3 sm:right-3.5 top-1/2 -translate-y-1/2 cursor-pointer text-slate-400 hover:text-brand-primary transition-colors flex items-center justify-center p-1 select-none bg-transparent border-none">
				<i class="fa-regular fa-eye-slash toggle-icon text-sm sm:text-base"></i>
			</button>
		</div>
	</div>

	<div class="fade-up-gsap flex flex-col w-full">
		<label for="password_confirmation" class="mb-1 text-xs sm:text-sm font-bold text-brand-dark">
			Konfirmasi Password <span class="text-rose-500">*</span>
		</label>

		<div class="relative w-full password-wrapper">
			<input type="password" id="password_confirmation" name="password_confirmation"
				placeholder="Ulangi password yang telah dibuat"
				class="w-full border border-slate-300 py-2.5 sm:py-3 pl-3.5 sm:pl-4 pr-11 sm:pr-12 text-xs sm:text-sm outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 bg-white text-brand-dark rounded-md transition-all placeholder:text-slate-400">

			<button type="button" aria-label="Tampilkan atau sembunyikan password konfirmasi"
				class="toggle-password-btn absolute right-3 sm:right-3.5 top-1/2 -translate-y-1/2 cursor-pointer text-slate-400 hover:text-brand-primary transition-colors flex items-center justify-center p-1 select-none bg-transparent border-none">
				<i class="fa-regular fa-eye-slash toggle-icon text-sm sm:text-base"></i>
			</button>
		</div>
	</div>

	<button type="submit"
		class="left-content-gsap w-full py-3 sm:py-3.5 bg-brand-primary hover:bg-brand-primary_hover text-white text-xs sm:text-sm font-bold flex items-center justify-center gap-2.5 rounded-md transition-all shadow-lg shadow-brand-primary/25 active:scale-[0.98] cursor-pointer !mt-5">
		<span>Daftar Akun Calon Asisten</span>
		<i class="fa-solid fa-user-plus text-xs sm:text-sm"></i>
	</button>
</form>

<p class="fade-up-gsap text-center text-xs sm:text-sm mt-5 sm:mt-6 text-slate-500 font-medium">
	Sudah memiliki akun calon asisten?
	<a href="<?= base_url('auth/login'); ?>" class="text-brand-primary font-bold hover:text-brand-primary_hover hover:underline transition-all ml-1">
		Masuk Sekarang
	</a>
</p>
<?= $this->endSection() ?>