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
                    <div class="item d-flex flex-row flex-nowrap" data-id="${item.id}" style="left: ${item.x_pos}px; top: ${item.y_pos}px;">
                        ${itemHTML}
                        <div class="d-flex flex-column gap-1">
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

        // Construct the HTML string
        const htmlString = `
            <div style="position: relative; width: ${data.size_width}px; height: ${data.size_height}px;" class="card-org" >
                <div style="position: absolute; width: ${data.size_width}px; height: ${data.size_height}px; background-size: contain; border-image: url('uploads/${compArr['frameIMG']}') 500 / ${data.size_height}px;"></div>
                <div style="position: absolute; width: ${data.size_width}px; height: ${data.size_height}px; background-size: contain; background-image: url('uploads/${compArr['leaderIMG']}'); background-position: center;"></div>
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
            url: 'save_item.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#editItemModal').modal('hide');
                alert(response);
                // Reload or update item display if needed
            }
        });
    });

    // Delete an item
    $(document).on('click', '.delete-btn', function () {
        console.log('item deleted');
        const itemId = $(this).parent().data('id');
        $.post('manage_items.php', { action: 'delete', id: itemId }, function () {
            loadItems();
        });
    });

    // Edit an item
    $(document).on('click', '.edit-btn', function () {
        console.log('item edited');
        itemId = $(this).parent().data('id'); // Assign value to itemId
        $.post('manage_items.php', { action: 'get_items' }, function (data) {
            const items = JSON.parse(data);
            const item = items.find(i => i.id == itemId);
            $('#editItemId').val(item.id);
            $('#itemName').val(item.name);
            $('#itemImage').val(item.image_url);
            $('#itemFrame').val(item.frame_url);
            $('#editItemModal').modal('show');
        });
    });

    // Drag and Drop functionality
    $(document).on('mousedown', '.item', function () {
        $(this).draggable({
            containment: '#area',
            stop: function (event, ui) {
                const itemId = $(this).data('id');
                const { left, top } = ui.position;
                $.post('manage_items.php', {
                    action: 'update_position',
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
