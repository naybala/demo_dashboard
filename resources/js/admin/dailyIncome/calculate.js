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

  function formatNumber(num) {
    if (num === null || num === undefined || isNaN(num)) return "0";
    return new Intl.NumberFormat("en-US").format(num);
  }

  function unformatNumber(str) {
    if (typeof str !== "string") return str;
    return parseFloat(str.replace(/,/g, "")) || 0;
  }

  function calculate() {
    const qty = unformatNumber($amount.val());
    $price.val(formatNumber((basePrice * qty).toFixed(2)));
    $investment.val(formatNumber((baseInvestment * qty).toFixed(2)));
    $profit.val(formatNumber((baseProfit * qty).toFixed(2)));
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
    let val = $(this)
      .val()
      .replace(/[^0-9.]/g, "");
    if (val) {
      $(this).val(formatNumber(val));
    }
    calculate();
  });

  // Initial load for Edit
  const initialProductId = $form.find('input[name="own_product_id"]').val();
  if (initialProductId) {
    updateProduct(initialProductId);
    // Format amount if it has a value
    if ($amount.val()) {
      $amount.val(formatNumber($amount.val()));
    }
  }

  // Before form submit, strip commas
  $form.on("submit", function () {
    $price.val(unformatNumber($price.val()));
    $investment.val(unformatNumber($investment.val()));
    $profit.val(unformatNumber($profit.val()));
    $amount.val(unformatNumber($amount.val()));
  });
});
