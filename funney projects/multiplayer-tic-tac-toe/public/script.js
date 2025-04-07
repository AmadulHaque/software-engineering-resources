const ws = new WebSocket('ws://localhost:8080');
const board = document.getElementById('board');
const status = document.getElementById('status');
const resetButton = document.getElementById('reset');

let player = '';
let gameBoard = Array(9).fill(null);
let currentPlayer = 'X';
let gameOver = false;

// Handle WebSocket messages
ws.onmessage = (event) => {
    const rawData = event.data;
    console.log('Received raw data:', rawData);
    
    // Basic check if the message looks like JSON
    if (rawData.startsWith('{') || rawData.startsWith('[')) {
      try {
        const data = JSON.parse(rawData); // Try parsing the JSON
        console.log('Parsed data:', data);
  
        if (data.message) {
          status.textContent = data.message;
        }
        if (data.player) {
          player = data.player;
        }
        if (data.board) {
          updateBoard(data.board);
        }
      } catch (error) {
        console.error('Error parsing message:', error);
      }
    } else {
      console.error('Received non-JSON message:', rawData);
    }
};
  
// Handle cell clicks
board.addEventListener('click', (e) => {
  if (gameOver) return;

  const index = e.target.dataset.index;
  if (gameBoard[index] || player === '') return;

  gameBoard[index] = player;
  ws.send(JSON.stringify({ move: index, player }));

  currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
  updateBoard(gameBoard);
});

// Update the game board UI
function updateBoard(boardState) {
  const cells = document.querySelectorAll('.cell');
  boardState.forEach((value, index) => {
    cells[index].textContent = value;
  });

  checkGameStatus(boardState);
}

// Check if someone has won
function checkGameStatus(boardState) {
  const winPatterns = [
    [0, 1, 2],
    [3, 4, 5],
    [6, 7, 8],
    [0, 3, 6],
    [1, 4, 7],
    [2, 5, 8],
    [0, 4, 8],
    [2, 4, 6]
  ];

  for (let pattern of winPatterns) {
    const [a, b, c] = pattern;
    if (boardState[a] && boardState[a] === boardState[b] && boardState[a] === boardState[c]) {
      gameOver = true;
      status.textContent = `${boardState[a]} wins!`;
      return;
    }
  }

  if (!boardState.includes(null)) {
    gameOver = true;
    status.textContent = 'It\'s a draw!';
  }
}

// Reset game
resetButton.addEventListener('click', () => {
  gameBoard = Array(9).fill(null);
  gameOver = false;
  updateBoard(gameBoard);
  status.textContent = '';
  resetButton.style.display = 'none';
});
