import startQuillEditor from "./productStartQuillEditor";

$(document).ready(function () {
  const descriptionEl = document.getElementById("description");
  const descriptionOtherEl = document.getElementById("description_other");

  // Initialize Quill for Description
  if (document.querySelector("#quill_description")) {
    const descriptionQuill = startQuillEditor(
      "#quill_description",
      "products/quill",
      descriptionEl.value,
    );

    // Sync Quill content to hidden input on change
    descriptionQuill.on("text-change", function () {
      descriptionEl.value = descriptionQuill.root.innerHTML;
    });
  }

  // Initialize Quill for Description (Other)
  if (document.querySelector("#quill_description_other")) {
    const descriptionOtherQuill = startQuillEditor(
      "#quill_description_other",
      "products/quill",
      descriptionOtherEl.value,
    );

    // Sync Quill content to hidden input on change
    descriptionOtherQuill.on("text-change", function () {
      descriptionOtherEl.value = descriptionOtherQuill.root.innerHTML;
    });
  }
});
