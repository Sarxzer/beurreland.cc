function updateCounter(counterValue, digitElements) {
    const str = counterValue.toString().padStart(6, "0");
    str.split("").forEach((num, i) => {
        console.log(`Updating digit ${i} to ${num}`);
        digitElements[i].textContent = num;
    });
}

const digits = document.querySelectorAll(".digit");

// function incrementCounter() {
//     fetch("/counter.php?increment=1")
//         .then((r) => r.text())
//         .then((n) => {
//             if (isNaN(n)) {
//                 console.error("Invalid counter value received:", n);
//                 return;
//             } else {
//                 let visits = parseInt(n);
//                 updateCounter(visits, digits);
//                 console.log(`Counter updated: ${visits} visits`);
//             }
//         });
// }

function fetchCounter() {
    fetch("/api/v1/counter.php")
        .then((r) => r.text())
        .then((n) => {
            if (isNaN(n)) {
                console.error("Invalid counter value received:", n);
                return;
            } else {
                let visits = parseInt(n);
                updateCounter(visits, digits);
                console.log(`Counter updated: ${visits} visits`);
            }
        });
}

setInterval(fetchCounter, 5000);
fetchCounter(); // initial fetch to set the counter on page load

function waveify(node, className, delayStart = 0) {
    if (node.querySelector && node.querySelector(`span.${className}`)) {
        return delayStart; // already processed
    }
    let delay = delayStart;

    node.childNodes.forEach((child) => {
        if (child.nodeType === Node.TEXT_NODE) {
            const text = child.textContent;
            const frag = document.createDocumentFragment();

            [...text].forEach((char) => {
                const span = document.createElement("span");
                span.classList.add(className);
                span.textContent = char === " " ? "\u00A0" : char;
                span.style.animationDelay = `${delay}s`;
                delay += 0.05;
                frag.appendChild(span);
            });

            child.replaceWith(frag);
        } else if (child.nodeType === Node.ELEMENT_NODE) {
            delay = waveify(child, className, delay);
        }
    });

    return delay;
}

function loadWaveAnimation() {
    document.querySelectorAll(".wave-auto").forEach((el) => {
        waveify(el, "wave");
    });

    document.querySelectorAll(".wave-auto-big").forEach((el) => {
        waveify(el, "waveBig");
    });
}

loadWaveAnimation();

const splashTexts = [
    "Beurre!!!",
    "Gloire au Beurre!!!",
    "Vive le Jambon-Beurre!!!",
    "Butter is love, butter is life!!!",
    "In Butter We Trust!!!",
    "Best viewed in 1920x1080!!!",
    "Hail Beurreland!!!",
    "Hail le Jambon-Beurre!!!",
    "Hail Frescri!!!",
    "Chonk l'a dans le cul!!!",
    "Exterminate the margarine!!!",
    "COLORMATIC",
];

const splashText = splashTexts[Math.floor(Math.random() * splashTexts.length)];

const splashTextElement = document.getElementById("splash-text");

function applySplashTextSizeByLength(element, text) {
    // Longer text shrinks linearly between max and min font sizes.
    const minSizeRem = 0.8;
    const maxSizeRem = 2.75;
    const shortestText = 8;
    const longestText = 36;
    const length = text.trim().length;
    const ratio = (length - shortestText) / (longestText - shortestText);
    const clampedRatio = Math.min(1, Math.max(0, ratio));
    const fontSizeRem = maxSizeRem - clampedRatio * (maxSizeRem - minSizeRem);

    element.style.setProperty("--splash-size", `${fontSizeRem.toFixed(2)}rem`);
}

function renderColormaticSplash(element, text) {
    const colors = [
        "#ff2d55",
        "#ff9500",
        "#ffd60a",
        "#34c759",
        "#32ade6",
        "#0a84ff",
        "#5e5ce6",
        "#bf5af2",
    ];

    element.textContent = "";
    [...text].forEach((char, i) => {
        const span = document.createElement("span");
        span.textContent = char;
        span.style.color = colors[i % colors.length];
        span.style.display = "inline-block";
        span.style.textShadow = "2px 2px 0 #000";
        element.appendChild(span);
    });
}

