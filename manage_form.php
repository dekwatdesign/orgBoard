<?php
require_once 'configs/database.php';
require_once 'includes/functions.php';

$list_s_area = [];
$list_s_area_sql = "SELECT * FROM area_sizes";
$list_s_area_query = mysqli_query($conn, $list_s_area_sql);
while ($list_s_area_row = mysqli_fetch_assoc($list_s_area_query)) {
    $list_s_area[] = $list_s_area_row;
}

$list_s_item = [];
$list_s_item_sql = "SELECT * FROM items_sizes";
$list_s_item_query = mysqli_query($conn, $list_s_item_sql);
while ($list_s_item_row = mysqli_fetch_assoc($list_s_item_query)) {
    $list_s_item[] = $list_s_item_row;
}

$list_s_frame = [];
$list_s_frame_sql = "SELECT * FROM items_frame_size";
$list_s_frame_query = mysqli_query($conn, $list_s_frame_sql);
while ($list_s_frame_row = mysqli_fetch_assoc($list_s_frame_query)) {
    $list_s_frame[] = $list_s_frame_row;
}

$list_s_gbl = [];
$list_s_gbl_sql = "SELECT * FROM global_sizes";
$list_s_gbl_query = mysqli_query($conn, $list_s_gbl_sql);
while ($list_s_gbl_row = mysqli_fetch_assoc($list_s_gbl_query)) {
    $list_s_gbl[] = $list_s_gbl_row;
}

$list_s_fs = [];
$list_s_fs_sql = "SELECT * FROM font_sizes";
$list_s_fs_query = mysqli_query($conn, $list_s_fs_sql);
while ($list_s_fs_row = mysqli_fetch_assoc($list_s_fs_query)) {
    $list_s_fs[] = $list_s_fs_row;
}

$list_s_fw = [];
$list_s_fw_sql = "SELECT * FROM font_weights";
$list_s_fw_query = mysqli_query($conn, $list_s_fw_sql);
while ($list_s_fw_row = mysqli_fetch_assoc($list_s_fw_query)) {
    $list_s_fw[] = $list_s_fw_row;
}

$list_pname = [];
$list_pname_sql = "SELECT * FROM prefix_name";
$list_pname_query = mysqli_query($conn, $list_pname_sql);
while ($list_pname_row = mysqli_fetch_assoc($list_pname_query)) {
    $list_pname[] = $list_pname_row;
}


$item_type = $_POST['item_type'];

