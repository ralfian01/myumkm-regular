<div class="content_box" style="max-width: 500px;">
    <div class="box_head">
        <div class="head_title">
            <div class="title">
                Tambah Kategori
            </div>
        </div>
    </div>

    <div class="form_target box_body">

        <div class="tx_field1 wd100pc">
            <div class="input_label">
                <label for="name">
                    Nama Kategori
                </label>
            </div>

            <div class="input_item">
                <input type="text" name="name" id="name" placeholder="Nama kategori produk" value="">
            </div>
        </div>

        <input type="hidden" name="slug" id="slug" value="">

        <div class="tx_field1 mt1c5">
            <div class="input_label">
                <label for="description">
                    Deskripsi singkat
                </label>
            </div>

            <div class="input_item">
                <textarea id="description" name="description" placeholder="Tulis deskripsi singkat" style="min-height: 100px;" maxlength="500"></textarea>
            </div>
        </div>

        <div class="tx_field1 mt1c5">
            <div class="input_label">
                <label>
                    Foto Katalog
                </label>
            </div>

            <div class="input_file_item" id="testung">

                <div class="input_instruction">
                    <div class="icon_instruction">
                        <i class="main ri-image-2-fill"></i>
                        <i class="drag ri-upload-2-fill"></i>
                    </div>

                    <div class="main_instruction">

                        <label for="file" class="input_trigger">
                            Pilih file
                        </label>
                        atau drag dan drop disini
                    </div>

                    <div class="sub_instruction">
                        PNG, JPG, JPEG up to 5MB
                    </div>
                </div>

                <div class="file_preview">
                    <!--  -->
                </div>

                <input id="file" name="thumbnail" type="file" accept="image/*" required />
            </div>

            <div class="notif_text">
                Foto wajib diisi
            </div>
        </div>
        <script type="text/javascript">
            // 
            let drgCount = 0;
            let tempFileList = [];

            $('body').find('*[class*="tx_field"]')
                .find('.input_file_item').on('dragenter', function() {

                    $(this)
                        .attr('ptx_ondrag', '')
                        .find('input[type="file"]')
                        .css({
                            zIndex: 2
                        });

                    drgCount++;
                }).on('drop dragleave', function() {

                    drgCount--;

                    if (drgCount === 0) {

                        $(this)
                            .removeAttr('ptx_ondrag')
                            .find('input[type="file"]')
                            .css({
                                zIndex: 0
                            });
                    }
                })
                .on('change', 'input[type="file"]', function(elem) {

                    let inputParent = $(this).parents('.input_file_item')[0],
                        previewParent = $(inputParent).find('.file_preview');

                    // ### Modify input[type="file"] FileList
                    const modFileList = () => {

                        // Combine temporary FileList and active input[type="file"] FileList
                        if (elem.target.files.length >= 0) {

                            if (tempFileList.length >= 1) {

                                let newList = new DataTransfer(),
                                    inputedJson = [];

                                // Input FileList from input[type="file"] to new List
                                Array.from(elem.target.files).forEach((data) => {

                                    inputedJson.push(JSON.stringify({
                                        modified: data['lastModified'],
                                        size: data['size'],
                                        name: data['name']
                                    }));

                                    newList.items.add(data);
                                });

                                // Input FileList from temporary FileList to new List
                                Array.from(tempFileList).forEach((data) => {

                                    let jsonString = JSON.stringify({
                                        modified: data['lastModified'],
                                        size: data['size'],
                                        name: data['name']
                                    });

                                    // Only input data that does not exist
                                    if (inputedJson.indexOf(jsonString) < 0) {

                                        newList.items.add(data);
                                    }
                                });

                                return elem.target.files = newList.files;
                            }
                        }

                        if (tempFileList.length >= 1)
                            return elem.target.files = tempFileList;
                    };

                    // If multiple file is allowed
                    if ($(this).attr('multiple'))
                        modFileList();


                    // ### Show selected file with HTML element
                    const showElem = () => {

                        let appendElement = '';
                        $.each(elem.target.files, (key, data) => {

                            let url = URL.createObjectURL(data);

                            appendElement += `
                                <div class="preview_list">
                                    <div class="file_info">
                                        <div class="thumb">
                                            <img src="${url}">
                                        </div>

                                        <div class="title">
                                            <span>
                                                ${data['name'].slice(0, -5)}
                                            </span>
                                            ${data['name'].slice(-5)}
                                        </div>
                                    </div>

                                    <div class="action">
                                        <button class="remove" data-id="${key}">
                                            <i class="ri-close-line"></i>
                                        </button>
                                    </div>
                                </div>
                            `;
                        });

                        $(previewParent).html(appendElement);
                    }

                    showElem();

                    tempFileList = elem.target.files;
                }).on('click', '.file_preview .remove', function() {

                    let inputParent = $(this).parents('.input_file_item')[0],
                        listParent = $(this).parents('.preview_list'),
                        inputFile = $(inputParent).find('input[type="file"]')[0],
                        elem = this,
                        id = $(this).attr('id');

                    // Remove element
                    $(listParent).remove();

                    // ### Remove data from input[type="file"]
                    // Duplicate array
                    let fileArray = Array.from(inputFile.files);
                    // Remove selected key
                    fileArray.splice(id, 1);

                    // Remake File object with FormData
                    let listFile = new DataTransfer();
                    fileArray.forEach((data) => {

                        listFile.items.add(data);
                    });

                    // Override Object File in input[type="file"]
                    inputFile.files = listFile.files;
                    tempFileList = inputFile.files;
                }).end();
        </script>

        <button class="submit button1 mt1c5">
            Simpan
        </button>
    </div>
    <script type="text/javascript">
        // 
        $('body')
            .on('input', 'input[name="name"]', function() {

                let value = $(this).val(),
                    slug = value.toLowerCase().replaceAll(/[^a-zA-z0-9_ ]/g, "").replaceAll(/\s\s+/g, " ").replaceAll(" ", '-'),
                    form = $(this).parents('.form_target')[0];

                $(form).find('input[name="slug"]').val(slug);
            })
            .on('click', '.form_target .submit', function() {

                let formTarget = $(this).parents('.form_target')[0],
                    btn = this;

                $(formTarget)
                    .find('*[class*="tx_field"], *[class*="qty_field"]')
                    .attr('ptx_validation', '')

                $.formCollect
                    .target(formTarget)
                    .required([{
                        name: 'name'
                    }, {
                        name: 'slug'
                    }, {
                        name: 'thumbnail'
                    }])
                    .collect(
                        (json) => {

                            let url = $.makeURL.api().addPath('catalog/product/category').href,
                                formData = jsonToFormData(json),
                                token = jsCookie.get('_PTS-Auth:Token');

                            let btnHTML = btn.innerHTML;
                            $(btn)
                                .html(`
                                    <div class="flex x_center y_center">
                                        <div class="flex_child fits mr1">
                                            <div class="anim_spin flex y_center x_center">
                                                <i class="ri-loader-4-line" style="font-size: 2rem; line-height: 0;"></i>
                                            </div>
                                        </div>
                                        <div class="flex_child fits">
                                            ${$(btn).text().trim()}
                                        </div>
                                    </div>
                                `)
                                .attr('disabled', '');

                            let alter_slug = 2;

                            let insert = function(formData) {

                                $.ajax({
                                    type: 'POST',
                                    url: url,
                                    headers: {
                                        'Authorization': `Bearer ${token}`
                                    },
                                    data: formData,
                                    success: function(res) {

                                        if (res.code == 200 || res.code == 204) {

                                            alert('Data berhasil disimpan');
                                            history.go(-1);
                                        }

                                        $(btn).html(btnHTML).removeAttr('disabled');
                                    },
                                    error: function(err) {

                                        console.log(err);

                                        if (typeof err.responseJSON !== 'undefined') {

                                            err = err.responseJSON;

                                            if (err.code == 409) {

                                                json['slug'] += '-' + alter_slug;
                                                alter_slug++;

                                                insert(jsonToFormData(json));
                                            } else {

                                                alert(`Error: \nCode: ${err.code}\nDescription: ${err.description}`);
                                            }
                                        }

                                        $(btn).html(btnHTML).removeAttr('disabled')
                                    }
                                });
                            }

                            insert(formData);
                        },
                        (err) => {

                            if ($.inArray(err.code, [undefined, null, '']) < 0 &&
                                err.code == 'REQUIRED_FORM_IS_EMPTY') {

                                let errDomParent = $(err.form.dom).parents('*[class*="tx_field"], *[class*="qty_field"]');

                                $(errDomParent).attr('ptx_validation', 'invalid')
                                    .find('input, textarea').focus()
                                    .end();
                            }
                        }
                    );
            });
    </script>
</div>