if (splashTextElement) {
    applySplashTextSizeByLength(splashTextElement, splashText);

    if (splashText === "COLORMATIC") {
        renderColormaticSplash(splashTextElement, splashText);
    } else {
        splashTextElement.textContent = splashText;
    }
}

const params = new URLSearchParams(window.location.search);

if (params.get("jambon") === "beurre") {
    snow(50, 50, 75, 15, 5);
}

if (params.get("purple") === "guy") {
    toggleEasterEgg();
}

const secret = ["3", "9", "5", "2", "4", "8"];
const admin = ["a", "d", "m", "i", "n"];

let buffer = [];

document.addEventListener("keydown", (e) => {
    buffer.push(e.key);

    // Keep only last N keys
    if (buffer.length > secret.length) {
        buffer.shift();
    }

    // Compare arrays
    if (
        JSON.stringify(buffer).toLowerCase() ===
        JSON.stringify(secret).toLowerCase()
    ) {
        toggleEasterEgg();
        buffer = []; // reset so it can be triggered again later
    }
    if (
        JSON.stringify(buffer).toLowerCase() ===
        JSON.stringify(admin).toLowerCase()
    ) {
        setupAdmin();
        buffer = []; // reset so it can be triggered again later
    }
});

const easterEggTrigger = document.getElementById("easter-egg-trigger");
if (easterEggTrigger) {
    easterEggTrigger.addEventListener("click", (event) => {
        event.preventDefault();
        toggleEasterEgg();
    });
}

function toggleEasterEgg() {
    const isActive =
        document.documentElement.style.getPropertyValue("--beurre-yellow") ===
        "#842593";
    if (isActive) {
        removeEasterEgg();
        //remove purple=guy from url without refreshing the page so that the easter egg can be shared with the link
        const url = new URL(window.location);
        url.searchParams.delete("purple");
        window.history.pushState({}, "", url);
    } else {
        activateEasterEgg();
        //add pruple=guy to url without refreshing the page so that the easter egg can be shared with the link
        const url = new URL(window.location);
        url.searchParams.set("purple", "guy");
        window.history.pushState({}, "", url);
    }
}

function activateEasterEgg() {
    //chqnge all root colors variables to new
    // actual ones :
    // :root {
    //     --beurre-yellow: #ffffcc;
    //     --beurre-light: #fff8a6;
    //     --beurre-light-secondary: #fbeda0;
    //     --beurre-dark: #c58d2b;
    //     --beurre-text: #2f1d00;
    //     --beurre-text-light: #b14c00;
    //     --beurre-text-secondary: #6d3900;
    //     --beurre-shadow: #f6d57b;
    //     --beurre-topbar-start: #ffef72;
    //     --beurre-topbar-end: #f6c745;
    // }

    //add 'animatiom: spinY 5s linear infinite;' to body, wait a forth the duration of the animation (2.5s) and then change all the clors and the rest of the easter egg, then remove the animation so it doesn't keep spinning forever
    document.body.style.animation = "spinY 5s linear infinite";

    setTimeout(() => {
        document.documentElement.style.setProperty(
            "--beurre-yellow",
            "#842593",
        ); //new one is purple
        document.documentElement.style.setProperty("--beurre-light", "#d9a1e8"); //new one is light purple
        document.documentElement.style.setProperty(
            "--beurre-light-secondary",
            "#f0c1f7",
        ); //new one is even lighter purple
        document.documentElement.style.setProperty("--beurre-mid", "#c080c0"); //new one is pinkish purple
        document.documentElement.style.setProperty("--beurre-dark", "#4b1460"); //new one is dark purple
        document.documentElement.style.setProperty("--beurre-text", "#ffffff"); //new one is white
        document.documentElement.style.setProperty(
            "--beurre-text-light",
            "#e0e0e0",
        ); //new one is light gray
        document.documentElement.style.setProperty(
            "--beurre-text-secondary",
            "#a0a0a0",
        ); //new one is gray
        document.documentElement.style.setProperty(
            "--beurre-shadow",
            "#c080c0",
        ); //new one is pinkish purple
        document.documentElement.style.setProperty(
            "--beurre-topbar-start",
            "#d9a1e8",
        ); //new one is light purple
        document.documentElement.style.setProperty(
            "--beurre-topbar-end",
            "#842593",
        ); //new one is purple

        document.querySelectorAll(".snowflake-image").forEach((img) => {
            img.src = "/assets/img/PurpleButter.png";
            img.style.width = Math.random() * (75 - 25) + 25 + "px"; // Size from 25px to 75px
            img.style.height = Math.random() * (75 - 25) + 25 + "px"; // Size from 25px to 75px
        });

        // change the title and favicon
        document.title = "Beurreland — Le royaume du Purple Guy";
        const favicon = document.querySelector("link[rel='shortcut icon']");
        if (favicon) {
            favicon.href = "/assets/img/Exotic_Butters.webp";
        }

        document.querySelectorAll("img").forEach((img) => {
            img.style.filter = "hue-rotate(270deg)"; // apply a hue rotation to all images to make them more purple
            img.style.transform = "rotateY(180deg)"; // flip all images horizontally to make them more purple
        });

        document.querySelectorAll(".snowflake-image").forEach((img) => {
            img.style.filter = "";
            img.style.transform = "";
        });

        document.querySelectorAll(".cube-face img").forEach((img) => {
            img.src = "/assets/img/PurpleGuyFace.webp";
            img.style.filter = "";
            img.style.transform = "none";
        });

        const style = document.createElement("style");
        style.innerHTML = `
          * { cursor: url('/assets/cur/purple-guy.cur'), auto !important; }
          a, button { cursor: url('/assets/cur/purple-guy.cur'), pointer !important; }
          input, textarea { cursor: url('/assets/cur/purple-guy.cur'), text !important; }
          .disabled { cursor: url('/assets/cur/purple-guy.cur'), not-allowed !important; }
          .loading { cursor: url('/assets/cur/purple-guy.cur'), wait !important; }
        `;
        document.head.appendChild(style);
    }, 3750); // wait 3/4 of the animation duration before changing colors and the rest of the easter egg

    setTimeout(() => {
        document.body.style.animation = "";
    }, 5000); // remove the animation after it has completed one full rotation (5s)
}

