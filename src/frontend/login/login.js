const formLogin = document.querySelector(".form_login");

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