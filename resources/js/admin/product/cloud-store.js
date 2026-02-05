import Cropper from "cropperjs";
import "cropperjs/dist/cropper.css";

$(document).ready(function () {
  let cropper;
  let selectedImgElement;
  let selectedFileIndex;
  let imageFiles = [];
  let deleteImgIndex;
  let whileUploading = false;
  let imgIndex = 0;
  var apiPath = $("#apiPath").val();

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
          deleteImgIndex = $(this).parent().find("img").attr("imgIndex");
          imageFiles = imageFiles.filter(
            (item) => item.imgIndex != deleteImgIndex,
          );
          console.log(imageFiles);
          $(`div[imgIndex="${deleteImgIndex}"]`).remove();
        });

        // Image click handler for cropping
        $img.on("click", function () {
          selectedImgElement = $img;
          selectedFileIndex = $(this).attr("imgIndex");

          // console.log(index)
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
            // console.log(selectedFileIndex,img.imgIndex)
            return img.imgIndex == selectedFileIndex
              ? { ...img, file: croppedFile }
              : img;
          });
        };
        reader.readAsDataURL(blob);
      });
      console.log(imageFiles);
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
    if (imageFiles.length == 0) {
      $("#photo_count-error").show();
      return false;
    }
    $("#photo_count-error").hide();
    $("#ajax-error-happening").hide();
    // console.log(imageFiles);
    $(".ajax-error-shower").text("");
    const formData = new FormData($("#listing-form")[0]);
    formData.append("photo_count", imageFiles.length);
    axios
      .post(`/${apiPath}`, formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      })
      .then(async (response) => {
        console.log(response.data);
        if (response.data.code === 200) {
          $("body").addClass("loading-overlay");
          var links = response.data.data;
          if (links.length > 0) {
            whileUploading = true; // Set uploading flag
            const uploadPromises = links.map((link, index) => {
              return axios.put(link.url, imageFiles[index].file, {
                headers: {
                  "Content-Type": "image/jpeg",
                  "x-amz-acl": "public-read", // to set file to public in DigitalOcean
                },
              });
            });
            // alert(response.data.message);
            await Promise.all(uploadPromises).then(() => {
              whileUploading = false; // Reset uploading flag
              $("body").removeClass("loading-overlay");
              Swal.fire({
                title: "Upload Complete",
                text: "Your files have been uploaded successfully.",
                icon: "success",
                confirmButtonText: "View Listings",
              }).then((result) => {
                if (result.isConfirmed) {
                  window.location.href = `/${apiPath}`;
                }
              });
            });
          } else {
            $("body").removeClass("loading-overlay");
            Swal.fire({
              title: "Upload Complete",
              text: "Your files have been uploaded successfully.",
              icon: "success",
              confirmButtonText: "Go to Listings",
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = `/${apiPath}`;
              }
            });
          }
        }
      })
      .catch((error) => {
        const { errors } = error.response.data;
        console.log(error);
        $("#ajax-error-happening").show();
        switch (error.status) {
          case 422:
            Object.entries(errors).forEach(([field, messages]) => {
              console.log(field, messages);
              $(`#${field}-error`).text(messages[0]);
            });
            break;
          case 500:
            console.log(error.message);
            break;
        }
      });
  });
});
