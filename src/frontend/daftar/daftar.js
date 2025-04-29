const formLogin = document.querySelector(".form_daftar");

formLogin.addEventListener("submit", (event) => {
    event.preventDefault()
    if (validateForm(formLoginValidity)) { formLogin.submit() }
})

formLogin.addEventListener("input", (event) => {
    const id = event.target.id;
    validateField(formLoginValidity, id);
})

const formLoginValidity = {
    nama: { condition: (value) => handleNamaValidation(value) },
    phone: { condition: (value) => handlePhoneValidation(value) },
    password: { condition: (value) => handlePasswordValidation(value) }
};

function validateField(formValidity, fieldId) {
    const { condition } = formValidity[fieldId];
    const field = document.getElementById(fieldId);

    const message = condition(field.value.trim())
    if (message === "") {
        field.setCustomValidity("");
        return true;
    } else {
        field.setCustomValidity(message);
        field.reportValidity();
        return false;
    }
}

function validateForm(formValidity) {
    for (const fieldId in formValidity) {
        if (validateField(formValidity, fieldId) === false) { return false; }
    }
    return true;
}

function handleNamaValidation(value) {
    if (value === "") {
        return "Field nama kosong.";
    } else if (!isValidCharacters(value)) {
        return "Field nama terdapat character invalid.";
    } else if (value.length >= 100) {
        return "Field nama mesti kurang daripada 100.";
    } else {
        return "";
    }
}

function handlePhoneValidation(value) {
    if (value === "") {
        return "Field nombor phone kosong.";
    } else if (!isValidPhoneNumber(value)) {
        return "Field password invalid.";
    } else {
        return "";
    }
}

function handlePasswordValidation(value) {
    if (value === "") {
        return "Field password kosong.";
    } else if (!isValidCharacters(value)) {
        return "Field password terdapat character invalid.";
    } else if (value.length <= 8 || value.length >= 128) {
        return "Field password mesti melebihi 8 dan kurang daripada 128.";
    } else {
        return "";
    }
}

function isValidCharacters(value) {
    const whitelistPattern = /^[A-Za-z0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?`~]+$/;
    return whitelistPattern.test(value);
}

function isValidPhoneNumber(value) {
    const whitelistPattern = /^(\+?6?01)[02-46-9]-*[0-9]{7}$|^(\+?6?01)[1]-*[0-9]{8}$/;
    return whitelistPattern.test(value);
}
