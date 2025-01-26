<?php
include_once("class_serpent.php");

header('Content-Type: application/json');

$race = $_GET['race'] ?? null;
$Objserpent = new Serpent("vide");

$mates = $Objserpent->getPotentialMates($race);

echo json_encode($mates);
