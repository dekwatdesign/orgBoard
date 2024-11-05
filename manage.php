<!DOCTYPE html>
<html lang="en">

<head>
    <base href="../" />
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
            width: 800px;
            height: 600px;
            border: 1px solid #ccc;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            margin: 20px auto;
        }

        .item {
            position: absolute;
            cursor: move;
            width: 100px;
            height: 100px;
        }

        .card-org {
            background-color: #f8f9fa;
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-radius: 0.25rem;
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
                            <label for="itemName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="itemName" required>
                        </div>
                        <div class="mb-3">
                            <label for="itemImage" class="form-label">Item Image</label>
                            <input type="file" class="form-control" id="itemImage" name="itemImage">
                        </div>
                        <div class="mb-3">
                            <label for="itemFrame" class="form-label">Frame Image</label>
                            <input type="file" class="form-control" id="itemFrame" name="itemFrame">
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