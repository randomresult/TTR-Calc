<?php
// templates/player_form.php
?>

<h1 class="mb-4">TTR-Punktberechnung</h1>
<?php if (isset($error) && $error !== '') : ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="post">
    <div class="form-group">
        <label for="playerId">Spieler auswählen:</label>
        <select class="form-control" name="playerId" id="playerId" onchange="this.form.submit(); togglePlayerFields();">
            <option value="">-- Wähle einen Spieler --</option>
            <?php
            foreach ($players as $player) {
                $selected = ($player->id === $selectedPlayerId) ? 'selected' : '';
                echo "<option value='{$player->id}' $selected>{$player->name}</option>";
            }
            ?>
            <option value="new" <?= $selectedPlayerId === 'new' ? 'selected' : '' ?>>+ Neuen Spieler erstellen</option>
        </select>
    </div>

    <div id="newPlayerFields" style="display: <?= $selectedPlayerId === 'new' ? 'block' : 'none' ?>;">
        <div class="form-group">
            <label>Spielername:</label>
            <input type="text" class="form-control" name="playerName" value="">
        </div>
        <div class="form-group">
            <label>Geburtsjahr:</label>
            <input type="number" class="form-control" name="birthYear" value="">
        </div>
        <div class="form-group">
            <label>Aktueller TTR-Wert:</label>
            <input type="number" class="form-control" name="ttr" value="" step="0.01">
        </div>
        <button type="submit" name="action" value="updatePlayer" class="btn btn-primary">Spieler speichern</button>
    </div>

    <?php if ($selectedPlayer && $selectedPlayerId !== 'new') : ?>
        <h2 class="mt-4">Spielerinformationen:</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($selectedPlayer->name) ?></p>
        <div class="form-group">
            <label>Geburtsjahr:</label>
            <input type="number" class="form-control" name="birthYear" value="<?= $selectedPlayer->birthYear ?>">
        </div>
        <div class="form-group">
            <label>Aktueller TTR-Wert:</label>
            <input type="number" class="form-control" name="ttr" value="<?= $selectedPlayer->ttr ?>" step="0.01">
        </div>
        <button type="submit" name="action" value="updatePlayer" class="btn btn-primary">Spieler aktualisieren</button>

        <?php if (!empty($selectedPlayer->matches)) : ?>
            <h2 class="mt-4">Spielhistorie:</h2>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Datum</th>
                    <th>Gegner Name</th>
                    <th>Gegner TTR</th>
                    <th>Ergebnis</th>
                    <th>Punkteänderung</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach (array_reverse($selectedPlayer->matches) as $match) : ?>
                    <tr>
                        <td><?= $match['date'] ?></td>
                        <td><?= htmlspecialchars($match['opponentName']) ?></td>
                        <td><?= $match['opponentTTR'] ?></td>
                        <td><?= $match['result'] === 'win' ? 'Sieg' : 'Niederlage' ?></td>
                        <td><?= $match['pointsChange'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</form>
