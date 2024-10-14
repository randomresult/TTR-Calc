<?php
// classes/PlayerManager.php
namespace App\Classes;
class PlayerManager {
    private $players = [];
    private $dataFile = __DIR__ . '/../data/players.json';

    public function __construct() {
        $this->loadPlayers();
    }

    public function loadPlayers() {
        if (file_exists($this->dataFile)) {
            $json = file_get_contents($this->dataFile);
            $data = json_decode($json, true);
            if ($data !== null) {
                foreach ($data as $playerData) {
                    $this->players[] = new Player(
                        $playerData['id'],
                        $playerData['name'],
                        $playerData['birthYear'],
                        $playerData['ttr'],
                        isset($playerData['matches']) ? $playerData['matches'] : []
                    );
                }
            }
        }
    }

    public function savePlayers() {
        $data = [];
        foreach ($this->players as $player) {
            $data[] = [
                'id' => $player->id,
                'name' => $player->name,
                'birthYear' => $player->birthYear,
                'ttr' => $player->ttr,
                'matches' => $player->matches,
            ];
        }
        file_put_contents($this->dataFile, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function getPlayers() {
        return $this->players;
    }

    public function getPlayerById($id) {
        foreach ($this->players as $player) {
            if ($player->id === $id) {
                return $player;
            }
        }
        return null;
    }

    public function addPlayer($player) {
        $this->players[] = $player;
        $this->savePlayers();
    }

    public function updatePlayer($updatedPlayer) {
        foreach ($this->players as &$player) {
            if ($player->id === $updatedPlayer->id) {
                $player = $updatedPlayer;
                break;
            }
        }
        $this->savePlayers();
    }
}
