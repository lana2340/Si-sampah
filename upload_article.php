<?php
// Koneksi ke database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'dbSI';

$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'upload_article') {
        // Ambil data dari form
        $title = $conn->real_escape_string($_POST['title']);
        $content = $conn->real_escape_string($_POST['content']);

        // Proses file gambar jika ada
        $image = null;
        if (isset($_FILES['articleImage']) && $_FILES['articleImage']['error'] === UPLOAD_ERR_OK) {
            $imageName = basename($_FILES['articleImage']['name']);
            $targetDir = 'uploads/';
            $targetFile = $targetDir . $imageName;

            if (move_uploaded_file($_FILES['articleImage']['tmp_name'], $targetFile)) {
                $image = $imageName;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal mengunggah gambar.']);
                exit;
            }
        }

        // Simpan ke tabel edukasi
        $sql = "INSERT INTO edukasi (title, content, image) VALUES ('$title', '$content', '$image')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(['status' => 'success', 'message' => 'Artikel berhasil diunggah ke tabel edukasi.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan artikel: ' . $conn->error]);
        }
    }

    if ($action === 'upload_image') {
        // Ambil data dari form
        $description = $conn->real_escape_string($_POST['description']);

        // Proses file gambar
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageName = basename($_FILES['image']['name']);
            $targetDir = 'uploads/';
            $targetFile = $targetDir . $imageName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // Simpan ke tabel gambar
                $sql = "INSERT INTO gambar (description, image) VALUES ('$description', '$imageName')";

                if ($conn->query($sql) === TRUE) {
                    echo json_encode(['status' => 'success', 'message' => 'Gambar berhasil diunggah ke tabel gambar.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan gambar: ' . $conn->error]);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal mengunggah gambar.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gambar tidak ditemukan atau terjadi kesalahan.']);
        }
    }
}

$conn->close();
?>
