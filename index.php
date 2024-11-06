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
    <script src="assets/js/scripts.bundle.js"></script>
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            loadItems();
            loadAreaBackground();

            // Load items from the database
            function loadItems() {
                $.post('manage_items.php', {
                    action: 'get_items'
                }, function(data) {
                    const items = JSON.parse(data);
                    $('#display-area').empty();

                    // Loop through each item
                    items.forEach(item => {
                        // Use Promise to handle the asynchronous HTML generation
                        convertJsonToHtml(item).then((itemHTML) => {
                            const itemElement = $(`
                                                    <div class="item d-flex flex-row flex-nowrap" data-id="${item.id}" style="left: ${item.x_pos}px; top: ${item.y_pos}px;">
                                                        ${itemHTML}
                                                    </div>
                                                `);
                            // Append the item element to the area container
                            $('#display-area').append(itemElement);
                        }).catch((error) => {
                            console.error('Error generating item HTML:', error);
                        });
                    });
                });
            }

            function loadAreaBackground() {
                $.post('save_area.php', {
                    action: 'get_background'
                }, function(data) {
                    $('#display-area').css('background-image', `url('uploads/${data.background_url}')`);
                });
            }

            function convertJsonToHtml(data) {
                return new Promise((resolve, reject) => {

                    const comp = data.comp;

                    let replacements = [];
                    let templateFile = '';

                    if (data.type_id == 1) {
                        templateFile = 'leader_card_template.temp';
                        replacements = [{
                                placeholder: '{{item_id}}',
                                value: data.id
                            },
                            {
                                placeholder: '{{item_pname}}',
                                value: comp.item_pname
                            },
                            {
                                placeholder: '{{item_fname}}',
                                value: comp.item_fname
                            },
                            {
                                placeholder: '{{item_lname}}',
                                value: comp.item_lname
                            },
                            {
                                placeholder: '{{item_work_position}}',
                                value: comp.item_work_position
                            },
                            {
                                placeholder: '{{item_bg}}',
                                value: comp.item_bg
                            },
                            {
                                placeholder: '{{size_width}}',
                                value: comp.size_width
                            },
                            {
                                placeholder: '{{size_height}}',
                                value: comp.size_height
                            },
                            {
                                placeholder: '{{item_frame_size}}',
                                value: comp.item_frame_size
                            },
                            {
                                placeholder: '{{item_avatar}}',
                                value: comp.item_avatar
                            },
                            {
                                placeholder: '{{item_frame}}',
                                value: comp.item_frame
                            }
                        ];
                    } else if (data.type_id == 2) {
                        templateFile = 'img_template.temp';
                        replacements = [{
                                placeholder: '{{item_id}}',
                                value: data.id
                            },
                            {
                                placeholder: '{{img_width}}',
                                value: comp.img_width
                            },
                            {
                                placeholder: '{{img_height}}',
                                value: comp.img_height
                            },
                            {
                                placeholder: '{{img_filename}}',
                                value: comp.item_img
                            },
                        ];
                    } else if (data.type_id == 3) {
                        templateFile = 'txt_template.temp';
                        replacements = [{
                                placeholder: '{{item_id}}',
                                value: data.id
                            },
                            {
                                placeholder: '{{txt_text}}',
                                value: comp.item_txt
                            },
                            {
                                placeholder: '{{txt_size}}',
                                value: comp.item_txt_size
                            },
                            {
                                placeholder: '{{txt_weight}}',
                                value: comp.item_txt_weight
                            },
                            {
                                placeholder: '{{txt_color}}',
                                value: comp.item_txt_color
                            },
                        ];
                    }

                    // Load the template and populate placeholders
                    $.get(`templates/${templateFile}`, function(template) {

                        // Use the array to replace the placeholders in the template
                        let htmlString = template;

                        replacements.forEach(replacement => {
                            const regex = new RegExp(replacement.placeholder, 'g');
                            htmlString = htmlString.replace(regex, replacement.value);
                        });

                        resolve(htmlString); // Return the HTML as a resolved Promise
                    }).fail((error) => {
                        reject("Error loading the template"); // Handle errors
                    });
                });
            }
        });
    </script>
</body>

</html>