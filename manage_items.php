<?php
require_once 'configs/database.php';

$action = $_POST['action'];

$uploadDir = "./uploads/";
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777);
}

switch ($action) {
    case 'add_item':

        $name = $_POST['name'];
        $position = $_POST['position'];

        if (isset($_FILES['itemAvatar']) && $_FILES['itemAvatar']['error'] == 0) {

            if (file_exists($bg_path)) {
                unlink($bg_path);
            }

            $fileExtension = pathinfo($_FILES['areaBackground']['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid('area_' . date('YmdHis'), true) . '.' . $fileExtension;
            $uploadPath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['areaBackground']['tmp_name'], $uploadPath)) {

            } else {

            }
        }

        $sql = "INSERT INTO items (name, position, image_url, frame_url) VALUES ('$name', '$position', '$image_url', '$frame_url')";
        $conn->query($sql);
        echo $conn->insert_id;
        break;

    case 'update_item':
        $item_id = $_POST['item_id'];

        // Avatar ===================
        $avatar_sql = " SELECT
                            B.item_value 
                        FROM
                            items AS A
                            INNER JOIN items_components AS B ON A.id = B.item_id 
                        WHERE
                            A.id = '$item_id' AND
                            B.item_setting_id = 5";
        $avatar_query = $conn->query($avatar_sql);
        $avatar_row = $avatar_query->fetch_assoc();
        $avatar_file = $avatar_row['item_value'];

        if (isset($_FILES['itemAvatar']) && $_FILES['itemAvatar']['error'] == 0):
            if (file_exists($uploadDir . $avatar_file)) {
                unlink($uploadDir . $avatar_file);
            }
            $avatar_file_ext = pathinfo($_FILES['itemAvatar']['name'], PATHINFO_EXTENSION);
            $avatar_file_name_new = uniqid('avatar_' . date('YmdHis'), true) . '.' . $avatar_file_ext;
            $avatar_upload_path = $uploadDir . $avatar_file_name_new;

            if (move_uploaded_file($_FILES['itemAvatar']['tmp_name'], $avatar_upload_path)) {
                $avatar_file = $avatar_file_name_new;
            }
        endif;

        // Frame ===================
        $frame_sql = "  SELECT
                            B.item_value 
                        FROM
                            items AS A
                            INNER JOIN items_components AS B ON A.id = B.item_id 
                        WHERE
                            A.id = '$item_id' AND
                            B.item_setting_id = 6";
        $frame_query = $conn->query($frame_sql);
        $frame_row = $frame_query->fetch_assoc();
        $frame_file = $frame_row['item_value'];

        if (isset($_FILES['itemFrame']) && $_FILES['itemFrame']['error'] == 0):
            if (file_exists($uploadDir . $frame_file)) {
                unlink($uploadDir . $frame_file);
            }
            $frame_file_ext = pathinfo($_FILES['itemFrame']['name'], PATHINFO_EXTENSION);
            $frame_file_name_new = uniqid('frame_' . date('YmdHis'), true) . '.' . $frame_file_ext;
            $frame_upload_path = $uploadDir . $frame_file_name_new;

            if (move_uploaded_file($_FILES['itemFrame']['tmp_name'], $frame_upload_path)) {
                $frame_file = $frame_file_name_new;
            }
        endif;

        $item_title_name = $_POST['itemTitleName'];
        $item_first_name = $_POST['itemFirstName'];
        $item_last_name = $_POST['itemLastName'];
        $item_work_position = $_POST['itemWorkPosition'];

        $sql = "UPDATE items_components SET item_value='$item_title_name' WHERE item_id='$item_id' AND item_setting_id=1;";
        $sql .= "UPDATE items_components SET item_value='$item_first_name' WHERE item_id='$item_id' AND item_setting_id=2;";
        $sql .= "UPDATE items_components SET item_value='$item_last_name' WHERE item_id='$item_id' AND item_setting_id=3;";
        $sql .= "UPDATE items_components SET item_value='$item_work_position' WHERE item_id='$item_id' AND item_setting_id=4;";
        $sql .= "UPDATE items_components SET item_value='$avatar_file' WHERE item_id='$item_id' AND item_setting_id=5;";
        $sql .= "UPDATE items_components SET item_value='$frame_file' WHERE item_id='$item_id' AND item_setting_id=6;";

        $conn->multi_query($sql);
        echo "Updated";
        break;

    case 'delete_item':
        $id = $_POST['id'];
        $sql = "DELETE FROM items WHERE id=$id";
        $conn->query($sql);
        echo "Deleted";
        break;

    case 'update_item_position':
        $id = $_POST['id'];
        $x_pos = $_POST['x_pos'];
        $y_pos = $_POST['y_pos'];
        $sql = "UPDATE items SET x_pos=$x_pos, y_pos=$y_pos WHERE id=$id";
        $conn->query($sql);
        echo "Position Updated";
        break;

    case 'get_comp_settings':
        $item_id = $_POST['id'];
        $sql = "SELECT
                    A.item_value,
                    B.setting_name 
                FROM
                    items_components AS A
                    INNER JOIN items_settings AS B ON A.item_setting_id = B.id 
                WHERE
                    A.item_id = '$item_id'
                ORDER BY
                    B.setting_sort ASC";
        $result = $conn->query($sql);
        $item_comp = [];
        while ($row = $result->fetch_assoc()):
            $item_comp[$row['setting_name']] = $row['item_value'];
        endwhile;
        echo json_encode($item_comp);
        break;

    case 'get_items':
        $sql = "SELECT 
                    A.id AS item_id,
                    A.name,
                    A.x_pos,
                    A.y_pos,
                    B.size_width,
                    B.size_height
                FROM 
                    items AS A
                    INNER JOIN items_sizes AS B ON A.item_sizes_id = B.id";
        $result = $conn->query($sql);
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $item_id = $row['item_id'];
            $comp_result = $conn->query("SELECT * FROM items_components WHERE item_id='$item_id' ");
            while ($comp_row = $comp_result->fetch_assoc()):
                $row['comp'][] = $comp_row;
            endwhile;

            $items[] = $row;
        }
        echo json_encode($items);
        break;
}

$conn->close();
?>