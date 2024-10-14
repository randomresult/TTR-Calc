<?php
namespace App\Classes;

class TTRCalculator {
    public function calculate($player, $games, $options) {
        // Extrahiere Optionen
        $lessThan30Matches = isset($options['lessThan30Matches']) ? $options['lessThan30Matches'] : false;
        $inactive = isset($options['inactive']) ? $options['inactive'] : false;

        // Alterskategorie basierend auf dem Geburtsjahr berechnen
        $currentYear = date('Y');
        $age = $currentYear - $player->birthYear;

        if ($age < 16) {
            $ageCategory = 'under16';
        } elseif ($age < 21) {
            $ageCategory = 'under21';
        } else {
            $ageCategory = 'over21';
        }

        // K-Faktor berechnen
        $kFactor = 16;

        // +4, wenn weniger als 30 gewertete Einzel
        if ($lessThan30Matches) {
            $kFactor += 4;
        }

        // +4, wenn Spieler unter 21 Jahre
        if ($ageCategory === 'under21' || $ageCategory === 'under16') {
            $kFactor += 4;
        }

        // +4, wenn Spieler unter 16 Jahre
        if ($ageCategory === 'under16') {
            $kFactor += 4;
        }

        // +4, wenn Spieler 365 Tage inaktiv war (für die nächsten 15 Einzel)
        $inactiveMatches = 0;
        if ($inactive) {
            $kFactor += 4;
        }

        $currentTTR = $player->ttr;
        $totalPointsGained = 0; // Variable für die gesamte Punktänderung
        $output = '';

        foreach ($games as $index => $game) {
            $opponentName = isset($game['opponentName']) ? $game['opponentName'] : '';
            $opponentTTR = isset($game['opponentTTR']) ? (float)$game['opponentTTR'] : 0;
            $result = isset($game['result']) && $game['result'] == 'win' ? 1 : 0;

            // Erwartungswert W berechnen
            $deltaTTR = $opponentTTR - $currentTTR;
            $exponent = $deltaTTR / 150;
            $tenPower = pow(10, $exponent);
            $W = 1 / (1 + $tenPower);

            // Anpassung des K-Faktors für inaktive Spieler (nur für die ersten 15 Einzel)
            $adjustedKFactor = $kFactor;
            if ($inactive && $inactiveMatches >= 15) {
                $adjustedKFactor -= 4; // Entferne die +4 nach 15 Spielen
            }

            // Punktänderung berechnen
            $deltaPoints = $adjustedKFactor * ($result - $W);

            // Runde die Punktänderung auf ganze Zahlen
            $deltaPointsRounded = round($deltaPoints);

            // Neue TTR-Punktzahl aktualisieren
            $currentTTR += $deltaPointsRounded;

            // Gesamte Punktänderung aktualisieren
            $totalPointsGained += $deltaPointsRounded;

            // Anzahl der Inaktivitäts-Spiele erhöhen
            if ($inactive) {
                $inactiveMatches++;
            }

            // Ergebnisse sammeln
            $output .= "<h3>Spiel " . ($index + 1) . ":</h3>";
            $output .= "<ul>";
            $output .= "<li>Gegner Name: $opponentName</li>";
            $output .= "<li>Gegner TTR: $opponentTTR</li>";
            $output .= "<li>Ergebnis: " . ($result ? 'Sieg' : 'Niederlage') . "</li>";
            $output .= "<li>Erwartungswert W: " . round($W, 5) . "</li>";
            $output .= "<li>Punktänderung: " . $deltaPointsRounded . "</li>";
            $output .= "<li>Neue TTR-Punktzahl: " . round($currentTTR) . "</li>";
            $output .= "</ul><hr>";

            // Speichere das Spiel in der Spielerhistorie
            $player->matches[] = [
                'date' => date('Y-m-d'),
                'opponentName' => $opponentName,
                'opponentTTR' => $opponentTTR,
                'result' => $result ? 'win' : 'loss',
                'pointsChange' => $deltaPointsRounded,
            ];
        }

        $finalTTR = round($currentTTR);
        $totalPointsGained = round($totalPointsGained);

        $output .= "<h2>Deine finale TTR-Punktzahl nach allen Spielen: $finalTTR</h2>";
        $output .= "<h2>Gesamt hinzugewonnene Punkte: $totalPointsGained</h2>";
        $output .= "<h2>Verwendeter K-Faktor: $kFactor</h2>";

        // Aktualisiere den Spieler mit dem neuen TTR-Wert
        $player->ttr = $finalTTR;

        return $output;
    }
}
