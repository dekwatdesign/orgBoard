$(document).ready(function () {
    loadItems();
    loadAreaBackground();

    // Load items from the database
    function loadItems() {
        $.post('manage_items.php', { action: 'get_items' }, function (data) {
            const items = JSON.parse(data);
            $('#area').empty();
            items.forEach(item => {
                const itemElement = $(`
                    <div class="item" data-id="${item.id}" style="left: ${item.x_pos}px; top: ${item.y_pos}px;">
                        <img src="${item.image_url}" style="border-image: url(${item.frame_url}) 30 / 10px;">
                        <button class="edit-btn">✏️</button>
                        <button class="copy-btn">📋</button>
                        <button class="delete-btn">🗑️</button>
                    </div>
                `);
                $('#area').append(itemElement);
            });
        });
    }

    // Load area background
    function loadAreaBackground() {
        $.post('save_area.php', {}, function (data) {
            $('#area').css('background-image', `url(${data.background_url})`);
        });
    }

    // Add new item
    $('#addItemBtn').on('click', function () {
        $('#editItemForm').trigger('reset');
        $('#editItemModal').modal('show');
    });

    // $('#editItemForm').on('submit', function (e) {
    //     e.preventDefault();
    //     const formData = {
    //         action: 'add',
    //         name: $('#itemName').val(),
    //         position: $('#itemPosition').val(),
    //         image_url: $('#itemImage').val(),
    //         frame_url: $('#itemFrame').val()
    //     };
    //     $.post('manage_items.php', formData, function () {
    //         $('#editItemModal').modal('hide');
    //         loadItems();
    //     });
    // });

    $('#editItemForm').on('submit', function(e) {
        e.preventDefault();
    
        const formData = new FormData(this);
        formData.append('action', 'update_item');
        formData.append('item_id', itemId); // Add item ID if needed
    
        $.ajax({
            url: 'save_item.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#editItemModal').modal('hide');
                alert(response);
                // Reload or update item display if needed
            }
        });
    });

    // Delete an item
    $(document).on('click', '.delete-btn', function () {
        const itemId = $(this).parent().data('id');
        $.post('manage_items.php', { action: 'delete', id: itemId }, function () {
            loadItems();
        });
    });

    // Edit an item
    $(document).on('click', '.edit-btn', function () {
        const itemId = $(this).parent().data('id');
        $.post('manage_items.php', { action: 'get_items' }, function (data) {
            const items = JSON.parse(data);
            const item = items.find(i => i.id == itemId);
            $('#editItemId').val(item.id);
            $('#itemName').val(item.name);
            $('#itemPosition').val(item.position);
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

    // Open the modal when the Edit Area button is clicked
    $('#editAreaBtn').on('click', function () {
        $('#editAreaModal').modal('show');
    });

    // Handle the form submission to update the background URL
    $('#editAreaForm').on('submit', function (e) {
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
