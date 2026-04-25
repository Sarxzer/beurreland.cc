document.querySelectorAll(".js-revoke-form").forEach((form) => {
    form.addEventListener("submit", (event) => {
        const ok = window.confirm("Revoke this API key? This cannot be undone.");
        if (!ok) {
            event.preventDefault();
        }
    });
});

document.querySelectorAll("[data-copy-target]").forEach((button) => {
    button.addEventListener("click", async () => {
        const targetId = button.getAttribute("data-copy-target");
        const input = document.getElementById(targetId);

        if (!input) {
            return;
        }

        try {
            await navigator.clipboard.writeText(input.value);
            button.textContent = "Copied!";
            setTimeout(() => {
                button.textContent = "Copy";
            }, 1200);
        } catch (err) {
            input.select();
            document.execCommand("copy");
            button.textContent = "Copied!";
            setTimeout(() => {
                button.textContent = "Copy";
            }, 1200);
        }
    });
});

const newKeyBlock = document.getElementById("new-key-block");
if (newKeyBlock) {
    newKeyBlock.scrollIntoView({ behavior: "smooth", block: "center" });
}
