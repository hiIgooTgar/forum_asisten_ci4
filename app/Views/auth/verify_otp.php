<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>
<div class="flex flex-col mb-5 sm:mb-6">
	<a href="<?= base_url(); ?>" class="left-content-gsap mb-4 sm:mb-6 inline-block w-fit">
		<img src="<?= base_url('assets/images/logo/logo-fa.png'); ?>" class="w-[70px] sm:w-[80px] h-auto" alt="Forum Asisten Logo" onerror="this.style.display='none'">
	</a>

	<h1 class="headline-animate-gsap text-2xl sm:text-3xl font-black text-brand-dark tracking-tight leading-tight">
		Verifikasi <span class="inline-block bg-gradient-to-r from-brand-primary to-brand-primary_light bg-clip-text text-transparent">Kode OTP</span>
	</h1>

	<p class="fade-up-gsap text-xs sm:text-sm mt-2 text-slate-500 leading-relaxed font-normal">
		Masukkan 6 digit kode OTP yang telah kami kirimkan ke email Anda untuk menyelesaikan aktivasi akun calon Asisten Dosen.
	</p>
</div>

<form action="<?= base_url('auth/processOtp'); ?>" method="POST" class="w-full space-y-5">
	<?= csrf_field() ?>
	<div class="fade-up-gsap flex justify-between gap-1.5 sm:gap-2.5 my-2">
		<?php for ($i = 0; $i < 6; $i++): ?>
			<input type="text" maxlength="1" name="otp[]" inputmode="numeric" pattern="[0-9]*" required autocomplete="off"
				class="otp-input w-10 h-12 xs:w-11 xs:h-13 sm:w-12 sm:h-14 text-center text-lg sm:text-xl font-bold border border-slate-300 rounded-md focus:border-brand-primary focus:ring-2 focus:ring-brand-primary/20 outline-none bg-white text-brand-dark transition-all">
		<?php endfor; ?>
	</div>

	<div class="fade-up-gsap">
		<button type="submit"
			class="w-full py-3 sm:py-3.5 bg-brand-primary hover:bg-brand-primary_hover text-white text-xs sm:text-sm font-bold flex items-center justify-center gap-2.5 rounded-md transition-all shadow-lg shadow-brand-primary/25 active:scale-[0.98] cursor-pointer">
			<span>Verifikasi Kode OTP</span>
			<i class="fa-solid fa-shield-check text-xs sm:text-sm"></i>
		</button>
	</div>
</form>

<div class="fade-up-gsap mt-5 p-3 sm:p-3.5 bg-slate-50 border border-slate-200/80 rounded-md flex items-center justify-between text-xs sm:text-sm text-slate-600">
	<div class="flex items-center gap-2">
		<i class="fa-solid fa-clock text-brand-primary text-sm sm:text-base"></i>
		<span>Tidak menerima kode OTP?</span>
	</div>
	<div>
		<a href="<?= base_url('auth/resendOtpAction'); ?>" id="resendBtn" class="text-brand-primary font-bold hover:text-brand-primary_hover hover:underline transition-all">
			Kirim Ulang
		</a>
		<span id="timerContainer" class="hidden text-slate-400 font-medium">
			Kirim ulang dalam <span id="timer" class="font-bold text-brand-primary">02:00</span>
		</span>
	</div>
</div>

<div class="fade-up-gsap flex items-center justify-center text-xs sm:text-sm mt-6 sm:mt-8 text-slate-500 font-medium">
	<a href="<?= base_url('auth/login'); ?>" class="inline-flex items-center gap-2 text-slate-600 hover:text-brand-primary font-bold transition-colors group">
		<i class="fa-solid fa-arrow-left text-xs sm:text-sm text-slate-500 group-hover:text-brand-primary transition-transform group-hover:-translate-x-1"></i>
		<span>Kembali ke Halaman Login</span>
	</a>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
	document.addEventListener("DOMContentLoaded", () => {
		const inputs = document.querySelectorAll('.otp-input');

		if (inputs.length > 0) inputs[0].focus();
		inputs.forEach((input, index) => {
			input.addEventListener('input', (e) => {
				const val = e.target.value;
				if (val.length === 1 && index < inputs.length - 1) {
					inputs[index + 1].focus();
				}
			});

			input.addEventListener('keydown', (e) => {
				if (e.key === 'Backspace') {
					if (!e.target.value && index > 0) {
						inputs[index - 1].focus();
					}
				} else if (e.key === 'ArrowLeft' && index > 0) {
					inputs[index - 1].focus();
				} else if (e.key === 'ArrowRight' && index < inputs.length - 1) {
					inputs[index + 1].focus();
				}
			});

			input.addEventListener('paste', (e) => {
				e.preventDefault();
				const pastedData = (e.clipboardData || window.clipboardData).getData('text').trim();
				if (/^\d{6}$/.test(pastedData)) {
					pastedData.split('').forEach((char, i) => {
						if (inputs[i]) {
							inputs[i].value = char;
						}
					});
					inputs[inputs.length - 1].focus();
				}
			});
		});

		const resendBtn = document.getElementById('resendBtn');
		const timerContainer = document.getElementById('timerContainer');
		const timerElement = document.getElementById('timer');
		const COOLDOWN_TIME = 180;

		const lastSentTime = <?= session()->get('otp_last_sent') ? session()->get('otp_last_sent') : 0; ?>;
		const currentTime = Math.floor(Date.now() / 1000);
		const elapsedTime = currentTime - lastSentTime;

		let remainingTime = COOLDOWN_TIME - elapsedTime;

		function updateTimerDisplay(seconds) {
			const m = Math.floor(seconds / 60).toString().padStart(2, '0');
			const s = (seconds % 60).toString().padStart(2, '0');
			timerElement.textContent = `${m}:${s}`;
		}

		if (remainingTime > 0) {
			resendBtn.classList.add('hidden');
			timerContainer.classList.remove('hidden');
			updateTimerDisplay(remainingTime);

			const countdown = setInterval(() => {
				remainingTime--;
				updateTimerDisplay(remainingTime);

				if (remainingTime <= 0) {
					clearInterval(countdown);
					timerContainer.classList.add('hidden');
					resendBtn.classList.remove('hidden');
				}
			}, 1000);
		}
	});
</script>
<?= $this->endSection() ?>