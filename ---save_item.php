<?php
require_once 'configs/database.php';

// Check if it's an update or create action
$action = $_POST['action'] ?? null;
$item_id = $_POST['item_id'] ?? null;

$name = $_POST['name'] ?? '';
$position = $_POST['position'] ?? '';
$x_pos = $_POST['x_pos'] ?? 0;
$y_pos = $_POST['y_pos'] ?? 0;

$imagePath = null;
$framePath = null;

// Upload image if provided
if (isset($_FILES['itemAvatar']) && $_FILES['itemAvatar']['error'] == 0) {
    $target_dir = "uploads/";
    $imagePath = $target_dir . basename($_FILES["itemAvatar"]["name"]);
    move_uploaded_file($_FILES["itemAvatar"]["tmp_name"], $imagePath);
}

// Upload frame if provided
if (isset($_FILES['itemFrame']) && $_FILES['itemFrame']['error'] == 0) {
    $target_dir = "uploads/";
    $framePath = $target_dir . basename($_FILES["itemFrame"]["name"]);
    move_uploaded_file($_FILES["itemFrame"]["tmp_name"], $framePath);
}

if ($action === 'update_item' && $item_id) {
    // Update existing item
    $sql = "UPDATE items SET name=?, position=?, x_pos=?, y_pos=?";
    $params = [$name, $position, $x_pos, $y_pos];

    // Only update image if a new one was uploaded
    if ($imagePath) {
        $sql .= ", image_url=?";
        $params[] = $imagePath;
    }

    // Only update frame if a new one was uploaded
    if ($framePath) {
        $sql .= ", frame_url=?";
        $params[] = $framePath;
    }

    $sql .= " WHERE id=?";
    $params[] = $item_id;

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);

    if ($stmt->execute()) {
        echo "Item updated successfully!";
    } else {
        echo "Error updating item: " . $stmt->error;
    }
    $stmt->close();

} else {

    // Insert new item
    $sql = "INSERT INTO items (name, position, x_pos, y_pos, image_url, frame_url) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddss", $name, $position, $x_pos, $y_pos, $imagePath, $framePath);

    if ($stmt->execute()) {
        echo "Item created successfully!";
    } else {
        echo "Error creating item: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
