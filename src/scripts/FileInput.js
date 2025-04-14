export function inputFile(multiple, accept) {
    const input = document.createElement("input");
    input.type = "file";
    input.multiple = multiple;
    input.accept = accept;

    input.addEventListener("change", ({ target }) => {
        const files = target.files;
        this.dispatchEvent(new CustomEvent("file_received", { files: files }))
    })

    input.click();
}