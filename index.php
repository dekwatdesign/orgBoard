<?php
require_once 'configs/database.php';

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

$area_settings_sql = "  SELECT 
                            * 
                        FROM 
                            area_settings AS A
                            INNER JOIN area_sizes AS B ON A.setting_value = B.id
                        WHERE 
                            A.id = 1";
$area_settings_query = mysqli_query($conn, $area_settings_sql);
$area_settings_row = mysqli_fetch_assoc($area_settings_query);
$area_size_width = $area_settings_row['size_width'];
$area_size_height = $area_settings_row['size_height'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <base href="./" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Display - Frontend</title>
    <link rel="stylesheet" href="./assets/css/style.bundle.css">
    <link rel="stylesheet" href="./assets/css/fonts.css">
    <link rel="stylesheet" href="./assets/css/fontawesome.css">
    <link rel="stylesheet" href="./assets/css/custom.css">
    <style>
        #display-area {
            position: relative;
            width: <?php echo $area_size_width ?>px;
            height: <?php echo $area_size_height ?>px;
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
                    style="left: <?php echo $item['x_pos']; ?>px; top: <?php echo $item['y_pos']; ?>px; width: <?php echo $item['size_width']; ?>px; height: <?php echo $item['size_height']; ?>px;">
                    <img src="<?php echo $item['image_url']; ?>" class="img-fluid"
                        style="border-image: url('<?php echo $item['frame_url']; ?>') 30 / 10px;">
                </div>
            <?php endforeach; ?>
        </div>

    </div>
    <script src="./assets/js/scripts.bundle.js"></script>
</body>

</html>