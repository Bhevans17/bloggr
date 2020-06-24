<?php
$stmt = $pdo->prepare("SELECT * FROM post WHERE username=? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$_SESSION["user"]]);
$user = $stmt->fetchAll();
