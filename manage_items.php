<?php
$servername = "localhost";
$username = "root"; // เปลี่ยนเป็นชื่อผู้ใช้ฐานข้อมูลของคุณ
$password = ""; // เปลี่ยนเป็นรหัสผ่านฐานข้อมูลของคุณ
$dbname = "image_management";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$action = $_POST['action'];

switch ($action) {
    case 'add':
        $name = $_POST['name'];
        $position = $_POST['position'];
        $image_url = $_POST['image_url'];
        $frame_url = $_POST['frame_url'];
        $sql = "INSERT INTO items (name, position, image_url, frame_url) VALUES ('$name', '$position', '$image_url', '$frame_url')";
        $conn->query($sql);
        echo $conn->insert_id;
        break;

    case 'update':
        $id = $_POST['id'];
        $name = $_POST['name'];
        $position = $_POST['position'];
        $image_url = $_POST['image_url'];
        $frame_url = $_POST['frame_url'];
        $sql = "UPDATE items SET name='$name', position='$position', image_url='$image_url', frame_url='$frame_url' WHERE id=$id";
        $conn->query($sql);
        echo "Updated";
        break;

    case 'delete':
        $id = $_POST['id'];
        $sql = "DELETE FROM items WHERE id=$id";
        $conn->query($sql);
        echo "Deleted";
        break;

    case 'update_position':
        $id = $_POST['id'];
        $x_pos = $_POST['x_pos'];
        $y_pos = $_POST['y_pos'];
        $sql = "UPDATE items SET x_pos=$x_pos, y_pos=$y_pos WHERE id=$id";
        $conn->query($sql);
        echo "Position Updated";
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
        while($row = $result->fetch_assoc()) {
            $item_id = $row['item_id'];
            $comp_result = $conn->query("SELECT * FROM items_components WHERE item_id='$item_id' ");
            while($comp_row = $comp_result->fetch_assoc()) :
                $row['comp'][] = $comp_row;
            endwhile;

            $items[] = $row;
        }
        echo json_encode($items);
        break;
}

$conn->close();
?>
