// public/js/script.js

function updateGameLabels() {
    const games = document.querySelectorAll('.game');
    games.forEach((gameDiv, index) => {
        gameDiv.id = 'game-' + index;
        const labels = gameDiv.querySelectorAll('label');
        labels[0].textContent = `Spiel ${index + 1} - Gegner Name:`;
        labels[1].textContent = `Gegner TTR:`;
        const opponentNameInput = gameDiv.querySelector('input[name^="games"][name$="[opponentName]"]');
        opponentNameInput.name = `games[${index}][opponentName]`;
        const opponentTTRInput = gameDiv.querySelector('input[name^="games"][name$="[opponentTTR]"]');
        opponentTTRInput.name = `games[${index}][opponentTTR]`;
        const resultSelect = gameDiv.querySelector('select[name^="games"]');
        resultSelect.name = `games[${index}][result]`;
        const removeButton = gameDiv.querySelector('button.remove-game');
        removeButton.setAttribute('onclick', `removeGame(${index})`);
    });
}

function addGame() {
    const gamesDiv = document.getElementById('games');
    const index = gamesDiv.children.length;
    const gameDiv = document.createElement('div');
    gameDiv.className = 'form-row align-items-end mb-2 game';
    gameDiv.id = 'game-' + index;

    gameDiv.innerHTML = `
        <div class="col-md-3">
            <label>Spiel ${index + 1} - Gegner Name:</label>
            <input type="text" class="form-control" name="games[${index}][opponentName]" required>
        </div>
        <div class="col-md-2">
            <label>Gegner TTR:</label>
            <input type="number" class="form-control" name="games[${index}][opponentTTR]" step="0.01" required>
        </div>
        <div class="col-md-2">
            <label>Ergebnis:</label>
            <select class="form-control" name="games[${index}][result]" required>
                <option value="win">Sieg</option>
                <option value="loss">Niederlage</option>
            </select>
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger remove-game" onclick="removeGame(${index})">-</button>
        </div>
    `;
    gamesDiv.appendChild(gameDiv);
}

function removeGame(index) {
    const gameDiv = document.getElementById('game-' + index);
    if (gameDiv) {
        gameDiv.remove();
        updateGameLabels();
    }
}

function resetForm() {
    if (confirm('Möchtest du das Formular wirklich zurücksetzen? Alle eingegebenen Daten gehen verloren.')) {
        window.location.href = window.location.pathname;
    }
}

function togglePlayerFields() {
    const playerSelect = document.getElementById('playerId');
    const newPlayerFields = document.getElementById('newPlayerFields');
    if (playerSelect.value === 'new') {
        newPlayerFields.style.display = 'block';
    } else {
        newPlayerFields.style.display = 'none';
    }
}
