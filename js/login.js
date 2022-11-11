// View password as text

let eye = document.querySelector(".eye");
eye.addEventListener("click", () => {
  let inputArea = document.querySelector("#password");
  let icon = eye.querySelector("i");
  if (inputArea.type === "password") {
    inputArea.type = "text";
    icon.classList.remove("fa-eye");
    icon.classList.add("fa-eye-slash");
  } else {
    inputArea.type = "password";
    icon.classList.remove("fa-eye-slash");
    icon.classList.add("fa-eye");
  }
});

// Clearing error message on text filed focus
let err = document.querySelector(".error");
let inputs = document.querySelectorAll("form input");
inputs.forEach((e) => {
  e.addEventListener("focus", () => {
    if (err) {
      err.style.display = "none";
    }
  });
});
