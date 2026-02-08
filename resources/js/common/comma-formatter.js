$(document).ready(function () {
  function formatNumber(num) {
    if (num === null || num === undefined || num === "") return "";
    let val = num.toString().replace(/[^0-9.]/g, "");
    if (val === "") return "";
    let parts = val.split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    return parts.join(".");
  }

  function unformatNumber(str) {
    if (typeof str !== "string") return str;
    return str.replace(/,/g, "");
  }

  $(document).on("input", ".comma-format", function () {
    let cursorPosition = this.selectionStart;
    let originalLength = this.value.length;

    let val = $(this).val();
    let formatted = formatNumber(val);
    $(this).val(formatted);

    // Adjust cursor position
    let newLength = formatted.length;
    this.setSelectionRange(
      cursorPosition + (newLength - originalLength),
      cursorPosition + (newLength - originalLength),
    );
  });

  // Format fields on page load
  $(".comma-format").each(function () {
    if ($(this).val()) {
      $(this).val(formatNumber($(this).val()));
    }
  });

  // Strip commas before form submission
  $(document).on("submit", "form", function () {
    $(this)
      .find(".comma-format")
      .each(function () {
        $(this).val(unformatNumber($(this).val()));
      });
  });
});
