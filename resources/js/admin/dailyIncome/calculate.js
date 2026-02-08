$(document).ready(function () {
  const $form = $("#daily-income-form");
  if (!$form.length) return;

  const products = JSON.parse($form.attr("data-products") || "{}");
  const $amount = $("#amount");
  const $price = $("#price");
  const $investment = $("#investment");
  const $profit = $("#profit");
  const $unitIdHidden = $("#unit_id_hidden");
  const $unitName = $("#unit_name");
  const $productWrapper = $("#product-select-wrapper");

  let basePrice = 0;
  let baseInvestment = 0;
  let baseProfit = 0;

  function calculate() {
    // Rely on global unformatNumber if possible, or just strip here for calculation
    const qty = parseFloat($amount.val().replace(/,/g, "")) || 0;

    // We update values; comma-formatter.js will handle the visual formatting if they have the class
    // But since we are setting .val() programmatically, we might need to trigger 'input' or format manually
    const p = (basePrice * qty).toFixed(2);
    const i = (baseInvestment * qty).toFixed(2);
    const pr = (baseProfit * qty).toFixed(2);

    $price.val(p).trigger("input");
    $investment.val(i).trigger("input");
    $profit.val(pr).trigger("input");
  }

  function updateProduct(id) {
    const product = products[id];
    if (product) {
      $unitIdHidden.val(product.unit_id);
      $unitName.val(product.unit_name);
      basePrice = parseFloat(product.price) || 0;
      baseInvestment = parseFloat(product.investment) || 0;
      baseProfit = parseFloat(product.profit) || 0;
    } else {
      $unitIdHidden.val("");
      $unitName.val("");
      basePrice = 0;
      baseInvestment = 0;
      baseProfit = 0;
    }
    calculate();
  }

  // searchable-select dispatches a native 'change' event on its x-data div
  // We listen for it on the wrapper
  $productWrapper.on("change", function (e) {
    const id = e.originalEvent.detail;
    updateProduct(id);
  });

  $amount.on("input", function () {
    calculate();
  });

  // Initial load for Edit
  const initialProductId = $form.find('input[name="own_product_id"]').val();
  if (initialProductId) {
    updateProduct(initialProductId);
  }

  // Comma formatting and final stripping is handled by common/comma-formatter.js
});