switch ($item_type) {
    case '1': ?>
        <input type="hidden" id="editItemId" name="editItemId">
        <input type="hidden" id="itemType" name="itemType" value="1" />

        <div class="d-flex flex-row flex-wrap gap-3 mb-3">
            <div class="col-lg col-12">
                <div class="card d-flex flex-row flex-wrap gap-3 h-100 shadow-sm">
                    <div class="card-body">
                        <h2>Info</h2>
                        <div class="mb-3">
                            <label for="itemAvatar" class="form-label required">Avatar Image</label>
                            <div class="d-flex flex-row gap-2 mb-2">
                                <span class="mt-4 text-gray-500">Preview:</span>
                                <img id="itemAvatarPreview" src="" alt="Avatar Preview"
                                    class="img-thumbnail mt-2 w-100px h-100px" style="object-fit: contain;">
                            </div>
                            <input type="file" class="form-control" accept="image/*" id="itemAvatar" name="itemAvatar">
                        </div>
                        <div class="mb-3">
                            <label for="itemTitleName" class="form-label required">Title Name</label>
                            <select id="itemTitleName" name="itemTitleName" class="form-select" required>
                                <?php foreach ($list_pname as $pname): ?>
                                    <option value="<?php echo $pname['id'] ?>"><?php echo $pname['prefix_title'] ?></option>
                                <?php endforeach; ?>
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
                    </div>
                </div>
            </div>
            <div class="col-lg col-12">
                <div class="card d-flex flex-row flex-wrap gap-3 h-100 shadow-sm">
                    <div class="card-body">
                        <h2>Preference</h2>
                        <div class="mb-3">
                            <label for="itemSize" class="form-label required">Item Size</label>
                            <select id="itemSize" name="itemSize" class="form-select" required>
                                <?php foreach ($list_s_item as $s_item): ?>
                                    <option value="<?php echo $s_item['id'] ?>"><?php echo $s_item['size_title'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="itemBG" class="form-label required">Background Image</label>
                            <div class="d-flex flex-row gap-2 mb-2">
                                <span class="mt-4 text-gray-500">Preview:</span>
                                <img id="itemBGPreview" src="" alt="BG Preview" class="img-thumbnail mt-2 w-100px h-100px"
                                    style="object-fit: contain;">
                            </div>
                            <input type="file" class="form-control" accept="image/*" id="itemBG" name="itemBG">
                        </div>
                        <div class="mb-3">
                            <label for="itemFrame" class="form-label required">Frame Image</label>
                            <div class="d-flex flex-row gap-2 mb-2">
                                <span class="mt-4 text-gray-500">Preview:</span>
                                <img id="itemFramePreview" src="" alt="Frame Preview" class="img-thumbnail mt-2 w-100px h-100px"
                                    style="object-fit: contain;">
                            </div>
                            <input type="file" class="form-control" accept="image/*" id="itemFrame" name="itemFrame">
                        </div>
                        <div class="mb-3">
                            <label for="itemFrameSize" class="form-label required">Frame Size</label>
                            <select id="itemFrameSize" name="itemFrameSize" class="form-select" required>
                                <?php foreach ($list_s_frame as $s_frame): ?>
                                    <option value="<?php echo $s_frame['id'] ?>"><?php echo $s_frame['size_title'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="d-flex flex-center">
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>

        <?php
        break;

    case '2': ?>
        <input type="hidden" id="editItemId" name="editItemId">
        <input type="hidden" id="itemType" name="itemType" value="2" />

        <div class="d-flex flex-row flex-wrap gap-3 mb-3">
            <div class="col">
                <div class="card d-flex flex-row flex-wrap gap-3 h-100 shadow-sm">
                    <div class="card-body">
                        <h2>Data</h2>
                        <div class="mb-3">
                            <label for="itemIMG" class="form-label required">Image</label>
                            <div class="d-flex flex-row gap-2 mb-2">
                                <span class="mt-4 text-gray-500">Preview:</span>
                                <img id="itemIMGPreview" src="" alt="Image Preview" class="img-thumbnail mt-2 w-100px h-100px"
                                    style="object-fit: contain;">
                            </div>
                            <input type="file" class="form-control" accept="image/*" id="itemIMG" name="itemIMG">
                        </div>
                        <div class="mb-3">
                            <label for="itemSizeIMG" class="form-label required">Size</label>
                            <select id="itemSizeIMG" name="itemSizeIMG" class="form-select" required>
                                <?php foreach ($list_s_gbl as $s_gbl): ?>
                                    <option value="<?php echo $s_gbl['id'] ?>"><?php echo $s_gbl['size_title'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-center">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
        <?php
        break;
    case '3': ?>
        <input type="hidden" id="editItemId" name="editItemId">
        <input type="hidden" id="itemType" name="itemType" value="3" />

        <div class="d-flex flex-row flex-wrap gap-3 mb-3">
            <div class="col">
                <div class="card d-flex flex-row flex-wrap gap-3 h-100 shadow-sm">
                    <div class="card-body">
                        <h2>Data</h2>
                        <div class="mb-3">
                            <label for="itemTXT" class="form-label required">Text</label>
                            <input type="text" class="form-control" id="itemTXT" name="itemTXT" required>
                        </div>
                        <div class="mb-3">
                            <label for="itemTXTSize" class="form-label required">Size</label>
                            <select id="itemTXTSize" name="itemTXTSize" class="form-select" required>
                                <?php foreach ($list_s_fs as $s_fs): ?>
                                    <option value="<?php echo $s_fs['id'] ?>"><?php echo $s_fs['size_title'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="itemTXTWeight" class="form-label required">Weight</label>
                            <select id="itemTXTWeight" name="itemTXTWeight" class="form-select" required>
                                <?php foreach ($list_s_fw as $s_fw): ?>
                                    <option value="<?php echo $s_fw['id'] ?>"><?php echo $s_fw['weight_title'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="itemTXTColor" class="form-label required">Color</label>
                            <input type="color" class="form-control form-control-color" id="itemTXTColor" name="itemTXTColor" value="#563d7c" title="Choose your color" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-center">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
        <?php
        break;
}

$conn->close();
