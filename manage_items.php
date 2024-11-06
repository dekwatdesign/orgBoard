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

        // IMG ===================
        $img_file = null;
        if (isset($_FILES['itemIMG']) && $_FILES['itemIMG']['error'] == 0):
            $img_file_ext = pathinfo($_FILES['itemIMG']['name'], PATHINFO_EXTENSION);
            $img_file_name_new = uniqid('img_' . date('YmdHis'), true) . '.' . $img_file_ext;
            $img_upload_path = $uploadDir . $img_file_name_new;

            if (move_uploaded_file($_FILES['itemIMG']['tmp_name'], $img_upload_path)) {
                $img_file = $img_file_name_new;
            }
        endif;

        $itemName = 'item_' . date('YmdHis');
        $itemType = $_POST['itemType'];

        $sql = "INSERT INTO items ( `name`, type_id, x_pos, y_pos ) VALUES ( '$itemName', '$itemType', 0, 0 )";
        $conn->query($sql);
        $items_last_id = $conn->insert_id;

        $component_set = [];

        if ($itemType == 1):
            $component_set = [
                1 => $_POST['itemTitleName'],
                2 => $_POST['itemFirstName'],
                3 => $_POST['itemLastName'],
                4 => $_POST['itemWorkPosition'],
                5 => $avatar_file,
                6 => $frame_file,
                7 => $_POST['itemFrameSize'],
                8 => $bg_file,
                9 => $_POST['itemSize'],
            ];
        elseif ($itemType == 2):
            $component_set = [
                10 => $img_file,
                11 => $_POST['itemSizeIMG'],
            ];
        elseif ($itemType == 3):
            $component_set = [
                12 => $_POST['itemTXT'],
                13 => $_POST['itemTXTSize'],
                14 => $_POST['itemTXTWeight'],
                15 => $_POST['itemTXTColor'],
            ];
        endif;

        if (count($component_set) > 0):
            foreach ($component_set as $comp_id => $comp_value):
                $ins_arr = [
                    'item_id' => $items_last_id,
                    'item_setting_id' => $comp_id,
                    'item_value' => $comp_value
                ];
                $sql = generateSQLInsert('items_components', $ins_arr);
                $conn->query($sql);
            endforeach;
        endif;

        echo "Added";
        break;

    case 'copy_item':

        $item_id = $_POST['id'];
        $type_id = $_POST['type_id'];

        $item_info_sql = "SELECT * FROM items WHERE id='$item_id'";
        $item_info_result = $conn->query($item_info_sql);
        $item_info_row = $item_info_result->fetch_assoc();

        $itemName = 'item_' . date('YmdHis');
        $itemSize = $item_info_row['item_sizes_id'];

        $sql = "INSERT INTO items ( `name`, type_id, x_pos, y_pos ) VALUES ( '$itemName', '$type_id', 0, 0 )";
        $conn->query($sql);
        $item_last_id = $conn->insert_id;

        $component_set = [];

        $item_comp_sql = "SELECT * FROM items_components WHERE item_id='$item_id'";
        $item_comp_result = $conn->query($item_comp_sql);
        while ($item_comp_row = $item_comp_result->fetch_assoc()) {
            $component_set[$item_comp_row['item_setting_id']] = $item_comp_row['item_value'];
        }

        // Avatar ===================
        if (file_exists($uploadDir . $component_set[5]) && strlen($component_set[5]) > 0) {
            $avatar_file_ext = pathinfo($uploadDir . $component_set[5], PATHINFO_EXTENSION);
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

        // IMG ===================
        if (file_exists($uploadDir . $component_set[10]) && strlen($component_set[10]) > 0) {
            $img_file_ext = pathinfo($uploadDir . $component_set[10], PATHINFO_EXTENSION);
            $img_file_name_new = uniqid('img_' . date('YmdHis'), true) . '.' . $img_file_ext;
            $img_upload_path = $uploadDir . $img_file_name_new;

            if (copy($uploadDir . $component_set[10], $img_upload_path)) {
                $component_set[10] = $img_file_name_new;
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

        // IMG ===================
        $img_sql = " SELECT
                        B.item_value 
                    FROM
                        items AS A
                        INNER JOIN items_components AS B ON A.id = B.item_id 
                    WHERE
                        A.id = '$item_id' AND
                        B.item_setting_id = 10";
        $img_query = $conn->query($img_sql);
        $img_row = $img_query->fetch_assoc();
        $img_file = $img_row['item_value'];

        if (isset($_FILES['itemIMG']) && $_FILES['itemIMG']['error'] == 0):
            if (file_exists($uploadDir . $img_file) && strlen($img_file) > 0) {
                unlink($uploadDir . $img_file);
            }
            $img_file_ext = pathinfo($_FILES['itemIMG']['name'], PATHINFO_EXTENSION);
            $img_file_name_new = uniqid('img_' . date('YmdHis'), true) . '.' . $img_file_ext;
            $img_upload_path = $uploadDir . $img_file_name_new;

            if (move_uploaded_file($_FILES['itemIMG']['tmp_name'], $img_upload_path)) {
                $img_file = $img_file_name_new;
            }
        endif;

        $itemType = $_POST['itemType'];
        $component_set = [];

        if ($itemType == 1):
            $component_set = [
                1 => $_POST['itemTitleName'],
                2 => $_POST['itemFirstName'],
                3 => $_POST['itemLastName'],
                4 => $_POST['itemWorkPosition'],
                5 => $avatar_file,
                6 => $frame_file,
                7 => $_POST['itemFrameSize'],
                8 => $bg_file,
                9 => $_POST['itemSize'],
            ];
        elseif ($itemType == 2):
            $component_set = [
                10 => $img_file,
                11 => $_POST['itemSizeIMG'],
            ];
        elseif ($itemType == 3):
            $component_set = [
                12 => $_POST['itemTXT'],
                13 => $_POST['itemTXTSize'],
                14 => $_POST['itemTXTWeight'],
                15 => $_POST['itemTXTColor'],
            ];
        endif;

        if (count($component_set) > 0):

            $sql = [];

            foreach ($component_set as $comp_id => $comp_value):
                $sql[] = "UPDATE items_components SET item_value='$comp_value' WHERE item_id='$item_id' AND item_setting_id=$comp_id";
            endforeach;

            if (count($sql) > 0):
                $sql = implode(';', $sql);
                $conn->multi_query($sql);
            endif;

        endif;

        echo "Updated";
        break;

    case 'delete_item':
        $id = $_POST['id'];
        $sql = "DELETE FROM items_components WHERE item_id=$id";
        if ($conn->query($sql) === TRUE) {
            $sql_m = "DELETE FROM items WHERE id=$id";
            $conn->query($sql_m);
            echo "Deleted";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
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

        $sql = "SELECT * FROM items";
        $result = $conn->query($sql);
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $item_id = $row['id'];
            $type_id = $row['type_id'];
            $comp_sql = "   SELECT
                                B.setting_name,
                                A.item_value 
                            FROM
                                items_components AS A
                                INNER JOIN items_settings AS B ON A.item_setting_id = B.id
                            WHERE
                                A.item_id='$item_id' ;";
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

                elseif ($comp_row['setting_name'] == 'item_size'):
                    $setting_sql = "SELECT size_width, size_height FROM items_sizes WHERE id='" . $comp_row['item_value'] . "'";
                    $setting_result = $conn->query($setting_sql);
                    $setting_row = $setting_result->fetch_assoc();
                    $row['comp']['size_width'] = $setting_row['size_width'];
                    $row['comp']['size_height'] = $setting_row['size_height'];

                elseif ($comp_row['setting_name'] == 'item_img_size'):
                    $setting_sql = "SELECT size_width, size_height FROM global_sizes WHERE id='" . $comp_row['item_value'] . "'";
                    $setting_result = $conn->query($setting_sql);
                    $setting_row = $setting_result->fetch_assoc();
                    $row['comp']['img_width'] = $setting_row['size_width'];
                    $row['comp']['img_height'] = $setting_row['size_width'];

                elseif ($comp_row['setting_name'] == 'item_txt_size'):
                    $setting_sql = "SELECT size_px FROM font_sizes WHERE id='" . $comp_row['item_value'] . "'";
                    $setting_result = $conn->query($setting_sql);
                    $setting_row = $setting_result->fetch_assoc();
                    $row['comp'][$comp_row['setting_name']] = $setting_row['size_px'];

                elseif ($comp_row['setting_name'] == 'item_txt_weight'):
                    $setting_sql = "SELECT weight_value FROM font_weights WHERE id='" . $comp_row['item_value'] . "'";
                    $setting_result = $conn->query($setting_sql);
                    $setting_row = $setting_result->fetch_assoc();
                    $row['comp'][$comp_row['setting_name']] = $setting_row['weight_value'];

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
