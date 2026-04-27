const api = "https://beurreland.cc/api/v1/gifs.php"; // Replace with your actual API endpoint, uses klipy API

let currentPage = 1;
let currentQuery = "";

let firstLoad = true;

function getGifColumns(resultsDiv) {
    let col1 = resultsDiv.querySelector(".gif-column-left");
    let col2 = resultsDiv.querySelector(".gif-column-right");

    if (!col1 || !col2) {
        resultsDiv.innerHTML = "";
        col1 = document.createElement("div");
        col1.classList.add("gif-column-left");
        col2 = document.createElement("div");
        col2.classList.add("gif-column-right");
        resultsDiv.appendChild(col1);
        resultsDiv.appendChild(col2);
    }

    return { col1, col2 };
}

function appendGifToBalancedColumns(resultsDiv, img) {
    const { col1, col2 } = getGifColumns(resultsDiv);
    const col1Height = col1.scrollHeight;
    const col2Height = col2.scrollHeight;

    if (col1Height <= col2Height) {
        col1.appendChild(img);
    } else {
        col2.appendChild(img);
    }
}

function createGifImage(gif) {
    const imgUrl =
        gif?.file?.sm?.jpg?.url ||
        gif?.file?.md?.jpg?.url ||
        gif?.file?.xs?.jpg?.url;
    const gifUrl =
        gif?.file?.sm?.gif?.url ||
        gif?.file?.md?.gif?.url ||
        gif?.file?.xs?.gif?.url;

    if (!gifUrl) {
        return null;
    }

    const img = document.createElement("img");
    img.src = imgUrl || gifUrl;
    img.alt = gif?.title || "GIF result";
    img.loading = "lazy";
    img.addEventListener("mouseover", () => {
        img.src = gifUrl;
    });
    img.addEventListener("mouseout", () => {
        img.src = imgUrl || gifUrl;
    });
    img.addEventListener("click", () => {
        addToTextarea(gifUrl, img.alt);
    });

    return img;
}

function toggleGifPicker() {
    const picker = document.getElementById("gifPicker");
    if (picker.style.display === "block") {
        picker.style.display = "none";
    } else {
        picker.style.display = "block";
    }
}

function goOnTop() {
    const picker = document.getElementById("gifPicker");
    picker.scrollTop = 0;
}

function addGifsToResults(gifs) {
    const resultsDiv = document.getElementById("results");
    gifs = gifs?.data?.data || gifs?.data || gifs || [];
    if (!resultsDiv) return;

    gifs.forEach((gif) => {
        const img = createGifImage(gif);
        if (img) {
            appendGifToBalancedColumns(resultsDiv, img);
        }
    });
}

function showGifs(gifs) {
    // show gif results in 2 columns, alternating images between the 2 columns, with the static image shown by default and the animated GIF shown on hover
    const resultsDiv = document.getElementById("results");

    gifs = gifs?.data?.data || gifs?.data || gifs || [];

    resultsDiv.innerHTML = "";
    gifs.forEach((gif) => {
        const img = createGifImage(gif);
        if (img) {
            appendGifToBalancedColumns(resultsDiv, img);
        }
    });

    goOnTop();
}

async function loadTrending(page = 1) {
    const status = document.getElementById("status");

    status.textContent = "Loading...";

    try {
        const response = await fetch(`${api}?type=trending&page=${page}&beurre=${currentPage}`);
        const data = await response.json();

        console.log("API response:", data);

        if (!response.ok || data.error) {
            throw new Error(
                data.error || `API returned HTTP ${response.status}`,
            );
        }

        return data.data;
    } catch (error) {
        status.textContent = `Error fetching data: ${error.message}`;
        console.error("Error fetching data:", error);
    }
}

async function searchGifs(query, page = 1) {
    const status = document.getElementById("status");

    status.textContent = "Searching...";

    try {
        const response = await fetch(
            `${api}?type=search&query=${encodeURIComponent(query)}&page=${page}&beurre=${currentPage}`,
        );
        const data = await response.json();

        console.log("API response:", data);

        if (!response.ok || data.error) {
            throw new Error(
                data.error || `API returned HTTP ${response.status}`,
            );
        }

        // return (
        //     (Array.isArray(data?.data?.data) && data.data.data) ||
        //     (Array.isArray(data?.data) && data.data) ||
        //     (Array.isArray(data) && data) || []
        // )

        return data.data;
    } catch (error) {
        status.textContent = `Error fetching data: ${error.message}`;
        console.error("Error fetching data:", error);
    }
}

function addToTextarea(content, alt = "") {
    const textarea = document.getElementById("message");
    textarea.value += "[img=" + alt + "]" + content + "[/img]";
    toggleGifPicker();
}

document.getElementById("bb-gif").addEventListener("click", () => {
    toggleGifPicker();
    goOnTop();

    if (firstLoad) {
        firstLoad = false;
        (async () => {
            const gifs = await loadTrending(); // Load trending GIFs on first open
            showGifs(gifs);
            console.log("Initial trending GIFs:", gifs);
        })();
    }
});

// Search when the button is clicked or when the user presses Enter in the input field
document
    .getElementById("searchButton")
    .addEventListener("click", async (event) => {
        event.preventDefault();
        const query = document.getElementById("searchInput").value.trim();
        if (query) {
            showGifs(await searchGifs(query));
        } else {
            showGifs(await loadTrending());
        }
    });

document
    .getElementById("searchInput")
    .addEventListener("keypress", async (event) => {
        if (event.key === "Enter") {
            event.preventDefault();
            const query = event.target.value.trim();
            if (query) {
                (async () => {
                    currentPage = 1; // Reset to first page on new search
                    const gifs = await searchGifs(query, currentPage);
                    showGifs(gifs);
                })();
            } else {
                (async () => {
                    currentPage = 1; // Reset to first page on new search
                    const gifs = await loadTrending(currentPage);
                    showGifs(gifs);
                })();
            }
        }
    });

// Load more GIFs when scrolling to the bottom of the results
document
    .getElementById("gifPicker")
    .addEventListener("scroll", async (event) => {
        const { scrollTop, scrollHeight, clientHeight } = event.target;
        if (scrollTop + clientHeight >= scrollHeight - 10) {
            // Near the bottom
            const query = document.getElementById("searchInput").value.trim();
            if (query) {
                (async () => {
                    currentPage++;
                    const newGifs = await searchGifs(query, currentPage);
                    addGifsToResults(newGifs);
                })();
            } else {
                (async () => {
                    currentPage++;
                    const newGifs = await loadTrending(currentPage);
                    addGifsToResults(newGifs);
                })();
            }
        }
    });

    // Close the GIF picker when clicking outside of it
    document.addEventListener("click", (event) => {
        const picker = document.getElementById("gifPicker");
        const button = document.getElementById("bb-gif");
        if (picker.style.display === "block" && !picker.contains(event.target) && event.target !== button) {
            picker.style.display = "none";
        }
    });