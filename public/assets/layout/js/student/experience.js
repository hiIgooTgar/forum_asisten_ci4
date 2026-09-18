$(document).ready(function () {
  function initSelect2() {
    $(".select2").select2({
      placeholder: "-- Pilih Data --",
      allowClear: true,
      width: "100%",
      dropdownParent: $(this).hasClass("modal") ? $(this) : $(document.body),
    });
  }

  $(".modal").on("shown.bs.modal", function () {
    $(this)
      .find(".select2")
      .select2({
        placeholder: "-- Pilih Data --",
        allowClear: true,
        width: "100%",
        dropdownParent: $(this),
      });
  });
});

function confirmDeleteExperience(deleteUrl, title, organization) {
  showConfirmModal({
    title: "Hapus Data Pengalaman?",
    text:
      'Apakah Anda yakin ingin menghapus "' +
      title +
      '" di ' +
      organization +
      "? Tindakan ini tidak dapat dibatalkan.",
    confirmText: "Ya, Hapus Data!",
    confirmBtnClass: "btn-danger",
    iconClass: "fa-trash-alt",
    iconBgColor: "#fee2e2",
    iconColor: "#dc2626",
    actionUrl: deleteUrl,
    method: "POST",
  });
}
