<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "image_management";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$action = $_POST['action'] ?? null;

if ($action === 'update_background') {
    if (isset($_FILES['areaBackground']) && $_FILES['areaBackground']['error'] == 0) {

        $bg_sql = "SELECT * FROM area_settings WHERE id=2";
        $bg_query = $conn->query($bg_sql);
        $bg_row = $bg_query->fetch_assoc();
        $bg_path = $bg_row['setting_value'];

        if (file_exists($bg_path)) {
            unlink($bg_path);
        }

        $uploadDir = "uploads/";
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777);
        }

        $fileExtension = pathinfo($_FILES['areaBackground']['name'], PATHINFO_EXTENSION);
        $newFileName = uniqid('area_'.date('YmdHis'), true) . '.' . $fileExtension;
        $uploadPath = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['areaBackground']['tmp_name'], $uploadPath)) {
            $sql = "UPDATE area_settings SET setting_value='$newFileName' WHERE id=2";
            $conn->query($sql);
            echo "Background Updated";
        } else {
            echo "File upload failed.";
        }
    }
} else if ($action === 'get_background') {
    $sql = "SELECT setting_value FROM area_settings WHERE id=2";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['background_url' => $row['setting_value']]);
}

$conn->close();
