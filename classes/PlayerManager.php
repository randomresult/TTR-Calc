<?php
// classes/Player.php
namespace App\Classes;
class Player {
    public $id;
    public $name;
    public $birthYear;
    public $ttr;
    public $matches;

    public function __construct($id, $name, $birthYear, $ttr, $matches = []) {
        $this->id = $id;
        $this->name = $name;
        $this->birthYear = $birthYear;
        $this->ttr = $ttr;
        $this->matches = $matches;
    }
}