                    <button type="button" class="bbcode-btn" id="bb-gif">GIF</button>

                    <div class="gif-picker-container">
                        <div class="gif-picker" id="gifPicker">
                            <div class="search-bar">
                                <input type="text" id="searchInput" placeholder="Search for GIFs...">
                                <button type="button" id="searchButton">Search</button>
                            </div>
                            <div class="gif-results" id="results">
                                <!-- Display GIF results in 2 columns, with the static image shown by default and the animated GIF shown on hover -->
                            </div>
                            <p class="gif-status" id="status"></p>
                        </div>
                    </div>
                    <textarea name="message" id="message"></textarea>
                    <link rel="stylesheet" href="/assets/css/gif_picker.css">
                    <style>
                        .gif-picker {
                            position: absolute;
                            top: 100%;
                            left: 0;
                        }
                    </style>
                    <script src="/assets/js/gif_picker.js"></script>