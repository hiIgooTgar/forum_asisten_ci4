function updateFileName(input) {
  const fileDisplay = input.parentElement.querySelector(".file-name-display");
  if (input.files && input.files[0]) {
    const fileName = input.files[0].name;
    fileDisplay.textContent = "Terpilih: " + fileName;
    fileDisplay.classList.add("text-success");
    fileDisplay.classList.remove("text-dark", "text-primary");
  } else {
    fileDisplay.textContent = "Pilih atau Seret Berkas PDF";
    fileDisplay.classList.remove("text-success", "text-primary");
    fileDisplay.classList.add("text-dark");
  }
}

function confirmResetDocuments(resetUrl) {
  showConfirmModal({
    title: "Reset Berkas Pendaftaran?",
    text: "Apakah Anda yakin ingin menghapus seluruh berkas pendaftaran yang telah diunggah? Tindakan ini akan mengosongkan kembali form dan tidak dapat dibatalkan.",
    confirmText: "Ya, Reset Semua!",
    confirmBtnClass: "btn-danger",
    iconClass: "fa-rotate-left",
    iconBgColor: "#fee2e2",
    iconColor: "#dc2626",
    actionUrl: resetUrl,
    method: "POST",
  });
}

function handleIncompleteWarning(reason) {
  if (reason === "profile") {
    showWarningModal({
      title: "Profil Belum Lengkap",
      text: "Silakan lengkapi seluruh data profil dan unggah foto profil Anda terlebih dahulu sebelum mengunggah berkas.",
      btnText: "Mengerti",
    });
  } else if (reason === "courses") {
    showWarningModal({
      title: "Mata Kuliah Belum Diambil",
      text: "Anda belum memilih atau mengambil mata kuliah. Silakan pilih mata kuliah terlebih dahulu.",
      btnText: "Mengerti",
    });
  }
}
