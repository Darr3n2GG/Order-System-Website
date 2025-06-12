class BaseViewModel {
    constructor(model) {
        this.model = model;
        this.filters = {};
        this.validators = {}; // { fieldName: (value) => errorMsg }
    }

    getApiUrl() {
        return this.model.getApiUrl();
    }
    
    async getData() {
        const data = await this.model.get(this.filters);
        return data.details || [];
    }

    insertData(data) {
        return this.model.insert(data);
    }

    updateData(data) {
        return this.model.update(data);
    }

    deleteData(id) {
        return this.model.delete(id);
    }

    validateField(field, value) {
        if (this.validators[field]) {
            return this.validators[field](value);
        }
        return ""; // No error by default
    }
}

export default BaseViewModel;
