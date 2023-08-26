<div class="content_box" style="max-width: 500px;">
    <div class="box_head">
        <div class="head_title">
            <div class="title">
                Tambah data
            </div>
        </div>
    </div>

    <div class="form_target box_body">

        <div class="tx_field1">
            <div class="input_label">
                <label for="payment_method">
                    Metode
                </label>
            </div>

            <div class="input_item">
                <select id="payment_method" name="payment_method">

                    <option value="" selected disabled>-Pilih Metode Pembayaran-</option>

                    <?php foreach ($payment_method_option as $key => $val) : ?>

                        <option value="<?= $val['method']; ?>"><?= $val['name']; ?></option>

                    <?php endforeach; ?>

                </select>
            </div>
        </div>

        <div class="tx_field1 mt1c5">
            <div class="input_label">
                <label for="name">
                    Nama rekening/akun
                </label>
            </div>

            <div class="input_item">
                <input type="text" name="name" id="name" placeholder="Nama rekening atau akun" value="">
            </div>
        </div>

        <div class="tx_field1 mt1c5">
            <div class="input_label">
                <label for="number">
                    Nomor rekening
                </label>
            </div>

            <div class="input_item">
                <input type="text" name="number" id="number" placeholder="Nomor rekening" value="">
            </div>
        </div>

        <button class="submit button1 mt1c5">
            Simpan
        </button>
    </div>
    <script type="text/javascript">
        // 
        $('body')
            .on('click', '.form_target .submit', function() {

                let formTarget = $(this).parents('.form_target')[0],
                    btn = this;

                $(formTarget)
                    .find('*[class*="tx_field"], *[class*="qty_field"]')
                    .attr('ptx_validation', '')

                $.formCollect
                    .target(formTarget)
                    .required([{
                        name: 'payment_method'
                    }, {
                        name: 'name'
                    }, {
                        name: 'number'
                    }])
                    .collect(
                        (json) => {

                            let url = $.makeURL.api().addPath('payment_method').href,
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

                                            $($(formTarget).find('input[name="slug"]').parents('*[class*="tx_field"]'))
                                                .attr('ptx_validation', 'invalid')
                                                .find('.notif_text').remove().end()
                                                .append(`
                                                    <div class="notif_text">
                                                        URL sudah digunakan
                                                    </div>
                                                `)
                                                .find('input, textarea').focus().end();
                                        } else {

                                            let imgPreview = $(formTarget).find('.img_preview').length;
                                            imgPreview += json['thumbnails'].length;

                                            if (imgPreview > 3) {

                                                alert('Maksimal 3 foto per produk');
                                            } else {

                                                alert(`Error: \nCode: ${err.code}\nDescription: ${err.description}`);
                                            }
                                        }
                                    }

                                    $(btn).html(btnHTML).removeAttr('disabled');
                                }
                            });
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