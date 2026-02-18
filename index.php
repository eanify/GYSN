<?php
header("Content-Type: application/json");

// Bayanan TiDB daga hoton ka
$host = "gateway01.eu-central-1.prod.aws.tidbcloud.com";
$port = 4000;
$user = "2qXLc6LmJoVKA6E.root";
$pass = "I21Ed9N393g7U1No";
$db   = "test";

// Hadawa da Database (Tidb yana bukatar SSL)
$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

if (!mysqli_real_connect($conn, $host, $user, $pass, $db, $port)) {
    echo json_encode(["status" => "error", "message" => "Connection failed"]);
    exit();
}

// Duba wane irin request ne (GET ko POST)
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Karbar data daga Sketchware
    $suna = $_POST['suna'] ?? 'Babu Suna';
    $sako = $_POST['sako'] ?? 'Babu Sako';

    $stmt = $conn->prepare("INSERT INTO sakonni (suna, sako) VALUES (?, ?)");
    $stmt->bind_param("ss", $suna, $sako);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "An adana sako a SQL"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
} else {
    // Idan GET ne, nuna sakonnin da ke ciki
    $result = mysqli_query($conn, "SELECT * FROM sakonni ORDER BY id DESC");
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($rows);
}

mysqli_close($conn);
?>
