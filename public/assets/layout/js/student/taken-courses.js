function confirmDeleteCourse(deleteUrl, courseName, programName) {
  showConfirmModal({
    title: "Hapus Mata Kuliah?",
    text:
      'Apakah Anda yakin ingin menghapus mata kuliah "' +
      courseName +
      '" (' +
      programName +
      ")? Tindakan ini tidak dapat dibatalkan.",
    confirmText: "Ya, Hapus Data!",
    confirmBtnClass: "btn-danger",
    iconClass: "fa-trash-alt",
    iconBgColor: "#fee2e2",
    iconColor: "#dc2626",
    actionUrl: deleteUrl,
    method: "POST",
  });
}

function handleIncompleteProfileSubmitUpdate() {
  var $modal = $(".modal-main-content");
  if ($modal.hasClass("show")) {
    $modal
      .one("hidden.bs.modal", function () {
        showWarningModal({
          title: "Profil Belum Lengkap",
          text: "Silakan lengkapi seluruh data profil dan unggah foto profil Anda terlebih dahulu sebelum mengubah/menambah data.",
          btnText: "Mengerti",
        });
      })
      .modal("hide");
  } else {
    showWarningModal({
      title: "Profil Belum Lengkap",
      text: "Silakan lengkapi seluruh data profil dan unggah foto profil Anda terlebih dahulu sebelum mengubah/menambah data.",
      btnText: "Mengerti",
    });
  }
}
