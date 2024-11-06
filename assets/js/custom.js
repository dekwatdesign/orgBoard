$(document).ready(function () {
    loadItems();
    loadAreaBackground();

    // Load items from the database
    function loadItems() {
        $.post('manage_items.php', { action: 'get_items' }, function (data) {
            const items = JSON.parse(data);
            $('#area').empty();

            // Loop through each item
            items.forEach(item => {
                // Use Promise to handle the asynchronous HTML generation
                convertJsonToHtml(item).then((itemHTML) => {

                    const itemElement = $(`
                        <div class="item d-flex flex-row flex-nowrap" data-id="${item.id}" style="left: ${item.x_pos}px; top: ${item.y_pos}px;">
                            ${itemHTML}
                            <div class="d-flex flex-column gap-1 ps-1">
                                <button data-type="${item.type_id}" class="btn btn-sm btn-icon btn-secondary edit-btn">✏️</button>
                                <button data-type="${item.type_id}" class="btn btn-sm btn-icon btn-secondary copy-btn">📋</button>
                                <button data-type="${item.type_id}" class="btn btn-sm btn-icon btn-secondary delete-btn">🗑️</button>
                            </div>
                        </div>
                    `);
                    // Append the item element to the area container
                    $('#area').append(itemElement);
                }).catch((error) => {
                    console.error('Error generating item HTML:', error);
                });
            });
        });
    }

    function convertJsonToHtml(data) {
        return new Promise((resolve, reject) => {

            const comp = data.comp;

            let replacements = [];
            let templateFile = '';

            if (data.type_id == 1) {
                templateFile = 'leader_card_template.temp';
                replacements = [
                    { placeholder: '{{item_id}}', value: data.id },
                    { placeholder: '{{item_pname}}', value: comp.item_pname },
                    { placeholder: '{{item_fname}}', value: comp.item_fname },
                    { placeholder: '{{item_lname}}', value: comp.item_lname },
                    { placeholder: '{{item_work_position}}', value: comp.item_work_position },
                    { placeholder: '{{item_bg}}', value: comp.item_bg },
                    { placeholder: '{{size_width}}', value: comp.size_width },
                    { placeholder: '{{size_height}}', value: comp.size_height },
                    { placeholder: '{{item_frame_size}}', value: comp.item_frame_size },
                    { placeholder: '{{item_avatar}}', value: comp.item_avatar },
                    { placeholder: '{{item_frame}}', value: comp.item_frame }
                ];
            }
            else if (data.type_id == 2) {
                templateFile = 'img_template.temp';
                replacements = [
                    { placeholder: '{{item_id}}', value: data.id },
                    { placeholder: '{{img_width}}', value: comp.img_width },
                    { placeholder: '{{img_height}}', value: comp.img_height },
                    { placeholder: '{{img_filename}}', value: comp.item_img },
                ];
            }
            else if (data.type_id == 3) {
                templateFile = 'txt_template.temp';
                replacements = [
                    { placeholder: '{{item_id}}', value: data.id },
                    { placeholder: '{{txt_text}}', value: comp.item_txt },
                    { placeholder: '{{txt_size}}', value: comp.item_txt_size },
                    { placeholder: '{{txt_weight}}', value: comp.item_txt_weight },
                    { placeholder: '{{txt_color}}', value: comp.item_txt_color },
                ];
            }

            // Load the template and populate placeholders
            $.get(`templates/${templateFile}`, function (template) {

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

    // Load area background
    function loadAreaBackground() {
        $.post('save_area.php', { action: 'get_background' }, function (data) {
            $('#area').css('background-image', `url('uploads/${data.background_url}')`);
        });
    }

    $('#editItemForm').on('submit', function (e) {
        e.preventDefault();

        const itemId = $('#editItemId').val(); // Add item ID if needed
        const itemAction = itemId != '' ? 'update_item' : 'add_item'; // Determine the action based on item ID

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
                loadItems();
                // Reload or update item display if needed
            }
        });
    });

    // Add new item
    $(document).on('click', '.add-btn', function () {
        let itemType = $(this).data('type');
        loadForm(itemType)
            .then(() => {
                $('#editItemForm').trigger('reset');

                if (itemType == 1) {
                    // Setup Modal
                    $('#editItemModal .modal-title').html('Add Card');
                    $('#editItemModal .modal-dialog').attr('class', 'modal-dialog modal-lg');
                    // Setup Form
                    $('#itemAvatarPreview').attr('src', '');
                    initIMGPreview('itemAvatar', 'itemAvatarPreview');

                    $('#itemBGPreview').attr('src', '');
                    initIMGPreview('itemBG', 'itemBGPreview');

                    $('#itemFramePreview').attr('src', '');
                    initIMGPreview('itemFrame', 'itemFramePreview');
                }
                else if (itemType == 2) {
                    // Setup Modal
                    $('#editItemModal .modal-title').html('Add Image');
                    $('#editItemModal .modal-dialog').attr('class', 'modal-dialog');
                    // Setup Form
                    $('#itemIMGPreview').attr('src', '');
                    initIMGPreview('itemIMG', 'itemIMGPreview');

                }
                else if (itemType == 3) {

                    $('#editItemModal .modal-title').html('Add Text');
                    $('#editItemModal .modal-dialog').attr('class', 'modal-dialog');

                }

                $('#editItemModal').modal('show');
            })
            .catch((error) => {
                console.error('Error loading form:', error)
            });
    });

    // Delete an item
    $(document).on('click', '.delete-btn', function () {

        const itemId = $(this).parent().parent().data('id');

        Swal.fire({
            text: `ต้องการลบหรือไม่`,
            icon: "warning",
            showCancelButton: true,
            buttonsStyling: false,
            confirmButtonText: "ตกลง",
            cancelButtonText: "ยกเลิก",
            customClass: {
                confirmButton: "btn fw-bold btn-danger",
                cancelButton: "btn fw-bold btn-active-light-primary"
            }
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: 'manage_items.php',
                    type: 'POST',
                    data: {
                        action: 'delete_item',
                        id: itemId
                    },
                    beforeSend: function () {
                        Swal.fire({
                            html: ` <div class="d-flex flex-column flex-center gap-3">
                                        <span class="fs-5 fw-bold">กำลังส่งข้อมูล</span>
                                        <div class="load">
                                            <span class="loading"></span>
                                            <span class="loading"></span>
                                            <span class="loading"></span>
                                        </div>
                                    </div>`,
                            buttonsStyling: false,
                            showConfirmButton: false,
                            allowOutsideClick: false
                        })
                    },
                    success: function (result) {
                        Swal.fire({
                            text: `ลบแล้ว !`,
                            icon: "success",
                            buttonsStyling: false,
                            showConfirmButton: false,
                            timer: 1000
                        }).then(function () {
                            loadItems();
                        });
                    }
                });

            }
        });

    });

    // Copy an item
    $(document).on('click', '.copy-btn', function () {
        const itemId = $(this).parent().parent().data('id');
        const itemType = $(this).data('type');
        $.post('manage_items.php', { action: 'copy_item', id: itemId, type_id: itemType }, function (data) {
            loadItems();
        });
    });

    // Edit an item
    $(document).on('click', '.edit-btn', function () {

        let itemType = $(this).data('type');
        loadForm(itemType)
            .then(() => {

                $('#editItemForm').trigger('reset');

                itemId = $(this).parent().parent().data('id');
                $.post('manage_items.php', { action: 'get_comp_settings', id: itemId }, function (data) {
                    const item = JSON.parse(data);
                    $('#editItemId').val(itemId);

                    console.log(item);
                    

                    if (itemType == 1) {
                        // Setup Modal
                        $('#editItemModal .modal-title').html('Edit Card');
                        $('#editItemModal .modal-dialog').attr('class', 'modal-dialog modal-lg');
                        // Setup Form
                        $('#itemTitleName').val(item.item_pname);
                        $('#itemFirstName').val(item.item_fname);
                        $('#itemLastName').val(item.item_lname);
                        $('#itemWorkPosition').val(item.item_work_position);

                        $('#itemSize').val(item.item_size); // From items
                        $('#itemFrameSize').val(item.item_frame_size);

                        $('#itemAvatarPreview').attr('src', `uploads/${item.item_avatar}`);
                        initIMGPreview('itemAvatar', 'itemAvatarPreview');

                        $('#itemBGPreview').attr('src', `uploads/${item.item_bg}`);
                        initIMGPreview('itemBG', 'itemBGPreview');

                        $('#itemFramePreview').attr('src', `uploads/${item.item_frame}`);
                        initIMGPreview('itemFrame', 'itemFramePreview');

                    }
                    else if (itemType == 2) {
                        // Setup Modal
                        $('#editItemModal .modal-title').html('Edit Image');
                        $('#editItemModal .modal-dialog').attr('class', 'modal-dialog');
                        // Setup Form
                        $('#itemIMGPreview').attr('src', `uploads/${item.item_img}`);
                        initIMGPreview('itemIMG', 'itemIMGPreview');

                        $('#itemSizeIMG').val(item.item_img_size);

                    }
                    else if (itemType == 3) {

                        $('#editItemModal .modal-title').html('Edit Text');
                        $('#editItemModal .modal-dialog').attr('class', 'modal-dialog');

                        $('#itemTXT').val(item.item_txt);
                        $('#itemTXTSize').val(item.item_txt_size);
                        $('#itemTXTWeight').val(item.item_txt_weight);
                        $('#itemTXTColor').val(item.item_txt_color);

                    }

                    $('#editItemModal').modal('show');
                });

            })
            .catch((error) => {
                console.error('Error loading form:', error)
            });
    });

    $(document).on('click', '.save-btn', function () {
        const displayArea = document.getElementById('area');
        html2canvas(displayArea).then(canvas => {
            const dataUrl = canvas.toDataURL("image/png");

            // ส่งข้อมูล PNG ไปที่ backend
            $.ajax({
                url: 'save_image.php',
                type: 'POST',
                data: {
                    imageData: dataUrl
                },
                success: function (response) {
                    alert('Image saved successfully!');
                },
                error: function (error) {
                    console.error('Error saving image:', error);
                }
            });
        }).catch(function (error) {
            console.error('Error converting display area to PNG:', error);
        });
    });

    function loadForm(itemType) {
        return new Promise((resolve, reject) => {
            $('#editItemForm').html('');
            $.post('manage_form.php', { item_type: itemType }, function (data) {
                $('#editItemForm').html(data);
                resolve(); // Resolve the Promise once data is loaded
            }).fail((error) => {
                reject(error); // Reject the Promise if there's an error
            });
        });
    }

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
