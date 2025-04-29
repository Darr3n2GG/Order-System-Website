export default class FormValidator {
    static validateForm(formValidity) {
        for (const fieldId in formValidity) {
            if (this.validateField(formValidity, fieldId) === false) { return false; }
        }
        return true;
    }

    static validateField(formValidity, fieldId) {
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
}