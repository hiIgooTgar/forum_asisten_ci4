function handleIncompleteProfileSubmit() {
  var $modal = $(".modal-main-content");
  if ($modal.hasClass("show")) {
    $modal
      .one("hidden.bs.modal", function () {
        showWarningModal({
          title: "Profil Belum Lengkap",
          text: "Silakan lengkapi seluruh data profil dan unggah foto profil Anda terlebih dahulu sebelum menambah data.",
          btnText: "Mengerti",
        });
      })
      .modal("hide");
  } else {
    showWarningModal({
      title: "Profil Belum Lengkap",
      text: "Silakan lengkapi seluruh data profil dan unggah foto profil Anda terlebih dahulu sebelum menambah data.",
      btnText: "Mengerti",
    });
  }
}

function handleIncompleteProfileUpdate() {
  var $modal = $(".modal-main-content");
  if ($modal.hasClass("show")) {
    $modal
      .one("hidden.bs.modal", function () {
        showWarningModal({
          title: "Profil Belum Lengkap",
          text: "Silakan lengkapi seluruh data profil dan unggah foto profil Anda terlebih dahulu sebelum mengubah data.",
          btnText: "Mengerti",
        });
      })
      .modal("hide");
  } else {
    showWarningModal({
      title: "Profil Belum Lengkap",
      text: "Silakan lengkapi seluruh data profil dan unggah foto profil Anda terlebih dahulu sebelum mengubah data.",
      btnText: "Mengerti",
    });
  }
}

function handleIncompleteProfileDelete() {
  showWarningModal({
    title: "Profil Belum Lengkap",
    text: "Silakan lengkapi seluruh data profil dan unggah foto profil Anda terlebih dahulu sebelum menghapus data.",
    btnText: "Mengerti",
  });
}
