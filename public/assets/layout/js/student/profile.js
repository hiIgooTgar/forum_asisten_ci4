document.addEventListener("DOMContentLoaded", function () {
  const facultySelect = $("#faculty_params");
  const studyProgramSelect = $("#study_program_params");
  const classGroupSelect = $("#class_params");

  const baseUrl = (window.baseUrl || "").replace(/\/+$/, "");

  function fetchStudyPrograms(
    facultyParams,
    selectedProgramParams = null,
    selectedClassParams = null,
  ) {
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

    if (!facultyParams) return;

    fetch(`${baseUrl}/student/profile/get-study-programs/${facultyParams}`)
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
            const isSelected =
              selectedProgramParams &&
              String(selectedProgramParams) === String(item.program_main);

            const newOption = new Option(
              item.program_name + level,
              item.program_main,
              false,
              isSelected,
            );
            studyProgramSelect.append(newOption);
          });
          studyProgramSelect.prop("disabled", false);
        } else {
          studyProgramSelect.append(
            '<option value="" disabled selected>-- Tidak ada prodi --</option>',
          );
        }
        studyProgramSelect.trigger("change.select2");

        const currentProdi = studyProgramSelect.val();
        if (currentProdi) {
          fetchClassGroups(currentProdi, selectedClassParams);
        }
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

  function fetchClassGroups(studyProgramParams, selectedClassParams = null) {
    classGroupSelect
      .empty()
      .append(
        '<option value="" disabled selected>-- Memuat Kelas... --</option>',
      )
      .prop("disabled", true)
      .trigger("change.select2");

    if (!studyProgramParams) return;

    fetch(`${baseUrl}/student/profile/get-class-groups/${studyProgramParams}`)
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
            const isSelected =
              selectedClassParams &&
              String(selectedClassParams) === String(item.class_main);

            const newOption = new Option(
              item.class_name,
              item.class_main,
              false,
              isSelected,
            );
            classGroupSelect.append(newOption);
          });
          classGroupSelect.prop("disabled", false);
        } else {
          classGroupSelect.append(
            '<option value="" disabled selected>-- Tidak ada kelas --</option>',
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

  facultySelect.on("change", function () {
    const facultyParams = $(this).val();
    fetchStudyPrograms(facultyParams);
  });

  studyProgramSelect.on("change", function () {
    const studyProgramParams = $(this).val();
    fetchClassGroups(studyProgramParams);
  });

  const initialFaculty = facultySelect.val();
  const initialProdi = studyProgramSelect.attr("data-selected");
  const initialClass = classGroupSelect.attr("data-selected");

  if (initialFaculty && studyProgramSelect.children("option").length <= 1) {
    fetchStudyPrograms(initialFaculty, initialProdi, initialClass);
  }
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

const cropInput = document.querySelector(".crop-file-input");
if (cropInput) {
  cropInput.addEventListener("change", function (e) {
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

      const profileRealInput = document.getElementById("profile_real_input");
      if (profileRealInput) {
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        profileRealInput.files = dataTransfer.files;
      }
    }
  });
}
