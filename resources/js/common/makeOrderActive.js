const $success = $("#delete");
if ($success.length) {
  Swal.fire("Deleted!", "Your file has been deleted.", "success");
}

$(".activeActionButton").on("submit", function(e) {
  e.preventDefault(); //stop form from submitting
  
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning", 
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, delete it!"
  }).then((result) => {
    if (result.isConfirmed) {
      $(this).submit();
      if ($success.length) {
        Swal.fire("Deleted!", "Your file has been deleted.", "success");
      }
    }
  });
});
