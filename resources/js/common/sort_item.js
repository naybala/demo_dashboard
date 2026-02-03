import Sortable from "sortablejs";
import axios from "axios";

document.addEventListener("DOMContentLoaded", () => {
  const tableValue = document.getElementById("sortable-table").value;
  const table = document.getElementById(tableValue);
  const model = document.getElementById("model").value;
  const currentPage = parseInt(document.getElementById("current-page").value);
  const perPage = parseInt(document.getElementById("per-page").value);
  if (table) {
    Sortable.create(table, {
      animation: 150,
      handle: ".sortable-item",
      onEnd: function () {
        const order = [];
        const pageOffset = (currentPage - 1) * perPage;

        document
          .querySelectorAll(`#${tableValue} .sortable-item`)
          .forEach((el, index) => {
            order.push({
              id: el.dataset.id,
              position: pageOffset + index + 1,
              model: model,
            });
          });
        axios
          .post("/map-price-regions/reorder", { order })
          .then((response) => {
            console.log(response.data.message);
          })
          .catch((error) => {
            console.error(
              "Failed to reorder:",
              error.response?.data || error.message
            );
          });
      },
    });
  }
});

// for usage of Sortable
//  <tbody id="sortable-table-map-price">
//    @foreach ($data['data'] as $record)
//       <tr class="sortable-item hover:bg-gray-300" data-id="{{ $record['id'] }}">
//        <x-table.body-column :field="$record['name']" limit="20" />
//        <x-table.body-column :field="$record['is_free']" limit="20" />
//        <x-table.action :id="$record['id']" field="mapPriceRegions" />
//       </tr>
//    @endforeach
//  </tbody>
//  <input type="hidden" value="map_price_regions" id="model">
//  <input type="hidden" value="sortable-table-map-price" id="sortable-table">
