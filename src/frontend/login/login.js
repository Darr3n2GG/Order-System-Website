import FormValidator from "../../scripts/FormValidator.js";

const formLogin = document.querySelector(".form_login");

formLogin.addEventListener("submit", (event) => {
    event.preventDefault()
    if (FormValidator.validateForm(formLoginValidity)) { formLogin.submit() }
})

formLogin.addEventListener("input", (event) => {
    const id = event.target.id;
    FormValidator.validateField(formLoginValidity, id);
})

const formLoginValidity = {
    nama: { condition: (value) => handleNamaValidation(value) },
    password: { condition: (value) => handlePasswordValidation(value) }
};

function handleNamaValidation(value) {
    if (value === "") {
        return "Field nama kosong.";
    } else if (!isValidCharacters(value)) {
        return "Field nama terdapat character invalid.";
    } else {
        return "";
    }
}

function handlePasswordValidation(value) {
    if (value === "") {
        return "Field password kosong.";
    } else if (!isValidCharacters(value)) {
        return "Field password terdapat character invalid.";
    } else {
        return "";
    }
}

function isValidCharacters(value) {
    const whitelistPattern = /^[A-Za-z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?`~]+$/;
    return whitelistPattern.test(value);
}