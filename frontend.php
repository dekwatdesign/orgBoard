<?php
$servername = "localhost";
$username = "root"; // เปลี่ยนเป็นชื่อผู้ใช้ฐานข้อมูลของคุณ
$password = ""; // เปลี่ยนเป็นรหัสผ่านฐานข้อมูลของคุณ
$dbname = "image_management";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ดึงข้อมูลไอเท็มทั้งหมด
$itemsResult = $conn->query("SELECT * FROM items");
$items = [];
while ($row = $itemsResult->fetch_assoc()) {
    $items[] = $row;
}

// ดึงภาพพื้นหลังของ Area
$areaResult = $conn->query("SELECT background_url FROM area WHERE id=1");
$area = $areaResult->fetch_assoc();
$background_url = $area['background_url'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Display - Frontend</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #display-area {
            position: relative;
            width: 800px;
            height: 600px;
            background-image: url('<?php echo $background_url; ?>');
            background-size: cover;
            background-position: center;
            border: 1px solid #ccc;
            margin: 20px auto;
        }

        .display-item {
            position: absolute;
            width: 100px;
            height: 100px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mt-4">Image Display</h1>
        <div id="display-area" style="background-image: url('<?php echo $background_url; ?>');">
            <?php foreach ($items as $item): ?>
                <div class="display-item"
                    style="left: <?php echo $item['x_pos']; ?>px; top: <?php echo $item['y_pos']; ?>px;">
                    <img src="<?php echo $item['image_url']; ?>" class="img-fluid"
                        style="border-image: url('<?php echo $item['frame_url']; ?>') 30 / 10px;">
                </div>
            <?php endforeach; ?>
        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>