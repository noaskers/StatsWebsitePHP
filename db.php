<?php
// Example PDO connection
$host = "none";
$user = "admin";
$pass = "none";
$db = "name";
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

function getPlayerStatsByUUID($uuid) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM player_stats WHERE uuid = ?");
    $stmt->execute([$uuid]);
    return $stmt->fetch();
}
