<?php
// templates/match_form.php
?>

<hr>
<form method="post">
    <input type="hidden" name="action" value="calculate">
    <input type="hidden" name="playerId" value="<?= $selectedPlayerId ?>">
    <input type="hidden" name="birthYear" value="<?= $selectedPlayer->birthYear ?>">
    <input type="hidden" name="ttr" value="<?= $selectedPlayer->ttr ?>">

    <h2 class="mt-4">K-Faktor-Optionen:</h2>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="lessThan30Matches" id="lessThan30Matches">
        <label class="form-check-label" for="lessThan30Matches">Weniger als 30 gewertete Einzel</label>
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="inactive" id="inactive">
        <label class="form-check-label" for="inactive">Inaktiv (keine gewertete Veranstaltung in den letzten 365 Tagen)</label>
    </div>

    <div id="games" class="mt-4">
        <h2>Spieleingaben:</h2>
        <div class="form-row align-items-end mb-2 game" id="game-0">
            <div class="col-md-3">
                <label>Spiel 1 - Gegner Name:</label>
                <input type="text" class="form-control" name="games[0][opponentName]" required>
            </div>
            <div class="col-md-2">
                <label>Gegner TTR:</label>
                <input type="number" class="form-control" name="games[0][opponentTTR]" step="0.01" required>
            </div>
            <div class="col-md-2">
                <label>Ergebnis:</label>
                <select class="form-control" name="games[0][result]" required>
                    <option value="win">Sieg</option>
                    <option value="loss">Niederlage</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger remove-game" onclick="removeGame(0)">-</button>
            </div>
        </div>
    </div>
    <button type="button" onclick="addGame()" class="btn btn-secondary mb-3">Spiel hinzufügen</button><br>

    <div class="buttons">
        <button type="submit" class="btn btn-success">Berechnen und speichern</button>
        <button type="button" onclick="resetForm()" class="btn btn-warning">Reset</button>
    </div>
</form>

<?php
if (isset($output) && $output !== '') {
    echo "<hr><h2>Ergebnisse:</h2>";
    echo "<div class='mt-3'>$output</div>";
}
?>
