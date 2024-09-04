

// Get references to DOM elements
const startButton = document.getElementById('start-btn');
const statusMessage = document.getElementById('status-message');

// Access the player name from the URL parameters
const playerName = new URLSearchParams(window.location.search).get('name');

// Define an array for the colors on the game board
const colors = ["red", "green", "blue", "yellow"];

// Define variables for game state
const gameSequence = []; // Array to store the sequence of the game
const playerSequence = []; // Array to store the player's sequence
let round = 0; // Variable to keep track of the current round
let isSimonTurn = false; // Boolean to determine if it's Simon's turn

// Get references to the colored buttons
const gameButtons = document.querySelectorAll('.game-btn');
const scoreMessage = document.getElementById('score-message');
const highScoreMessage = document.getElementById('high-score');
let highScore = 0;

// Function to play the Simon sequence
function playSimonSequence() {
    isSimonTurn = true; // Set Simon's turn to true
    statusMessage.textContent = "Simon's Turn"; // Update status message

    // Add a new random color to the gameSequence
    const randomColor = colors[getRandomNumber(0, 3)];
    gameSequence.push(randomColor);

    // Start animating the sequence from index 0
    animateSequence(0);
}

// Function to animate the sequence of colors
function animateSequence(index) {
    if (index < gameSequence.length) {
        setTimeout(() => {
            const button = document.getElementById(`button-${colors.indexOf(gameSequence[index]) + 1}`);
            button.style.opacity = '0.5';

            // Optionally add a delay to reset the opacity
            setTimeout(() => {
                button.style.opacity = '1';
                animateSequence(index + 1); // Move to the next color in the sequence
            }, 500); // Adjust the delay as needed
        }, 1000); // 1000 ms delay before starting the animation
    } else {
        // Once the sequence animation is complete, switch to the player's turn
        isSimonTurn = false;
        statusMessage.textContent = `${playerName}'s Turn`; // Update status message to player's turn
        playerSequence = []; // Reset playerSequence array
    }
}

// Event listener for the start button
startButton.addEventListener('click', () => {
    startButton.disabled = true; // Disable the start button
    playSimonSequence(); // Start the game loop
});

// Function to generate a random number between min and max (inclusive)
function getRandomNumber(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

// Function to handle button clicks
function handleButtonClick(colourIndex) {
    if (!isSimonTurn) { // Check if it's the player's turn
        const colour = colors[colourIndex]; // Get the color based on index
        playerSequence.push(colour); // Add the clicked color to the player's sequence

        // Get the button element based on the colourIndex
        const button = document.getElementById(`btn${colourIndex + 1}`);
        button.style.opacity = '0.5'; // Dim the button

        // Reset opacity after 300ms
        setTimeout(() => {
            button.style.opacity = '1'; // Reset opacity
        }, 300); // 300 ms delay before resetting opacity

        // Add logic here to check if playerSequence matches gameSequence and handle game state
    }
}

// Function to check if the player's sequence matches the game sequence
function checkPlayerSequence() {
    for (let i = 0; i < playerSequence.length; i++) {
        if (playerSequence[i] !== gameSequence[i]) {
            gameOver(); // Call the gameOver function instead of directly setting the statusMessage
            return; // Exit the function if there is a mismatch
        }
    }

    // If playerSequence matches gameSequence up to the current length
    if (playerSequence.length === gameSequence.length) {
        // Delay for 500ms to show "Correct!" message
        setTimeout(() => {
            round++; // Increment the round value
            statusMessage.textContent = "Correct!"; // Update status message

            // After 1000ms, start a new round
            setTimeout(() => {
                playSimonSequence(); // Start a new round with an updated game sequence
            }, 1000); // 1000 ms delay before starting the next round
        }, 500); // 500 ms delay before showing "Correct!" message
    }
}

// Event listener for the start button
startButton.addEventListener('click', () => {
    startButton.disabled = true; // Disable the start button
    playSimonSequence(); // Start the game loop
});

// Set up event listeners for each game button
gameButtons.forEach((button, index) => {
    button.addEventListener('click', () => {
        handleButtonClick(index); // Handle button click with the index
    });
});

function gameOver() {
    // Set the status message to "Game Over!"
    statusMessage.textContent = "Game Over!";

    // Update the scoreMessage with the current round value
    scoreMessage.textContent = `Previous Score: ${round}`;

    // Check and update high score
    if (round > highScore) {
        highScore = round; // Update high score
    }

    // Update the highScoreMessage with the current high score
    highScoreMessage.textContent = `High Score: ${highScore}`;

    // Reset the round value
    round = 0;

    // Reset the game sequence
    gameSequence = [];

    // Re-enable the start button
    startButton.disabled = false;
}