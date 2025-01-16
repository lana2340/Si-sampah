<?php
header('Content-Type: application/json');

// Database connection settings
$host = 'localhost'; // Ubah sesuai konfigurasi server Anda
$dbname = 'dbsi'; // Ubah sesuai nama database Anda
$username = 'root'; // Ubah sesuai username database Anda
$password = ''; // Ubah sesuai password database Anda

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch data from the 'sampah' table
    $stmt = $pdo->prepare("SELECT b_sampah, b_sampah_image FROM sampah");
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convert BLOB data to base64
    foreach ($results as &$row) {
        if (!empty($row['b_sampah_image'])) {
            $row['b_sampah_image'] = base64_encode($row['b_sampah_image']);
        }
    }

    // Output the results as JSON
    echo json_encode($results);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
