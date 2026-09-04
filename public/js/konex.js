document.querySelectorAll("[data-kx-submit]").forEach((form) => {
    form.addEventListener("submit", (event) => {
        event.preventDefault();
        const next = form.getAttribute("data-kx-next");
        if (next) {
            window.location.href = next;
        }
    });
});
