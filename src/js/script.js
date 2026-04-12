function updateCounter(counterValue, digitElements) {
    const str = counterValue.toString().padStart(6, "0");
    str.split("").forEach((num, i) => {
        console.log(`Updating digit ${i} to ${num}`);
        digitElements[i].textContent = num;
    });
}

const digits = document.querySelectorAll(".digit");

fetch("/counter.php?increment=1")
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

document.querySelectorAll(".wave-auto").forEach((link) => {
    const text = link.textContent;
    link.textContent = "";
    [...text].forEach((char, i) => {
        const span = document.createElement("span");
        span.classList.add("wave");
        span.textContent = char === " " ? "\u00A0" : char;
        span.style.animationDelay = `${i * 0.05}s`;
        link.appendChild(span);
    });
});


const splashTexts = [
    "Beurre!!!",
    "Gloire au Beurre!!!",
    "Vive le Jambon-Beurre!!!",
    "Butter is love, butter is life!!!",
    "In Butter We Trust!!!",
    "Best viewed on desktop!!!",
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
    const minSizeRem = 0.85;
    const maxSizeRem = 2;
    const shortestText = 8;
    const longestText = 36;
    const length = text.trim().length;
    const ratio = (length - shortestText) / (longestText - shortestText);
    const clampedRatio = Math.min(1, Math.max(0, ratio));
    const fontSizeRem = maxSizeRem - clampedRatio * (maxSizeRem - minSizeRem);

    element.style.fontSize = `${fontSizeRem.toFixed(2)}rem`;
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
});

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
            img.src = "/src/img/PurpleGuy.webp";
            img.style.width = Math.random() * (75 - 25) + 25 + "px"; // Size from 25px to 75px
            img.style.height = Math.random() * (75 - 25) + 25 + "px"; // Size from 25px to 75px
        });

        // change the title and favicon
        document.title = "Beurreland — Le royaume du Purple Guy";
        const favicon = document.querySelector("link[rel='shortcut icon']");
        if (favicon) {
            favicon.href = "/src/img/Exotic_Butters.webp";
        }

        document.querySelectorAll("img").forEach((img) => {
            img.style.filter = "hue-rotate(270deg)"; // apply a hue rotation to all images to make them more purple
            img.style.transform = "rotateY(180deg)"; // flip all images horizontally to make them more purple
        });

        document.querySelectorAll(".cube-face img").forEach((img) => {
            img.src = "/src/img/PurpleGuyFace.webp";
            img.style.filter = "";
            img.style.transform = "none";
        });
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
            img.src = "/src/img/jambon-beurre.png";
            img.style.width = Math.random() * (75 - 25) + 25 + "px"; // Size from 25px to 75px
            img.style.height = "auto";
        });

        // change the title and favicon back to original
        document.title = "Beurreland — La religion sacrée du beurre";
        const favicon = document.querySelector("link[rel='shortcut icon']");
        if (favicon) {
            favicon.href = "/src/img/Butter_Pixel.png";
        }

        document.querySelectorAll("img").forEach((img) => {
            img.style.filter = "none"; // remove hue rotation
            img.style.transform = "none"; // remove horizontal flip
        });

        document.querySelectorAll(".cube-face img").forEach((img) => {
            img.src = "/src/img/Fresnik_Mobile.png";
            img.style.filter = "";
            img.style.transform = "none";
        });
    }, 1250); // wait 1/4 of the duration of the animation before changing colors

    setTimeout(() => {
        document.body.style.animation = "";
    }, 5000); // remove the animation after it has completed one full rotation (5s)
}

function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'fr'}, 'google_translate_element');
}