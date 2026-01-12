<?php
require __DIR__ . '/../../../app/bootstrap.php';
require __DIR__ . '/../../../app/functions.php';

header('Content-Type: application/json');

$guildId = (int)($_POST['GuildID'] ?? 0);
$sc      = $_POST['StarCitizenRelated'] ?? null;
$bl      = $_POST['Blacklisted'] ?? null;

if (!$guildId) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid GuildID']);
    exit;
}

if (!in_array($sc, ['Y','N',null,''], true)) {
    $sc = null;
}
if (!in_array($bl, ['Y','N',null,''], true)) {
    $bl = null;
}

global $pdo;

$stmt = $pdo->prepare("
    UPDATE Guilds
    SET
        StarCitizenRelated = :sc,
        Blacklisted        = :bl,
        ModifyDate         = NOW()
    WHERE GuildID = :id
");

$stmt->execute([
    ':sc' => $sc ?: null,
    ':bl' => $bl ?: null,
    ':id' => $guildId
]);

echo json_encode([
    'success' => true,
    'StarCitizenRelated' => $sc,
    'Blacklisted'        => $bl
]);
