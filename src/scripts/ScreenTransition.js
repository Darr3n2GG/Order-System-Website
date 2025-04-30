const uniqueTags = Array.from(
    new Set([...document.querySelectorAll('*')].map(el => el.tagName.toLowerCase()))
).filter(tag => tag.includes('-'));

Promise.all(uniqueTags.map(tag => customElements.whenDefined(tag)))
    .then(() => {
        document.body.classList.add("loaded");
    });