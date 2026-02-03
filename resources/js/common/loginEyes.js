const togglePassword = document.querySelector("#togglePassword");
const password = document.querySelector("#password");
const removeEyes = document.querySelector("#removeEyes");
const showEyes = document.querySelector("#showEyes");

if (togglePassword) {
  togglePassword.addEventListener("click", function (e) {
    const type =
      password.getAttribute("type") === "password" ? "text" : "password";
    password.setAttribute("type", type);
  });
}

if (removeEyes) {
  removeEyes.addEventListener("click", () => {
    removeEyes.classList.add("hidden");
    showEyes.classList.remove("hidden");
  });
}
if (showEyes) {
  showEyes.addEventListener("click", () => {
    showEyes.classList.add("hidden");
    removeEyes.classList.remove("hidden");
  });
}
