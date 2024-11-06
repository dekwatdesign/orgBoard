<?php
require_once 'configs/database.php';
require_once 'includes/functions.php';

$action = $_POST['action'];

$uploadDir = "uploads/";
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777);
}

switch ($action) {
    case 'add_item':

        // Avatar ===================
        $avatar_file = null;
        if (isset($_FILES['itemAvatar']) && $_FILES['itemAvatar']['error'] == 0):
            $avatar_file_ext = pathinfo($_FILES['itemAvatar']['name'], PATHINFO_EXTENSION);
            $avatar_file_name_new = uniqid('avatar_' . date('YmdHis'), true) . '.' . $avatar_file_ext;
            $avatar_upload_path = $uploadDir . $avatar_file_name_new;

            if (move_uploaded_file($_FILES['itemAvatar']['tmp_name'], $avatar_upload_path)) {
                $avatar_file = $avatar_file_name_new;
            }
        endif;

        // Frame ===================
        $frame_file = null;
        if (isset($_FILES['itemFrame']) && $_FILES['itemFrame']['error'] == 0):
            $frame_file_ext = pathinfo($_FILES['itemFrame']['name'], PATHINFO_EXTENSION);
            $frame_file_name_new = uniqid('frame_' . date('YmdHis'), true) . '.' . $frame_file_ext;
            $frame_upload_path = $uploadDir . $frame_file_name_new;

            if (move_uploaded_file($_FILES['itemFrame']['tmp_name'], $frame_upload_path)) {
                $frame_file = $frame_file_name_new;
            }
        endif;

        // Background ===================
        $bg_file = null;
        if (isset($_FILES['itemBG']) && $_FILES['itemBG']['error'] == 0):
            $bg_file_ext = pathinfo($_FILES['itemBG']['name'], PATHINFO_EXTENSION);
            $bg_file_name_new = uniqid('bg_' . date('YmdHis'), true) . '.' . $bg_file_ext;
            $bg_upload_path = $uploadDir . $bg_file_name_new;

            if (move_uploaded_file($_FILES['itemBG']['tmp_name'], $bg_upload_path)) {
                $bg_file = $bg_file_name_new;
            }
        endif;

        $itemName = 'item_' . date('YmdHis');
        $itemSize = $_POST['itemSize'];

        $sql = "INSERT INTO 
                    items (
                        `name`,
                        type_id,
                        x_pos,
                        y_pos,
                        item_sizes_id
                    ) 
                VALUES 
                    (
                        '$itemName',
                        1,
                        0,
                        0,
                        '$itemSize'
                    )";
        $conn->query($sql);

        $itemTitleName = $_POST['itemTitleName'];
        $itemFirstName = $_POST['itemFirstName'];
        $itemLastName = $_POST['itemLastName'];
        $itemWorkPosition = $_POST['itemWorkPosition'];
        $itemFrameSize = $_POST['itemFrameSize'];

        $component_set = [
            1 => $itemTitleName,
            2 => $itemFirstName,
            3 => $itemLastName,
            4 => $itemWorkPosition,
            5 => $avatar_file,
            6 => $frame_file,
            7 => $itemFrameSize,
            8 => $bg_file
        ];

        $items_last_id = $conn->insert_id;

        foreach ($component_set as $comp_id => $comp_value):
            $ins_arr = [
                'item_id' => $items_last_id,
                'item_setting_id' => $comp_id,
                'item_value' => $comp_value
            ];
            $sql = generateSQLInsert('items_components', $ins_arr);
            $conn->query($sql);
        endforeach;

        echo "Added";
        break;

    case 'copy_item':

        $item_id = $_POST['id'];

        $item_info_sql = "SELECT * FROM items WHERE id='$item_id'";
        $item_info_result = $conn->query($item_info_sql);
        $item_info_row = $item_info_result->fetch_assoc();

        $itemName = 'item_' . date('YmdHis');
        $itemSize = $item_info_row['item_sizes_id'];

        $sql = "INSERT INTO 
                    items (
                        `name`,
                        type_id,
                        x_pos,
                        y_pos,
                        item_sizes_id
                    ) 
                VALUES 
                    (
                        '$itemName',
                        1,
                        0,
                        0,
                        '$itemSize'
                    )";
        $conn->query($sql);
        $item_last_id = $conn->insert_id;

        $itemTitleName = '';
        $itemFirstName = '';
        $itemLastName = '';
        $itemWorkPosition = '';
        $itemFrameSize = '';

        $component_set = [];

        $item_comp_sql = "SELECT * FROM items_components WHERE item_id='$item_id'";
        $item_comp_result = $conn->query($item_comp_sql);
        while ($item_comp_row = $item_comp_result->fetch_assoc()) {
            $component_set[$item_comp_row['item_setting_id']] = $item_comp_row['item_value'];
        }

        // Avatar ===================
        if (file_exists($uploadDir . $component_set[5]) && strlen($component_set[5]) > 0) {
            $avatar_file_ext = pathinfo($uploadDir. $component_set[5], PATHINFO_EXTENSION);
            $avatar_file_name_new = uniqid('avatar_' . date('YmdHis'), true) . '.' . $avatar_file_ext;
            $avatar_upload_path = $uploadDir . $avatar_file_name_new;

            if (copy($uploadDir . $component_set[5], $avatar_upload_path)) {
                $component_set[5] = $avatar_file_name_new;
            }
        }

        // Frame ===================
        if (file_exists($uploadDir . $component_set[6]) && strlen($component_set[6]) > 0) {
            $frame_file_ext = pathinfo($uploadDir . $component_set[6], PATHINFO_EXTENSION);
            $frame_file_name_new = uniqid('frame_' . date('YmdHis'), true) . '.' . $frame_file_ext;
            $frame_upload_path = $uploadDir . $frame_file_name_new;

            if (copy($uploadDir . $component_set[6], $frame_upload_path)) {
                $component_set[6] = $frame_file_name_new;
            }
        }

        // Background ===================
        if (file_exists($uploadDir . $component_set[8]) && strlen($component_set[8]) > 0) {
            $bg_file_ext = pathinfo($uploadDir . $component_set[8], PATHINFO_EXTENSION);
            $bg_file_name_new = uniqid('bg_' . date('YmdHis'), true) . '.' . $bg_file_ext;
            $bg_upload_path = $uploadDir . $bg_file_name_new;

            if (copy($uploadDir . $component_set[8], $bg_upload_path)) {
                $component_set[8] = $bg_file_name_new;
            }
        }

        $ins_sql = [];
        foreach ($component_set as $comp_id => $comp_value):
            $ins_arr = [
                'item_id' => $item_last_id,
                'item_setting_id' => $comp_id,
                'item_value' => $comp_value
            ];
            $sql = generateSQLInsert('items_components', $ins_arr);
            $ins_sql[] = $sql;
            $conn->query($sql);
        endforeach;

        echo "Duplicated";
        echo json_encode($ins_sql);
        break;

    case 'update_item':
        $item_id = $_POST['item_id'];
        $item_size = $_POST['itemSize'];

        $update_item_sql = "UPDATE items SET item_sizes_id='$item_size' WHERE id='$item_id'";
        $conn->query($update_item_sql);

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
            if (file_exists($uploadDir . $avatar_file) && strlen($avatar_file) > 0) {
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
            if (file_exists($uploadDir . $frame_file) && strlen($frame_file) > 0) {
                unlink($uploadDir . $frame_file);
            }
            $frame_file_ext = pathinfo($_FILES['itemFrame']['name'], PATHINFO_EXTENSION);
            $frame_file_name_new = uniqid('frame_' . date('YmdHis'), true) . '.' . $frame_file_ext;
            $frame_upload_path = $uploadDir . $frame_file_name_new;

            if (move_uploaded_file($_FILES['itemFrame']['tmp_name'], $frame_upload_path)) {
                $frame_file = $frame_file_name_new;
            }
        endif;

        // Background ===================
        $bg_sql = " SELECT
                        B.item_value 
                    FROM
                        items AS A
                        INNER JOIN items_components AS B ON A.id = B.item_id 
                    WHERE
                        A.id = '$item_id' AND
                        B.item_setting_id = 8";
        $bg_query = $conn->query($bg_sql);
        $bg_row = $bg_query->fetch_assoc();
        $bg_file = $bg_row['item_value'];

        if (isset($_FILES['itemBG']) && $_FILES['itemBG']['error'] == 0):
            if (file_exists($uploadDir . $bg_file) && strlen($bg_file) > 0) {
                unlink($uploadDir . $bg_file);
            }
            $bg_file_ext = pathinfo($_FILES['itemBG']['name'], PATHINFO_EXTENSION);
            $bg_file_name_new = uniqid('bg_' . date('YmdHis'), true) . '.' . $bg_file_ext;
            $bg_upload_path = $uploadDir . $bg_file_name_new;

            if (move_uploaded_file($_FILES['itemBG']['tmp_name'], $bg_upload_path)) {
                $bg_file = $bg_file_name_new;
            }
        endif;

        $item_title_name = $_POST['itemTitleName'];
        $item_first_name = $_POST['itemFirstName'];
        $item_last_name = $_POST['itemLastName'];
        $item_work_position = $_POST['itemWorkPosition'];
        $item_frame_size = $_POST['itemFrameSize'];

        $sql = "UPDATE items_components SET item_value='$item_title_name' WHERE item_id='$item_id' AND item_setting_id=1;";
        $sql .= "UPDATE items_components SET item_value='$item_first_name' WHERE item_id='$item_id' AND item_setting_id=2;";
        $sql .= "UPDATE items_components SET item_value='$item_last_name' WHERE item_id='$item_id' AND item_setting_id=3;";
        $sql .= "UPDATE items_components SET item_value='$item_work_position' WHERE item_id='$item_id' AND item_setting_id=4;";
        $sql .= "UPDATE items_components SET item_value='$avatar_file' WHERE item_id='$item_id' AND item_setting_id=5;";
        $sql .= "UPDATE items_components SET item_value='$frame_file' WHERE item_id='$item_id' AND item_setting_id=6;";
        $sql .= "UPDATE items_components SET item_value='$item_frame_size' WHERE item_id='$item_id' AND item_setting_id=7;";
        $sql .= "UPDATE items_components SET item_value='$bg_file' WHERE item_id='$item_id' AND item_setting_id=8;";

        $conn->multi_query($sql);
        echo "Updated";
        break;

    case 'delete_item':
        $id = $_POST['id'];

        $sql = "DELETE FROM items WHERE id=$id";
        $conn->query($sql);

        $sql = "DELETE FROM items_components WHERE item_id=$id";
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
        $item_comp = [];

        $info_sql = "SELECT item_sizes_id FROM items WHERE id='$item_id'";
        $info_result = $conn->query($info_sql);
        $info_row = $info_result->fetch_assoc();
        $item_comp['item_size'] = $info_row['item_sizes_id'];

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
            $comp_sql = "   SELECT
                                B.setting_name,
                                A.item_value 
                            FROM
                                items_components AS A
                                INNER JOIN items_settings AS B ON A.item_setting_id = B.id
                            WHERE
                                A.item_id='$item_id'";
            $comp_result = $conn->query($comp_sql);
            while ($comp_row = $comp_result->fetch_assoc()):
                if ($comp_row['setting_name'] == 'item_pname'):
                    $setting_sql = "SELECT prefix_title FROM prefix_name WHERE id='" . $comp_row['item_value'] . "'";
                    $setting_result = $conn->query($setting_sql);
                    $setting_row = $setting_result->fetch_assoc();
                    $row['comp'][$comp_row['setting_name']] = $setting_row['prefix_title'];
                elseif ($comp_row['setting_name'] == 'item_frame_size'):
                    $setting_sql = "SELECT size_px FROM items_frame_size WHERE id='" . $comp_row['item_value'] . "'";
                    $setting_result = $conn->query($setting_sql);
                    $setting_row = $setting_result->fetch_assoc();
                    $row['comp'][$comp_row['setting_name']] = $setting_row['size_px'];
                else:
                    $row['comp'][$comp_row['setting_name']] = $comp_row['item_value'];
                endif;
            endwhile;
            $items[] = $row;
        }
        echo json_encode($items);
        break;
}

$conn->close();
