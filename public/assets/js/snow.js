function snow(ammount, minSize=10, maxSize=25, minSpeed=15, maxSpeed=5) { // ammount, minSize, maxSize, minSpeed, maxSpeed
  let snowContainer = document.getElementById('snow');
  if (!snowContainer) {
    snowContainer = document.createElement('div');
    snowContainer.id = 'snow';
    document.body.appendChild(snowContainer); // Add snow to body without rebuilding existing DOM
  }

  const flakes = ["<img class='snowflake-image' src='/assets/img/jambon-beurre.png' style='width: 200px; height: 200px;' />"] // Snowflakes
  for (let i = 0; i < ammount; i++) { // Create snowflakes
      const car = flakes[Math.floor(Math.random() * flakes.length)]; // Random snowflake
      const flake = document.createElement('div');
      flake.className = 'snowflake';
      flake.innerHTML = car;
      snowContainer.appendChild(flake); // Add snowflake to snow
  }
  const snowflakes = document.querySelectorAll(".snowflake"); // Select all snowflakes
  const snowflakesImage = document.querySelectorAll(".snowflake-image"); // Select all snowflakes images
  snowflakes.forEach((flake) => { // For each snowflake
    const driftDuration = Math.random() * (maxSpeed - minSpeed) + minSpeed; // Time from 5s to 15s
    const driftDelay = Math.random() * 5; // Wait time from 0s to 5s
    const startingX = Math.random() * window.innerWidth; // Ramdom horizontal position
    const size = Math.random() * (maxSize - minSize) + minSize; // Size from 5px to 15px
    const color = parseInt(255-Math.random()*50).toString(16); // Random color from 0xffff00 to 0xffffff
    const speed = parseInt(Math.random() * 3); // Speed from 1 to 4 
    const zIndex = parseInt(-1 + Math.random() * 2147483648); // Random zIndex from -1 to 2147483647

    flake.style.position = 'fixed'; // Fixed position
    flake.style.top = '-10px'; // Start from top
    flake.style.userSelect = 'none'; // Disable selection
    flake.style.cursor = 'default'; // Default cursor
    flake.style.pointerEvents = 'none'; // Disable pointer events
    
    flake.style.color = `#${color}${color}ff`; // Set color (blueish)
    flake.style.left = `${startingX}px`; // Set horizontal position
    flake.style.fontSize = `${size}px`; // Set size

    flake.style.zIndex = zIndex; // Set zIndex (for overlapping and underlapping)
    flake.style.animation = `snowfall-speed${speed} ${driftDuration}s linear infinite`; // Set animation speed and duration
    flake.style.animationDelay = `-${driftDelay}s`; // Set animation delay (for random start)
  });

  snowflakesImage.forEach((img) => { // For each snowflake image
    const size = Math.random() * (maxSize - minSize) + minSize; // Size from 5px to 15px
    img.style.userSelect = 'none'; // Disable selection
    img.style.cursor = 'default'; // Default cursor
    img.style.pointerEvents = 'none'; // Disable pointer events
    img.style.height = `${size}px`; // Set height
    img.style.width = `auto`; // Set width
  });
}