<?php
require_once 'configs/database.php';

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <base href="./" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Management System</title>
    <link rel="stylesheet" type="text/css" href="assets/plugins/global/plugins.bundle.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/style.bundle.css" />

    <link rel="stylesheet" type="text/css" href="assets/css/fonts.css">
    <link rel="stylesheet" type="text/css" href="assets/css/fontawesome.css">
    <link rel="stylesheet" type="text/css" href="assets/css/custom.css">

    <link rel="stylesheet" type="text/css" href="assets/css/jquery-ui.min.css">

    <style>
        #area {
            position: relative;
            width: <?php echo $area_size_width ?>px;
            height: <?php echo $area_size_height ?>px;
            border: 1px solid #ccc;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            margin: 20px auto;
        }

        .item {
            position: absolute;
            cursor: move;
            /* width: 100px; */
            /* height: 100px; */
        }
        
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mt-4">Image Management System</h1>
        <div class="mb-3">
            <button class="btn btn-primary" id="addItemBtn">Add Item</button>
            <button class="btn btn-secondary" id="editAreaBtn">Edit Area Background</button>
        </div>
        <div id="area"></div>
    </div>

    <!-- Modal for editing item -->
    <div class="modal fade" id="editItemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editItemForm" enctype="multipart/form-data">
                        <input type="hidden" id="editItemId">
                        <div class="mb-3">
                            <label for="itemTitleName" class="form-label required">Title Name</label>
                            <select id="itemTitleName" name="itemTitleName" class="form-select" required>
                                <option value="1">นาย</option>
                                <option value="2">นาง</option>
                                <option value="3">นางสาว</option>
                                <option value="4">พระ</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="itemFirstName" class="form-label required">First Name</label>
                            <input type="text" class="form-control" id="itemFirstName" name="itemFirstName" required>
                        </div>
                        <div class="mb-3">
                            <label for="itemLastName" class="form-label required">Last Name</label>
                            <input type="text" class="form-control" id="itemLastName" name="itemLastName" required>
                        </div>
                        <div class="mb-3">
                            <label for="itemWorkPosition" class="form-label required">Work Position</label>
                            <input type="text" class="form-control" id="itemWorkPosition" name="itemWorkPosition" required>
                        </div>
                        <div class="mb-3">
                            <label for="itemAvatar" class="form-label required">Avatar Image</label>
                            <div class="d-flex flex-row gap-2 mb-2">
                                <span class="mt-4 text-gray-500">Preview:</span>
                                <img id="itemAvatarPreview" src="" alt="Avatar Preview" class="img-thumbnail mt-2 w-100px h-100px" style="object-fit: contain;">
                            </div>
                            <input type="file" class="form-control" accept="image/*" id="itemAvatar" name="itemAvatar">
                        </div>
                        <div class="mb-3">
                            <label for="itemFrame" class="form-label required">Frame Image</label>
                            <div class="d-flex flex-row gap-2 mb-2">
                                <span class="mt-4 text-gray-500">Preview:</span>
                                <img id="itemFramePreview" src="" alt="Frame Preview" class="img-thumbnail mt-2 w-100px h-100px" style="object-fit: contain;">
                            </div>
                            <input type="file" class="form-control" accept="image/*" id="itemFrame" name="itemFrame">
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Area Background -->
    <div class="modal fade" id="editAreaModal" tabindex="-1" aria-labelledby="editAreaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAreaModalLabel">Edit Area Background</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAreaForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="areaBackground" class="form-label">Background Image</label>
                            <input type="file" class="form-control" id="areaBackground" name="areaBackground">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/plugins/global/plugins.bundle.js"></script>
    <script src="assets/js/scripts.bundle.js"></script>

    <!-- Include jQuery -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <!-- Include jQuery UI -->
    <script src="assets/js/jquery-ui.min.js"></script>

    <script src="assets/js/custom.js"></script>
</body>

</html>