$(document).ready(function () {
  const $form = $("#daily-income-form");
  if (!$form.length) return;

  const products = JSON.parse($form.attr("data-products") || "{}");
  const $rowsContainer = $("#product-rows");

  function calculateRow($row) {
    const qty = parseFloat($row.find(".amount").val().replace(/,/g, "")) || 0;

    const basePrice = parseFloat($row.data("base-price") || 0);
    const baseInvestment = parseFloat($row.data("base-investment") || 0);
    const baseProfit = parseFloat($row.data("base-profit") || 0);

    $row
      .find(".price")
      .val((basePrice * qty).toFixed(2))
      .trigger("input");
    $row
      .find(".investment")
      .val((baseInvestment * qty).toFixed(2))
      .trigger("input");
    $row
      .find(".profit")
      .val((baseProfit * qty).toFixed(2))
      .trigger("input");
  }

  function updateProduct($row, id) {
    const product = products[id];

    if (product) {
      $row.find(".unit-id-hidden").val(product.unit_id);
      $row.find(".unit-name").val(product.unit_name);
      $row.data("base-price", parseFloat(product.price) || 0);
      $row.data("base-investment", parseFloat(product.investment) || 0);
      $row.data("base-profit", parseFloat(product.profit) || 0);
    } else {
      $row.find(".unit-id-hidden").val("");
      $row.find(".unit-name").val("");

      $row.data("base-price", 0);
      $row.data("base-investment", 0);
      $row.data("base-profit", 0);
    }

    calculateRow($row);
  }

  // change product
  $rowsContainer.on("change", ".product-select-wrapper", function (e) {
    const id = e.originalEvent.detail;
    const $row = $(this).closest(".product-row");
    updateProduct($row, id);
  });

  // change amount
  $rowsContainer.on("input", ".amount", function () {
    const $row = $(this).closest(".product-row");
    calculateRow($row);
  });

  // add row
  $("#add-row").on("click", function () {
    const $newRow = $rowsContainer.find(".product-row:first").clone();

    // Reset values in the new row
    $newRow.find("input").val("");
    $newRow.find(".amount").val("1");
    $newRow.find(".product-select-wrapper [x-data]").each(function () {
      // Find the x-data element and reset its state
      // However, cloning Alpine elements is tricky.
      // We need to clean up the cloned element's Alpine internal state before re-init.
    });

    $newRow.data("base-price", 0);
    $newRow.data("base-investment", 0);
    $newRow.data("base-profit", 0);

    $rowsContainer.append($newRow);

    // Re-initialize Alpine.js for the new row
    if (window.Alpine) {
      window.Alpine.initTree($newRow[0]);
    }
  });

  // remove row
  $rowsContainer.on("click", ".remove-row", function () {
    if ($rowsContainer.find(".product-row").length > 1) {
      $(this).closest(".product-row").remove();
    }
  });
});
