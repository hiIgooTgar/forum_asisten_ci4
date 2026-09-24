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
