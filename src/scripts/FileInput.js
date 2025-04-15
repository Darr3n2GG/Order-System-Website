export class FileInput {
    #input;

    constructor(multiple, accept) {
        this.#input = this.#createInput(multiple, accept)
        this.#input.addEventListener("click", ({ target }) => {
            target.value = null
        })
    }

    #createInput(multiple, accept) {
        const input = document.createElement("input");
        input.type = "file";
        input.multiple = multiple;
        input.accept = accept;

        return input;
    }

    getInput() {
        return this.#input;
    }

    clickInput() {
        this.#input.click();
    }
}