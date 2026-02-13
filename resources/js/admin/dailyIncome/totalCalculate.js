$(document).ready(function () {
  const $form = $("#daily-income-form");
  if (!$form.length) return;

  function calculateOverallTotals() {
    let totalPrice = 0;
    let totalInvestment = 0;
    let totalProfit = 0;

    $(".product-row").each(function () {
      const price =
        parseFloat($(this).find(".price").val().replace(/,/g, "")) || 0;
      const investment =
        parseFloat($(this).find(".investment").val().replace(/,/g, "")) || 0;
      const profit =
        parseFloat($(this).find(".profit").val().replace(/,/g, "")) || 0;

      totalPrice += price;
      totalInvestment += investment;
      totalProfit += profit;
    });

    $("#total-price").text(totalPrice.toLocaleString());
    $("#total-investment").text(totalInvestment.toLocaleString());
    $("#total-profit").text(totalProfit.toLocaleString());
  }

  // Listen for changes in row totals (which are updated by calculate.js)
  $(document).on("input change", ".price, .investment, .profit", function () {
    calculateOverallTotals();
  });

  // Also trigger when rows are added or removed
  $(document).on("click", "#add-row, .remove-row", function () {
    // Wait a bit for the row to be added/removed from DOM
    setTimeout(calculateOverallTotals, 50);
  });

  // Initial calculation
  calculateOverallTotals();
});
