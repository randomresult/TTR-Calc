<?php
// public/index.php

require_once __DIR__ . '/../classes/Player.php';
require_once __DIR__ . '/../classes/PlayerManager.php';
require_once __DIR__ . '/../classes/TTRCalculator.php';

use App\Classes\Player;
use App\Classes\PlayerManager;
use App\Classes\TTRCalculator;

$playerManager = new PlayerManager();
$players = $playerManager->getPlayers();

// Initialisiere Variablen
$selectedPlayerId = isset($_POST['playerId']) ? $_POST['playerId'] : '';
$selectedPlayer = null;
$error = '';
$output = '';
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($selectedPlayerId && $selectedPlayerId !== 'new') {
    $selectedPlayer = $playerManager->getPlayerById($selectedPlayerId);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'updatePlayer') {
        if ($selectedPlayerId === 'new') {
            // Neuen Spieler erstellen
            $playerName = isset($_POST['playerName']) ? trim($_POST['playerName']) : '';
            $birthYear = isset($_POST['birthYear']) ? $_POST['birthYear'] : null;
            $initialTTR = isset($_POST['ttr']) ? $_POST['ttr'] : null;

            if ($playerName !== '' && $birthYear !== '' && $initialTTR !== '') {
                $newPlayer = new Player(
                    uniqid(),
                    $playerName,
                    (int)$birthYear,
                    (float)$initialTTR
                );
                $playerManager->addPlayer($newPlayer);
                $selectedPlayer = $newPlayer;
                $selectedPlayerId = $newPlayer->id;
            } else {
                $error = "Bitte alle Felder ausfüllen.";
            }
        } elseif ($selectedPlayer) {
            // Spieler aktualisieren
            $birthYear = isset($_POST['birthYear']) ? $_POST['birthYear'] : null;
            $ttr = isset($_POST['ttr']) ? $_POST['ttr'] : null;
            if ($birthYear !== '' && $ttr !== '') {
                $selectedPlayer->birthYear = (int)$birthYear;
                $selectedPlayer->ttr = (float)$ttr;
                $playerManager->updatePlayer($selectedPlayer);
            } else {
                $error = "Bitte alle Felder ausfüllen.";
            }
        }
    } elseif ($action === 'calculate') {
        if ($selectedPlayer) {
            // Aktualisiere den aktuellen TTR-Wert, falls geändert
            $selectedPlayer->ttr = isset($_POST['ttr']) ? (float)$_POST['ttr'] : $selectedPlayer->ttr;
            $playerManager->updatePlayer($selectedPlayer);
        }

        $games = isset($_POST['games']) ? $_POST['games'] : [];
        // Reindexiere die Spiele
        $games = array_values($games);

        // Optionen sammeln
        $options = [
            'lessThan30Matches' => isset($_POST['lessThan30Matches']) ? true : false,
            'inactive' => isset($_POST['inactive']) ? true : false,
        ];

        // Berechnung durchführen
        $calculator = new TTRCalculator();
        $output = $calculator->calculate($selectedPlayer, $games, $options);

        // Spieler aktualisieren
        $playerManager->updatePlayer($selectedPlayer);
    }
}

// HTML-Templates einbinden
include __DIR__ . '/../templates/header.php';
include __DIR__ . '/../templates/player_form.php';

if ($selectedPlayerId && $selectedPlayerId !== 'new') {
    include __DIR__ . '/../templates/match_form.php';
}

include __DIR__ . '/../templates/footer.php';
