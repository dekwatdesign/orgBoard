$('#editItemForm').on('submit', function (e) {
    e.preventDefault();

    const itemId = $('#editItemId').val(); // Add item ID if needed
    const itemAction = itemId != '' ? 'update_item' : 'add_item'; // Determine the action based on item ID

    const formData = new FormData(this);
    formData.append('action', itemAction);

    itemId ? formData.append('item_id', itemId) : null;

    $.ajax({
        url: `${configs.actn_dir}manage_items.php`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            $('#editItemModal').modal('hide');
            alert(response);
            loadItems().then(() => {
                $(`${configs.orgb_selector} .item`).each(function(i, obj) {
                    const item = $(this);
                    const itemId = item.data('id');
                    const itemType = item.data('type');
                    if (item.find('.item-tools').length === 0) {
                        loadItemsTools(itemId, itemType);
                    }
                });
            }).catch((error) => {
                console.error("Error loading items:", error);
            });
            // Reload or update item display if needed
        }
    });
});

$(document).on('click', '.item', function (event) {
    console.log('Clicked on item:', $(this).data('id'));

    event.stopPropagation(); // หยุดการกระจายของ event

    // ซ่อน .card-tools ที่มี class .show ทั้งหมดก่อน
    $('.item-tools.show').removeClass('show');

    // แสดง .card-tools ภายใน .item ที่คลิก
    $(this).find('.item-tools').addClass('show');
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
                url: `${configs.actn_dir}manage_items.php`,
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
                        loadItems().then(() => {
                            $(`${configs.orgb_selector} .item`).each(function(i, obj) {
                                const item = $(this);
                                const itemId = item.data('id');
                                const itemType = item.data('type');
                                if (item.find('.item-tools').length === 0) {
                                    loadItemsTools(itemId, itemType);
                                }
                            });
                        }).catch((error) => {
                            console.error("Error loading items:", error);
                        });
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
    $.post(`${configs.actn_dir}manage_items.php`, { action: 'copy_item', id: itemId, type_id: itemType }, function (data) {
        loadItems().then(() => {
            $(`${configs.orgb_selector} .item`).each(function(i, obj) {
                const item = $(this);
                const itemId = item.data('id');
                const itemType = item.data('type');
                if (item.find('.item-tools').length === 0) {
                    loadItemsTools(itemId, itemType);
                }
            });
        }).catch((error) => {
            console.error("Error loading items:", error);
        });
    });
});

// Edit an item
$(document).on('click', '.edit-btn', function () {

    let itemType = $(this).data('type');
    loadForm(itemType)
        .then(() => {

            $('#editItemForm').trigger('reset');

            itemId = $(this).parent().parent().data('id');
            $.post(`${configs.actn_dir}manage_items.php`, { action: 'get_comp_settings', id: itemId }, function (data) {
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

                    $('#itemAvatarPreview').attr('src', `${configs.orgb_dir}${item.item_avatar}`);
                    initIMGPreview('itemAvatar', 'itemAvatarPreview');

                    $('#itemBGPreview').attr('src', `${configs.orgb_dir}${item.item_bg}`);
                    initIMGPreview('itemBG', 'itemBGPreview');

                    $('#itemFramePreview').attr('src', `${configs.orgb_dir}${item.item_frame}`);
                    initIMGPreview('itemFrame', 'itemFramePreview');

                }
                else if (itemType == 2) {
                    // Setup Modal
                    $('#editItemModal .modal-title').html('Edit Image');
                    $('#editItemModal .modal-dialog').attr('class', 'modal-dialog');
                    // Setup Form
                    $('#itemIMGPreview').attr('src', `${configs.orgb_dir}${item.item_img}`);
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

$(document).on('click', function (event) {
    if (!$(event.target).closest('.item').length && !$(event.target).closest('.item-tools').length) {
        $('.item-tools.show').removeClass('show'); // ลบ class .show เพื่อซ่อน .item-tools
    }
});

// Drag and Drop functionality
$(document).on('mousedown', '.item', function () {
    $(this).draggable({
        containment: `${configs.orgb_selector}`,
        stop: function (event, ui) {
            const itemId = $(this).data('id');
            const { left, top } = ui.position;
            $.post(`${configs.actn_dir}manage_items.php`, {
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
        url: `${configs.actn_dir}save_area.php`,
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

function loadForm(itemType) {
    return new Promise((resolve, reject) => {
        $('#editItemForm').html('');
        $.post(`${configs.actn_dir}manage_form.php`, { item_type: itemType }, function (data) {
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

function loadItemsTools(item_id, type_id) {
    console.log('Loading tools for item:', item_id, type_id);
    const itemTools = `<div class="item-tools flex-column gap-1 ps-1" style="position: absolute; right: calc(-1.5em - 1.1rem - 2px - 0.25rem);">
                            <button data-type="${type_id}" class="btn btn-sm btn-icon btn-bg-light btn-color-warning edit-btn" title="Edit">
                                <i class="fs-2 text-warning fa-solid fa-pen-to-square"></i>
                            </button>
                            <button data-type="${type_id}" class="btn btn-sm btn-icon btn-bg-light btn-color-dark copy-btn" title="Duplicate">
                                <i class="fs-2 text-dark fa-solid fa-copy"></i>
                            </button>
                            <button data-type="${type_id}" class="btn btn-sm btn-icon btn-bg-light btn-color-danger delete-btn" title="Delete">
                                <i class="fs-2 text-danger fa-solid fa-trash"></i>
                            </button>
                        </div>`;
    $(`.item[data-id=${item_id}]`).append(itemTools);
}