function removeEasterEgg() {
    document.body.style.animation = "spinY 5s linear infinite";
    setTimeout(() => {
        // reset colors to original
        document.documentElement.style.setProperty(
            "--beurre-yellow",
            "#ffffcc",
        );
        document.documentElement.style.setProperty("--beurre-light", "#fff8a6");
        document.documentElement.style.setProperty(
            "--beurre-light-secondary",
            "#fbeda0",
        );
        document.documentElement.style.setProperty("--beurre-mid", "#f5cf5f");
        document.documentElement.style.setProperty("--beurre-dark", "#c58d2b");
        document.documentElement.style.setProperty("--beurre-text", "#2f1d00");
        document.documentElement.style.setProperty(
            "--beurre-text-light",
            "#b14c00",
        );
        document.documentElement.style.setProperty(
            "--beurre-text-secondary",
            "#6d3900",
        );
        document.documentElement.style.setProperty(
            "--beurre-shadow",
            "#f6d57b",
        );
        document.documentElement.style.setProperty(
            "--beurre-topbar-start",
            "#ffef72",
        );
        document.documentElement.style.setProperty(
            "--beurre-topbar-end",
            "#f6c745",
        );

        document.querySelectorAll(".snowflake-image").forEach((img) => {
            img.src = "/assets/img/jambon-beurre.png";
            img.style.width = Math.random() * (75 - 25) + 25 + "px"; // Size from 25px to 75px
            img.style.height = "auto";
        });

        // change the title and favicon back to original
        document.title = "Beurreland — La religion sacrée du beurre";
        const favicon = document.querySelector("link[rel='shortcut icon']");
        if (favicon) {
            favicon.href = "/assets/img/Butter_Pixel.png";
        }

        document.querySelectorAll("img").forEach((img) => {
            img.style.filter = "none"; // remove hue rotation
            img.style.transform = "none"; // remove horizontal flip
        });

        document.querySelectorAll(".cube-face img").forEach((img) => {
            img.src = "/assets/img/Fresnik_Mobile.png";
            img.style.filter = "";
            img.style.transform = "none";
        });

        // change the body cursor back to the custom jambon-beurre cursor defined in the CSS file
        const style = document.createElement("style");
        style.innerHTML = `
          * { cursor: url('/assets/cur/jambon-beurre.cur'), auto !important; }
          a, button { cursor: url('/assets/cur/jambon-beurre.cur'), pointer !important; }
          input, textarea { cursor: url('/assets/cur/jambon-beurre.cur'), text !important; }
          .disabled { cursor: url('/assets/cur/jambon-beurre.cur'), not-allowed !important; }
          .loading { cursor: url('/assets/cur/jambon-beurre.cur'), wait !important; }
        `;
        document.head.appendChild(style);
    }, 1250); // wait 1/4 of the duration of the animation before changing colors

    setTimeout(() => {
        document.body.style.animation = "";
    }, 5000); // remove the animation after it has completed one full rotation (5s)
}

