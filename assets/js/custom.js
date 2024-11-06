$(document).ready(function () {
    loadItems();
    loadAreaBackground();

    // Load items from the database
    function loadItems() {
        $.post('manage_items.php', { action: 'get_items' }, function (data) {
            const items = JSON.parse(data);
            $('#area').empty();
            items.forEach(item => {
                const itemHTML = convertJsonToHtml(item);
                const itemElement = $(`
                    <div class="item d-flex flex-row flex-nowrap" data-id="${item.item_id}" style="left: ${item.x_pos}px; top: ${item.y_pos}px;">
                        ${itemHTML}
                        <div class="d-flex flex-column gap-1 ps-1">
                            <button class="btn btn-sm btn-icon btn-secondary edit-btn">✏️</button>
                            <button class="btn btn-sm btn-icon btn-secondary copy-btn">📋</button>
                            <button class="btn btn-sm btn-icon btn-secondary delete-btn">🗑️</button>
                        </div>
                    </div>
                `);
                $('#area').append(itemElement);
            });
        });
    }

    function convertJsonToHtml(data) {
        

        // Find image and frame URLs from the "comp" array
        let compArr = [];

        $.each(data.comp, function (index, comp) {
            if (comp.item_setting_id == 5) {
                compArr['leaderIMG'] = comp.item_value;
            } else if (comp.item_setting_id == 6) {
                compArr['frameIMG'] = comp.item_value;
            }
        });

        const frame_size = 10;

        // Construct the HTML string
        const htmlString = `
            <style>
                .item-leader-${data.item_id} {
                    background-color: #FFFFFF;
                    width: ${data.size_width}px;
                    height: calc(${data.size_height}px + 30px);
                    position: relative;
                }
                .item-leader-${data.item_id}::before {
                    content: ' ';
                    position: absolute;
                    border-image-source: url('uploads/Frame_04.svg');
                    border-image-slice: 70;
                    border-image-repeat: stretch;
                    border-style: solid;
                    border-width: ${frame_size}px;
                    width: ${data.size_width}px;
                    height: calc(${data.size_height}px + 30px);
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    gap: 10px;
                    z-index: 2;
                }
                .item-avatar-${data.item_id} {
                    position: absolute; 
                    top: ${frame_size}px;
                    left: ${frame_size}px;
                    width: calc(${data.size_width}px - ${frame_size}px - ${frame_size}px);
                    height: calc(${data.size_height}px - ${frame_size}px - ${frame_size}px);
                    background-repeat: no-repeat;
                    background-size: contain;
                    border-image: url('uploads/${compArr['frameIMG']}') 500 / ${data.size_height}px;
                    z-index: 1;
                }
                .item-frame-${data.item_id} {
                    position: absolute; 
                    top: ${frame_size}px;
                    left: ${frame_size}px;
                    width: calc(${data.size_width}px - ${frame_size}px - ${frame_size}px);
                    height: calc(${data.size_height}px - ${frame_size}px - ${frame_size}px);
                    background-repeat: no-repeat; 
                    background-size: contain; 
                    background-image: url('uploads/${compArr['leaderIMG']}');
                    background-position: center;
                    z-index: 1;
                }
            </style>
            <div class="card-org item-leader-${data.item_id}">
                <div class="item-avatar-${data.item_id}"></div>
                <div class="item-frame-${data.item_id}"></div>
            </div>
        `;

        return htmlString;
    }

    // Load area background
    function loadAreaBackground() {
        $.post('save_area.php', { action: 'get_background' }, function (data) {
            $('#area').css('background-image', `url('uploads/${data.background_url}')`);
        });
    }

    // Add new item
    $('#addItemBtn').on('click', function () {
        $('#editItemForm').trigger('reset');
        $('#editItemModal').modal('show');
        $('#editItemId').val(null);

        $('#itemAvatar').val(null);
        $('#itemAvatarPreview').attr('src', '');
        
        $('#itemFrame').val(null);
        $('#itemFramePreview').attr('src', '');

    });

    $('#editItemForm').on('submit', function (e) {
        e.preventDefault();

        console.log('item submitted');

        const itemId = $('#editItemId').val(); // Add item ID if needed
        const itemAction = itemId ? 'update_item' : 'add_item';

        const formData = new FormData(this);
        formData.append('action', itemAction);

        itemId ? formData.append('item_id', itemId) : null;

        $.ajax({
            url: 'manage_items.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#editItemModal').modal('hide');
                alert(response);
                location.reload();
                // Reload or update item display if needed
            }
        });
    });

    // Delete an item
    $(document).on('click', '.delete-btn', function () {
        console.log('item deleted');
        const itemId = $(this).parent().data('id');
        $.post('manage_items.php', { action: 'delete_item', id: itemId }, function () {
            loadItems();
        });
    });

    // Edit an item
    $(document).on('click', '.edit-btn', function () {

        $('#itemAvatar').val(null);
        $('#itemAvatarPreview').attr('src', '');
        $('#itemFrame').val(null);
        $('#itemFramePreview').attr('src', '');

        itemId = $(this).parent().parent().data('id'); // Assign value to itemId
        $.post('manage_items.php', { action: 'get_comp_settings', id: itemId }, function (data) {
            const item = JSON.parse(data);
            $('#editItemId').val(itemId);
            $('#itemTitleName').val(item.item_pname);
            $('#itemFirstName').val(item.item_fname);
            $('#itemLastName').val(item.item_lname);
            $('#itemWorkPosition').val(item.item_work_position);

            $('#itemAvatarPreview').attr('src', `./uploads/${item.item_avatar}`);
            initIMGPreview('itemAvatar','itemAvatarPreview');
            
            $('#itemFramePreview').attr('src', `./uploads/${item.item_frame}`);
            initIMGPreview('itemFrame','itemFramePreview');

            $('#editItemModal').modal('show');
        });
    });

    function initIMGPreview(input_id, preview_id) {
        $(`#${input_id}`).on('change', function (event) {
            const file = event.target.files[0];

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    $(`#${preview_id}`).attr('src', e.target.result);
                }

                reader.readAsDataURL(file);
            } else {
                $(`#${preview_id}`).attr('src', '');
                alert("Please select a valid image file.");
            }
        });
    }

    // Drag and Drop functionality
    $(document).on('mousedown', '.item', function () {
        $(this).draggable({
            containment: '#area',
            stop: function (event, ui) {
                const itemId = $(this).data('id');
                const { left, top } = ui.position;
                $.post('manage_items.php', {
                    action: 'update_item_position',
                    id: itemId,
                    x_pos: left,
                    y_pos: top
                });
            }
        });
    });

    // =============== Area ===============

    // Open the modal when the Edit Area button is clicked
    $('#editAreaBtn').on('click', function () {
        $('#editAreaModal').modal('show');
    });

    // Handle the form submission to update the background URL
    $('#editAreaForm').on('submit', function (e) {
        console.log('area edited');
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('action', 'update_background');

        $.ajax({
            url: 'save_area.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#editAreaModal').modal('hide');
                alert(response);
                loadAreaBackground();
            }
        });
    });

});
