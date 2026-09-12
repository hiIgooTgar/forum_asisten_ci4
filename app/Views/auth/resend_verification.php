<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="flex flex-col mb-5 sm:mb-6">
	<a href="<?= base_url(); ?>" class="left-content-gsap mb-4 sm:mb-6 inline-block w-fit">
		<img src="<?= base_url('assets/images/logo/logo-fa.png'); ?>" class="w-[70px] sm:w-[80px] h-auto" alt="Forum Asisten Logo" onerror="this.style.display='none'">
	</a>

	<h1 class="headline-animate-gsap text-2xl sm:text-3xl font-black text-brand-dark tracking-tight leading-tight">
		Kirim Ulang <span class="inline-block bg-gradient-to-r from-brand-primary to-brand-primary_light bg-clip-text text-transparent">Verifikasi Email</span>
	</h1>

	<p class="fade-up-gsap text-xs sm:text-sm mt-1.5 text-slate-500 leading-relaxed font-normal">
		Belum menerima email verifikasi? Masukkan alamat email Anda di bawah ini untuk mengirimkan ulang tautan verifikasi/OTP.
	</p>
</div>

<div class="fade-up-gsap p-3.5 mb-4 bg-amber-50 border border-amber-200 rounded-md flex items-start gap-3 text-xs text-amber-800">
	<i class="fa-solid fa-triangle-exclamation text-amber-500 text-sm mt-0.5"></i>
	<div>
		<strong>Periksa Folder Spam/Junk!</strong> Email verifikasi terkadang masuk ke folder Spam. Jika berada di sana, tandai sebagai <em>"Bukan Spam"</em> agar pesan berikutnya berjalan lancar.
	</div>
</div>

<form action="<?= base_url('auth/processResendVerification'); ?>" method="POST" class="w-full space-y-4">
	<?= csrf_field() ?>
	<div class="fade-up-gsap flex flex-col w-full">
		<label for="email" class="mb-1 text-xs sm:text-sm font-bold text-brand-dark">
			Email <span class="text-rose-500">*</span>
		</label>
		<input type="text" id="email" name="email" value="<?= old('email'); ?>"
			placeholder="nim@students.amikom.ac.id"
			class="w-full border border-slate-300 py-2.5 sm:py-3 px-3.5 sm:px-4 text-xs sm:text-sm outline-none focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 bg-white text-brand-dark rounded-md transition-all placeholder:text-slate-400">
	</div>

	<button type="submit"
		class="left-content-gsap w-full py-3 sm:py-3.5 bg-brand-primary hover:bg-brand-primary_hover text-white text-xs sm:text-sm font-bold flex items-center justify-center gap-2.5 rounded-md transition-all shadow-lg shadow-brand-primary/25 active:scale-[0.98] cursor-pointer !mt-5">
		<span>Kirim Ulang Tautan Verifikasi</span>
		<i class="fa-solid fa-paper-plane text-xs sm:text-sm"></i>
	</button>
</form>

<div class="fade-up-gsap flex items-center justify-center text-xs sm:text-sm mt-6 sm:mt-8 text-slate-500 font-medium">
	<a href="<?= base_url('auth/login'); ?>" class="inline-flex items-center gap-2 text-slate-600 hover:text-brand-primary font-bold transition-colors group">
		<i class="fa-solid fa-arrow-left text-xs sm:text-sm text-slate-500 group-hover:text-brand-primary transition-transform group-hover:-translate-x-1"></i>
		<span>Kembali ke Halaman Login</span>
	</a>
</div>
<?= $this->endSection() ?>