function googleTranslateElementInit() {
    new google.translate.TranslateElement(
        { pageLanguage: "fr" },
        "google_translate_element",
    );
}

function dateToFrenchLocale(dateString) {
    const date = new Date(dateString);

    const parts = new Intl.DateTimeFormat("fr-FR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        hour12: false,
        timeZone: "Europe/Paris", // optional
    }).formatToParts(date);

    const map = Object.fromEntries(
        parts.filter((p) => p.type !== "literal").map((p) => [p.type, p.value]),
    );

    let formattedDate = `le ${map.day}/${map.month}/${map.year} à ${map.hour}:${map.minute}`;
    return formattedDate;
}

function loadGuestbookLatestMessage() {
    const sidebarGuestbookPreview = document.getElementById(
        "guestbook-latest-message",
    );

    fetch("/api/v1/guestbook.php?id=latest&html=true")
        .then((response) => response.json())
        .then((data) => {
            if (!data || !data.message) {
                sidebarGuestbookPreview.textContent = "Aucun message trouvé.";
                return;
            }

            const message = data.message;
            const author = data.name;
            const date = dateToFrenchLocale(data.created_at);

            sidebarGuestbookPreview.innerHTML = `
                <span class="name">${author} </span> <span class="date">(${date})</span><br>
                <div class="content">${message}</div>
            `;

            loadWaveAnimation(); // Reapply wave animation to new messages
        })
        .catch((error) => {
            console.error(
                "Erreur lors de la récupération du dernier message du livre d'or :",
                error,
            );
            sidebarGuestbookPreview.textContent =
                "Impossible de récupérer le dernier message.";
        });
}

loadGuestbookLatestMessage();
setInterval(loadGuestbookLatestMessage, 30000); // Refresh every 30 seconds

function reloadGuestbook() {
    const guestbookContainer = document.getElementById("guestbook-messages");

    fetch("/api/v1/guestbook.php?html=true")
        .then((response) => response.json())
        .then((data) => {
            console.log("Messages du livre d'or récupérés :", data);
            if (!data) {
                guestbookContainer.innerHTML = "<p>Aucun message trouvé.</p>";
                return;
            }

            let messages = "";
            data.forEach((message) => {
                const author = message.name;
                const content = message.message;

                const formattedDate = dateToFrenchLocale(message.created_at);

                const messageElement = document.createElement("div");
                messageElement.classList.add("message");
                messageElement.innerHTML = `
                <button class="admin-only" onclick="deleteMessage(${message.id})">Supprimer id: ${message.id}</button>
                <span class="name">${author}</span>
                <span class="date">(${formattedDate})</span>
                </br>
                <div class="content">${content}</div>
            `;
                messages += messageElement.outerHTML;
            });
            guestbookContainer.innerHTML = messages;

            loadWaveAnimation(); // Reapply wave animation to new messages
        })
        .catch((error) => {
            console.error(
                "Erreur lors de la récupération des messages du livre d'or :",
                error,
            );
            guestbookContainer.innerHTML =
                "<p>Impossible de récupérer les messages.</p>";
        });
}

const reloadButton = document.getElementById("reload");
if (reloadButton) {
    reloadButton.addEventListener("click", reloadGuestbook);
}

