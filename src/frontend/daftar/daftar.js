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
        return "Input nama kosong.";
    } else if (!isValidCharacters(value)) {
        return "Input nama terdapat character tidak sah.";
    } else if (value.length >= 100) {
        return "Input nama mesti kurang daripada 100.";
    } else {
        return "";
    }
}

function handlePhoneValidation(value) {
    if (value === "") {
        return "Input nombor phone kosong.";
    } else if (!isValidPhoneNumber(value)) {
        return "Input password tidak sah.";
    } else {
        return "";
    }
}

function handlePasswordValidation(value) {
    if (value === "") {
        return "Input password kosong.";
    } else if (!isValidCharacters(value)) {
        return "Input password terdapat character tidak sah.";
    } else if (value.length <= 8 || value.length >= 128) {
        return "Input password mesti melebihi 8 dan kurang daripada 128.";
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
