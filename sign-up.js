const togglePassword = document.querySelector("#togglePassword");
const togglePassword2 = document.querySelector("#togglePassword2");
const password1 = document.querySelector("#password");
const password2 = document.querySelector("#password2");

togglePassword.addEventListener("click", function () {

    const type = password1.getAttribute("type") === "password" ? "text" : "password";
    password1.setAttribute("type", type);

    this.classList.toggle("bi-eye");
});

togglePassword2.addEventListener("click", function () {

    const type = password2.getAttribute("type") === "password" ? "text" : "password";
    password2.setAttribute("type", type);

    this.classList.toggle("bi-eye");
});