function randomAds() {
    console.log("Selecting a random ad to display...");
    const ads = [
        {
            image: "/assets/img/ACHETE Beurre Frescri Gastronomique Demi-sel.png",
            alt: "Publicité pour le Beurre Gastronomique de Frescri",
        },
        {
            image: "/assets/img/beurre.jpg",
            alt: "beurre.jpg",
        },
        {
            image: "/assets/img/FRESCRI C'est bon le beurre Pub.png",
            alt: "Publicité pour le Beurre Deluxe de Frescri",
        },
        {
            image: "/assets/img/davide-jambon-beuere.gif",
            alt: "Désolé, le dieu du beurre a mangé la publicité",
        },
        {
            image: "/assets/img/jambon-beurre.png",
            alt: "jambon-beurre.png",
        }
    ];

    const randomAd = ads[Math.floor(Math.random() * ads.length)];

    html = `
        <img src="${randomAd.image}" alt="${randomAd.alt}">
        <figcaption>${randomAd.alt}</figcaption>
    `;

    const ad = document.getElementById("ad");
    if (ad) {
        ad.innerHTML = html;

        console.log(`Ad displayed: ${randomAd.alt}`);
    }
}

randomAds();

/* Admin setup */

function setupAdmin() {
    const password = prompt(
        "Entrez le mot de passe pour accéder aux fonctionnalités d'administration:",
    );

    if (!password) return;

    sessionStorage.setItem("adminPassword", password);

    // change the css for admin-only elements to make them visible
    const style = document.createElement("style");
    style.innerHTML = `
        .admin-only { display: inline-block !important; }
    `;
    document.head.appendChild(style);

    alert("Mode administrateur activé !");
}

function deleteMessage(id) {
    if (!confirm("Êtes-vous sûr de vouloir supprimer ce message ?")) return;

    const adminPassword = sessionStorage.getItem("adminPassword");

    fetch(`/api/v1/guestbook.php?id=${id}`, {
        method: "DELETE",
        headers: {
            "X-Auth-Token": adminPassword,
        },
    })
        .then((response) => {
            if (response.ok) {
                alert("Message supprimé avec succès.");
                reloadGuestbook();
            } else {
                alert("Échec de la suppression du message.");
            }
        })
        .catch((error) => {
            console.error("Erreur lors de la suppression du message :", error);
            alert("Une erreur est survenue lors de la suppression du message.");
        });
}


const showHidenLink = document.querySelector(".hiden-link");
if (showHidenLink) {
    showHidenLink.addEventListener("click", () => {
        fetch("/api/v1/tor.php")
            .then((response) => response.text())
            .then((data) => {
                const hostname = JSON.parse(data).hostname;
                if (hostname) {
                    // response example: {"hostname":"exampleonionaddress.onion"}
                    // create a custom modal to display the onion address with a copy button
                    const modal = document.createElement("div");
                    modal.classList.add("modal");

                    const content = document.createElement("div");
                    content.classList.add("content");

                    const address = document.createElement("p");
                    address.textContent = `Adresse Tor : ${hostname}`;
                    content.appendChild(address);

                    const copyButton = document.createElement("button");
                    copyButton.classList.add("copy-btn");
                    copyButton.textContent = "Copier l'adresse";
                    copyButton.addEventListener("click", () => {
                        navigator.clipboard.writeText(hostname).then(
                            () => {
                                alert("Adresse copiée dans le presse-papiers !");
                            },
                            (err) => {
                                console.error("Erreur lors de la copie de l'adresse :", err);
                                alert("Échec de la copie de l'adresse.");
                            },
                        );
                    });
                    content.appendChild(copyButton);

                    const closeButton = document.createElement("button");
                    closeButton.classList.add("close-btn");
                    closeButton.textContent = "Fermer";
                    closeButton.style.marginLeft = "10px";
                    closeButton.addEventListener("click", () => {
                        document.body.removeChild(modal);
                    });
                    content.appendChild(closeButton);

                    modal.appendChild(content);

                    document.body.appendChild(modal);
                }
            })
            .catch((error) => {
                console.error("Erreur lors de la récupération de l'adresse Tor :", error);
            });
    });
}

const copyButton = document.getElementById("copyButton");
if (copyButton) {
    copyButton.addEventListener("click", () => {
        const copyInput = document.getElementById("copyInput");
        if (copyInput) {
            navigator.clipboard.writeText(copyInput.value).then(
                () => {
                    alert("88x31.png copié dans le presse-papiers !");
                },
                (err) => {
                    console.error("Erreur lors de la copie du code de parrainage :", err);
                    alert("Échec de la copie du code de parrainage.");
                },
            );
        }
    });
}