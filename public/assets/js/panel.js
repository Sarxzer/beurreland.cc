// Made by copilot for a temporary admin panel, will be removed later when a more complete and secure solution is implemented
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

const recipientModeSelect = document.querySelector(".js-recipient-mode");
const singleRecipientBlock = document.querySelector(".js-recipient-single");
const singleRecipientInput = document.getElementById("recipient_email");

const updateRecipientMode = () => {
    if (!recipientModeSelect || !singleRecipientBlock || !singleRecipientInput) {
        return;
    }

    const isSingle = recipientModeSelect.value === "single";
    singleRecipientBlock.style.display = isSingle ? "block" : "none";
    singleRecipientInput.required = isSingle;
};

if (recipientModeSelect) {
    updateRecipientMode();
    recipientModeSelect.addEventListener("change", updateRecipientMode);
}

document.querySelectorAll(".js-send-mail-form").forEach((form) => {
    form.addEventListener("submit", (event) => {
        const mode = recipientModeSelect ? recipientModeSelect.value : "single";
        const confirmationText = mode === "all_accounts"
            ? "Envoyer ce mail a tous les comptes avec email valide ?"
            : "Envoyer ce mail a ce destinataire ?";

        if (!window.confirm(confirmationText)) {
            event.preventDefault();
        }
    });
});
