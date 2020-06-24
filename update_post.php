<?php
include "./config/db.php";

$stmt = $pdo->prepare("DELETE FROM post WHERE username=? AND");
$stmt->execute([$_SESSION["user"]]);
$user = $stmt->fetchAll();
