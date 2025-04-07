const express = require('express');
const http = require('http');
const WebSocket = require('ws');

const app = express();
const server = http.createServer(app);
const wss = new WebSocket.Server({ server });

let waitingPlayer = null; // Store the player who is waiting for an opponent

wss.on('connection', (ws) => {
  // Handle new player connections
  if (!waitingPlayer) {
    waitingPlayer = ws; // The first player waits for an opponent
    ws.send(JSON.stringify({ message: 'Waiting for opponent...' }));
  } else {
    const opponent = waitingPlayer;
    waitingPlayer = null; // Once the second player connects, start the game

    // Notify both players that the game is starting
    ws.send(JSON.stringify({ message: 'Game starting! You are Player X', player: 'X' }));
    opponent.send(JSON.stringify({ message: 'Game starting! You are Player O', player: 'O' }));

    // Broadcast the game state to both players
    [ws, opponent].forEach((player) => {
      player.on('message', (message) => {
        // Broadcast moves to both players
        [ws, opponent].forEach((p) => {
          if (p !== player) p.send(message);
        });
      });

      player.on('close', () => {
        // Handle player disconnection
        opponent.send(JSON.stringify({ message: 'Your opponent has disconnected. You win!' }));
      });
    });
  }
});

// Serve static files (frontend)
app.use(express.static('public'));

// Start the server
server.listen(8080, () => {
  console.log('Server is running on http://localhost:8080');
});
