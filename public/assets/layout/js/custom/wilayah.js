$(document).ready(function () {
  const $prov = $("#select_province");
  const $reg = $("#select_regency");
  const $dist = $("#select_district");
  const $vill = $("#select_village");

  const baseUrl = (window.baseUrl || window.location.origin).replace(
    /\/+$/,
    "",
  );

  const initialProv = $prov.data("initial") || "";
  const initialReg = $reg.data("initial") || "";
  const initialDist = $dist.data("initial") || "";
  const initialVill = $vill.data("initial") || "";

  // Load Provinsi Awal
  $.getJSON(`${baseUrl}/api/wilayah/provinces`, function (res) {
    if (res && res.data) {
      populateDropdown($prov, res.data, initialProv, "-- Pilih Provinsi --");

      let selectedCode = $prov.find("option:selected").data("code");
      if (selectedCode) {
        loadRegencies(selectedCode, initialReg);
      }
    }
  });

  $prov.on("change", function () {
    let code = $(this).find("option:selected").data("code");
    resetDropdown($reg, "-- Pilih Kabupaten/Kota --");
    resetDropdown($dist, "-- Pilih Kecamatan --");
    resetDropdown($vill, "-- Pilih Kelurahan/Desa --");

    if (code) {
      loadRegencies(code);
    }
  });

  $reg.on("change", function () {
    let code = $(this).find("option:selected").data("code");
    resetDropdown($dist, "-- Pilih Kecamatan --");
    resetDropdown($vill, "-- Pilih Kelurahan/Desa --");

    if (code) {
      loadDistricts(code);
    }
  });

  $dist.on("change", function () {
    let code = $(this).find("option:selected").data("code");
    resetDropdown($vill, "-- Pilih Kelurahan/Desa --");

    if (code) {
      loadVillages(code);
    }
  });

  function loadRegencies(provCode, targetVal = "") {
    $reg.prop("disabled", true);
    $.getJSON(`${baseUrl}/api/wilayah/regencies/${provCode}`, function (res) {
      if (res && res.data) {
        populateDropdown(
          $reg,
          res.data,
          targetVal,
          "-- Pilih Kabupaten/Kota --",
        );
        $reg.prop("disabled", false);

        let selectedCode = $reg.find("option:selected").data("code");
        if (selectedCode && targetVal) {
          loadDistricts(selectedCode, initialDist);
        }
      }
    });
  }

  function loadDistricts(regCode, targetVal = "") {
    $dist.prop("disabled", true);
    $.getJSON(`${baseUrl}/api/wilayah/districts/${regCode}`, function (res) {
      if (res && res.data) {
        populateDropdown($dist, res.data, targetVal, "-- Pilih Kecamatan --");
        $dist.prop("disabled", false);

        let selectedCode = $dist.find("option:selected").data("code");
        if (selectedCode && targetVal) {
          loadVillages(selectedCode, initialVill);
        }
      }
    });
  }

  function loadVillages(distCode, targetVal = "") {
    $vill.prop("disabled", true);
    $.getJSON(`${baseUrl}/api/wilayah/villages/${distCode}`, function (res) {
      if (res && res.data) {
        populateDropdown(
          $vill,
          res.data,
          targetVal,
          "-- Pilih Kelurahan/Desa --",
        );
        $vill.prop("disabled", false);
      }
    });
  }

  function populateDropdown($el, dataList, selectedValue, placeholder) {
    let html = `<option value="">${placeholder}</option>`;

    $.each(dataList, function (i, item) {
      let isSelected =
        selectedValue &&
        selectedValue.toString().trim().toLowerCase() ===
          item.name.toString().trim().toLowerCase();
      html += `<option value="${item.name}" data-code="${item.code}" ${isSelected ? "selected" : ""}>${item.name}</option>`;
    });

    $el.html(html);
    $el.trigger("change.select2");
  }

  function resetDropdown($el, placeholder) {
    $el.html(`<option value="">${placeholder}</option>`).prop("disabled", true);
    $el.trigger("change.select2");
  }
});
