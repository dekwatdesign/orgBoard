function afterLoadItems() {
    const items = document.querySelectorAll(`${configs.orgb_selector} .item`);
    // Loop through each item and get the data-id attribute
    items.forEach(item => {
        const dataId = item.getAttribute('data-id');
        const dataType = item.getAttribute('data-type');
        console.log(`Item ID: ${dataId}, Type: ${dataType}`);

        if (dataType == 1) {
            // ใช้ setTimeout เพื่อให้แน่ใจว่า append เสร็จสมบูรณ์ก่อนเรียก fitTextToWidth
            fitTextToWidth(`itemName${dataId}`, `itemWorkPosition${dataId}`, `itemSignContainer${dataId}`);
        }
    });
}

function loadItems() {
    return new Promise((resolve, reject) => {
        $.post(`${configs.actn_dir}manage_items.php`, { action: 'get_items' }, function (data) {
            const items = JSON.parse(data);
            $(`${configs.orgb_selector}`).empty();
            const itemPromises = [];

            items.forEach(item => {
                const itemPromise = convertJsonToHtml(item).then((itemHTML) => {
                    const itemElement = $(`
                        <div class="item d-flex flex-row flex-nowrap" data-id="${item.id}" data-type="${item.type_id}" style="left: ${item.x_pos}px; top: ${item.y_pos}px;">
                            ${itemHTML}
                        </div>
                    `);
                    $(`${configs.orgb_selector}`).append(itemElement);
                }).catch((error) => {
                    console.error('Error generating item HTML:', error);
                });

                itemPromises.push(itemPromise);
            });

            Promise.all(itemPromises).then(() => {
                afterLoadItems();
                resolve();
            }).catch(reject);
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
                { placeholder: '{{orgb_dir}}', value: configs.orgb_dir },
                { placeholder: '{{sign_dir}}', value: configs.sign_dir },
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
                { placeholder: '{{orgb_dir}}', value: configs.orgb_dir },
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
        $.get(`${configs.temp_dir}${templateFile}`, function (template) {

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

function loadAreaBackground() {
    $.post(`${configs.actn_dir}save_area.php`, { action: 'get_background' }, function (data) {
        $(`${configs.orgb_selector}`).css('background-image', `url('${configs.orgb_dir}${data.background_url}')`);
    });
}

function fitTextToWidth(txt_id, txt_next_id, txt_parent_id) {

    const textElement = document.getElementById(txt_id);
    const nextElement = document.getElementById(txt_next_id);
    const parentElement = document.getElementById(txt_parent_id);

    // กำหนดขนาดเริ่มต้นของ font size สำหรับ textElement
    let fontSize = 100; // เริ่มจากขนาดใหญ่ไปก่อน
    textElement.style.fontSize = fontSize + "px";

    // ปรับขนาดตัวอักษรของ textElement ให้พอดีกับ Parent
    while (textElement.scrollWidth > parentElement.clientWidth && fontSize > 0) {
        fontSize -= 1;
        textElement.style.fontSize = fontSize + "px";
    }

    // ปรับขนาดตัวอักษรของ nextElement ให้เล็กกว่า textElement
    let nextFontSize = fontSize - 2; // ตั้งค่าให้เล็กกว่าตัวแรก
    nextElement.style.fontSize = nextFontSize + "px";

    // ลดขนาด nextElement ให้ไม่เกินขนาดของ parentElement
    while (nextElement.scrollWidth > parentElement.clientWidth && nextFontSize > 0) {
        nextFontSize -= 1;
        nextElement.style.fontSize = nextFontSize + "px";
    }
}
