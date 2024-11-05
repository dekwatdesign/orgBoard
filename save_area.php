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
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777);
        }
        $target_file = $target_dir . basename($_FILES["areaBackground"]["name"]);
        move_uploaded_file($_FILES["areaBackground"]["tmp_name"], $target_file);

        $sql = "UPDATE area SET background_url='$target_file' WHERE id=1";
        $conn->query($sql);
        echo "Background Updated";
    }
} elseif ($action === 'get_background') {
    $sql = "SELECT background_url FROM area WHERE id=1";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo json_encode(['background_url' => $row['background_url']]);
}

$conn->close();
?>
