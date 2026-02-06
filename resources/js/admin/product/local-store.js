import Cropper from "cropperjs";
import "cropperjs/dist/cropper.css";

$(document).ready(function () {
  let cropper;
  let selectedImgElement;
  let selectedFileIndex;
  let imageFiles = [];
  let whileUploading = false;
  let imgIndex = 0;
  var apiPath = $("#apiPath").val();
  const isUpdate = $("#is-update").val() === "true";
  const productId = $("#product-id").val();

  // Load existing photos if in update mode
  if (isUpdate) {
    const existingPhotos = JSON.parse($("#existing-photos").val() || "[]");
    const existingPhotoPaths = JSON.parse(
      $("#existing-photo-paths").val() || "[]",
    );

    existingPhotos.forEach((photoUrl, index) => {
      const photoPath = existingPhotoPaths[index];
      imgIndex++;
      const $wrapper = $("<div>", {
        imgIndex: imgIndex,
        class: "relative group existing-photo",
        "data-path": photoPath,
      });
      const $img = $("<img>", {
        src: photoUrl,
        imgIndex: imgIndex,
        class: "w-24 h-24 object-cover rounded-md",
      });

      // Add delete button for existing photos
      const $deleteBtn = $("<button>", {
        type: "button",
        imgIndex: imgIndex,
        class:
          "absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hidden group-hover:block",
        html: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
      });

      $deleteBtn.on("click", function (e) {
        e.stopPropagation();
        $wrapper.remove();
      });

      $wrapper.append($img, $deleteBtn);
      $("#previewContainer").append($wrapper);
    });
  }

  $("#fileInput").on("change", function (event) {
    $.each(event.target.files, function (index, file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        imgIndex++;
        // Create wrapper div for image and delete button
        const $wrapper = $("<div>", {
          imgIndex: imgIndex,
          class: "relative group",
        });
        const $img = $("<img>", {
          src: e.target.result,
          imgIndex: imgIndex,
          class: "w-24 h-24 object-cover cursor-pointer rounded-md",
        });

        // Add delete button
        const $deleteBtn = $("<button>", {
          type: "button",
          imgIndex: imgIndex,
          class:
            "absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hidden group-hover:block",
          html: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
        });

        // Delete handler
        $deleteBtn.on("click", function (e) {
          e.stopPropagation(); // Prevent image click event
          const indexToDelete = $(this).attr("imgIndex");
          imageFiles = imageFiles.filter(
            (item) => item.imgIndex != indexToDelete,
          );
          $(`div[imgIndex="${indexToDelete}"]`).remove();
        });

        // Image click handler for cropping
        $img.on("click", function () {
          selectedImgElement = $img;
          selectedFileIndex = $(this).attr("imgIndex");

          $("#cropperImage").attr("src", e.target.result);
          $("#cropModal").removeClass("hidden");

          if (cropper) cropper.destroy();
          cropper = new Cropper($("#cropperImage")[0], {
            aspectRatio: 1,
            viewMode: 1,
          });
        });

        $wrapper.append($img, $deleteBtn);
        $("#previewContainer").append($wrapper);
        imageFiles.push({ file: file, imgIndex: imgIndex });
      };
      reader.readAsDataURL(file);
    });
  });

  $("#closeModal").on("click", function () {
    $("#cropModal").addClass("hidden");
    if (cropper) cropper.destroy();
  });

  $("#cropImageBtn").on("click", function () {
    if (cropper) {
      cropper.getCroppedCanvas().toBlob((blob) => {
        const croppedFile = new File(
          [blob],
          `cropped_image_${selectedFileIndex}.jpg`,
          {
            type: "image/jpeg",
            lastModified: new Date().getTime(),
          },
        );

        const reader = new FileReader();
        reader.onload = function (e) {
          selectedImgElement.attr("src", e.target.result);
          imageFiles = imageFiles.map((img) => {
            return img.imgIndex == selectedFileIndex
              ? { ...img, file: croppedFile }
              : img;
          });
        };
        reader.readAsDataURL(blob);
      });
      $("#cropModal").addClass("hidden");
    }
  });

  // Add beforeunload event handler
  $(window).on("beforeunload", function (e) {
    if (whileUploading === true) {
      return "Are you sure to cancel uploading and leave this side.";
    }
  });

  $("#btn-submit").click(function (e) {
    e.preventDefault();

    // In update mode, we might not have new images but have existing ones
    const hasExistingPhotos = $(".existing-photo").length > 0;
    if (imageFiles.length == 0 && !hasExistingPhotos && !isUpdate) {
      $("#photo_count-error").show();
      return false;
    }

    $("#photo_count-error").hide();
    $("#ajax-error-happening").hide();

    $(".ajax-error-shower").text("");
    const $form = $("#product-form");
    const formData = new FormData($form[0]);

    // Gather existing photos to keep
    $(".existing-photo").each(function () {
      formData.append("existing_photos[]", $(this).data("path"));
    });

    // Append each new image file to the photos[] array
    formData.delete("photos[]");
    imageFiles.forEach((item) => {
      formData.append("photos[]", item.file);
    });

    // If it's an update, Laravel needs _method: PUT for FormData
    if (isUpdate) {
      formData.append("_method", "PUT");
    }

    $("body").addClass("loading-overlay");
    whileUploading = true;

    const url = isUpdate ? `/${apiPath}/${productId}` : `/${apiPath}`;

    axios
      .post(url, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
      .then((response) => {
        whileUploading = false;
        $("body").removeClass("loading-overlay");
        Swal.fire({
          title: "Success",
          text: response.data.message || "Product saved successfully",
          icon: "success",
          confirmButtonText: "OK",
        }).then((result) => {
          if (result.isConfirmed) {
            window.location.href = `/${apiPath}`;
          }
        });
      })
      .catch((error) => {
        whileUploading = false;
        $("body").removeClass("loading-overlay");
        console.log(error);
        if (
          error.response &&
          error.response.data &&
          error.response.data.errors
        ) {
          const { errors } = error.response.data;
          $("#ajax-error-happening").show();
          Object.entries(errors).forEach(([field, messages]) => {
            let elementId = field.replace(/\./g, "_");
            $(`#${elementId}-error`).text(messages[0]);
            if (field.includes(".")) {
              let baseField = field.split(".")[0];
              $(`#${baseField}-error`).text(messages[0]);
            }
          });
        } else {
          Swal.fire({
            title: "Error",
            text: "Something went wrong. Please try again.",
            icon: "error",
          });
        }
      });
  });
});
