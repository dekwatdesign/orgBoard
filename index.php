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
            background-size: cover;
            background-position: center;
            border: 1px solid #ccc;
            margin: 20px auto;
        }

        .item {
            position: absolute;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mt-4">Image Display</h1>
        <div class="scroll pe-5" data-kt-scroll="true" data-kt-scroll-height="auto" data-kt-scroll-offset="100px">
            <div id="display-area"></div>
        </div>


    </div>
    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/jquery-ui.min.js"></script>

    <script>
        const configs = {
            orgb_selector: '#display-area',
            orgb_dir: './uploads/',
            sign_dir: './assets/medias/sign.svg',
            temp_dir: './templates/',
            actn_dir: './actions/',
        };
    </script>
    <script src="assets/js/custom.js"></script>

    <script>
        $(document).ready(function() {
            loadItems();
            loadAreaBackground();
        });
    </script>
</body>

</html>