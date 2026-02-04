$(document).ready(function () {
  const validate = document.getElementById("toast-success");
  if (validate) {
    setTimeout(() => {
      validate.style.display = "none";
    }, 5000);
  }
});
