document.addEventListener("DOMContentLoaded", function () {
  const facultySelect = $("#faculty_id");
  const studyProgramSelect = $("#study_program_id");
  const classGroupSelect = $("#class_id");

  const baseUrl = (window.baseUrl || "").replace(/\/+$/, "");

  facultySelect.on("change", function () {
    const facultyId = $(this).val();

    studyProgramSelect
      .empty()
      .append(
        '<option value="" disabled selected>-- Memuat Program Studi... --</option>',
      )
      .prop("disabled", true)
      .trigger("change.select2");

    classGroupSelect
      .empty()
      .append('<option value="" disabled selected>-- Pilih Kelas --</option>')
      .prop("disabled", true)
      .trigger("change.select2");

    if (facultyId) {
      fetch(`${baseUrl}/student/profile/get-study-programs/${facultyId}`)
        .then((res) => {
          if (!res.ok) throw new Error("Network response was not ok");
          return res.json();
        })
        .then((data) => {
          studyProgramSelect
            .empty()
            .append(
              '<option value="" disabled selected>-- Pilih Program Studi --</option>',
            );

          if (data && data.length > 0) {
            data.forEach((item) => {
              const level = item.degree_level ? ` (${item.degree_level})` : "";
              studyProgramSelect.append(
                new Option(item.program_name + level, item.id),
              );
            });
            studyProgramSelect.prop("disabled", false);
          } else {
            studyProgramSelect.append(
              '<option value="" disabled>-- Tidak ada prodi --</option>',
            );
          }
          studyProgramSelect.trigger("change.select2");
        })
        .catch((err) => {
          console.error("Error fetching study programs:", err);
          studyProgramSelect
            .empty()
            .append(
              '<option value="" disabled selected>-- Gagal Memuat Data --</option>',
            )
            .trigger("change.select2");
        });
    }
  });

  studyProgramSelect.on("change", function () {
    const studyProgramId = $(this).val();

    classGroupSelect
      .empty()
      .append(
        '<option value="" disabled selected>-- Memuat Kelas... --</option>',
      )
      .prop("disabled", true)
      .trigger("change.select2");

    if (studyProgramId) {
      fetch(`${baseUrl}/student/profile/get-class-groups/${studyProgramId}`)
        .then((res) => {
          if (!res.ok) throw new Error("Network response was not ok");
          return res.json();
        })
        .then((data) => {
          classGroupSelect
            .empty()
            .append(
              '<option value="" disabled selected>-- Pilih Kelas --</option>',
            );

          if (data && data.length > 0) {
            data.forEach((item) => {
              classGroupSelect.append(new Option(item.class_name, item.id));
            });
            classGroupSelect.prop("disabled", false);
          } else {
            classGroupSelect.append(
              '<option value="" disabled>-- Tidak ada kelas --</option>',
            );
          }
          classGroupSelect.trigger("change.select2");
        })
        .catch((err) => {
          console.error("Error fetching class groups:", err);
          classGroupSelect
            .empty()
            .append(
              '<option value="" disabled selected>-- Gagal Memuat Data --</option>',
            )
            .trigger("change.select2");
        });
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const gpaInput = document.getElementById("gpa_input");
  if (gpaInput) {
    gpaInput.addEventListener("change", function () {
      let val = parseFloat(this.value);
      if (isNaN(val)) return;

      if (val > 4.0) {
        this.value = "4.00";
      } else if (val < 0.1) {
        this.value = "0.10";
      } else {
        this.value = val.toFixed(2);
      }
    });
  }

  const phoneInput = document.getElementById("phone_number_input");
  if (phoneInput) {
    phoneInput.addEventListener("input", function (e) {
      let raw = this.value.replace(/\D/g, "");
      if (raw.length > 20) raw = raw.substring(0, 20);
      const chunks = raw.match(/.{1,4}/g);
      this.value = chunks ? chunks.join("-") : raw;
    });
  }
});

(function () {
  function updateRealtimeClock() {
    const now = new Date();
    const optionsDate = {
      weekday: "long",
      day: "numeric",
      month: "long",
      year: "numeric",
      timeZone: "Asia/Jakarta",
    };
    const formattedDate = new Intl.DateTimeFormat("id-ID", optionsDate).format(
      now,
    );

    const optionsTime = {
      hour: "2-digit",
      minute: "2-digit",
      second: "2-digit",
      hour12: false,
      timeZone: "Asia/Jakarta",
    };
    const formattedTime = new Intl.DateTimeFormat("id-ID", optionsTime)
      .format(now)
      .replace(/\./g, ":");

    const dateElem = document.getElementById("realtime-day-date");
    const clockElem = document.getElementById("realtime-clock");

    if (dateElem) dateElem.textContent = formattedDate;
    if (clockElem) clockElem.textContent = formattedTime + " WIB";
  }

  document.addEventListener("DOMContentLoaded", function () {
    updateRealtimeClock();
    setInterval(updateRealtimeClock, 1000);
  });
})();

document
  .querySelector(".crop-file-input")
  .addEventListener("change", function (e) {
    const file = e.target.files[0];
    const maxSizeBytes = 1.5 * 1024 * 1024;

    if (file) {
      if (file.size > maxSizeBytes) {
        alert(
          "Ukuran file foto terlalu besar. Maksimal ukuran file adalah 1.5 MB.",
        );
        this.value = "";
        return;
      }

      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(file);
      document.getElementById("profile_real_input").files = dataTransfer.files;
    }
  });
