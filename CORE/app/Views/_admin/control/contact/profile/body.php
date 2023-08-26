<div class="form_target content_box" style="max-width: 500px">
    <div class="box_head">
        <div class="head_title">
            <div class="title">
                Profil website
            </div>
        </div>
    </div>
    <div class="box_body">
        <div class="tx_field1 wd100pc">
            <div class="input_label">
                <label for="site_name">
                    Nama website <span style="color: red">*</span>
                </label>
            </div>

            <div class="input_item">
                <input type="text" name="site_name" id="site_name" placeholder="Masukan nama website" value="<?= $owner_contact['site_name']; ?>">
            </div>
        </div>

        <div class="tx_field1 mt2 wd100pc">
            <div class="input_label">
                <label for="address">
                    Alamat toko <span style="color: red">*</span>
                </label>
            </div>

            <div class="input_item">
                <textarea name="address" id="address" placeholder="Alamat toko"><?= $owner_contact['address']; ?></textarea>
            </div>
        </div>

        <div class="tx_field1 mt2 wd100pc">
            <div class="input_label">
                <label for="email">
                    Email <span style="color: red">*</span>
                </label>
            </div>

            <div class="input_item">
                <input type="email" name="email" id="email" placeholder="myemail@mail.com" value="<?= $owner_contact['email']; ?>">
            </div>
        </div>

        <div class="tx_field1 mt2 wd100pc">
            <div class="input_label">
                <label for="phone_number">
                    Nomor telepon <span style="color: red">*</span>
                </label>
            </div>

            <div class="input_item">
                <div class="addon inline tx_w_bold">
                    62
                </div>
                <input type="number" name="phone_number" id="phone_number" maxlength="15" placeholder="812345678900" value="<?= substr($owner_contact['phone_number'], 0, 2) == '62' ? substr($owner_contact['phone_number'], 2) : $owner_contact['phone_number']; ?>">
            </div>
        </div>

        <div class="tx_field1 mt2 wd100pc">
            <div class="input_label">
                <label for="office_number">
                    Nomor kantor
                </label>
            </div>

            <div class="input_item">
                <input type="number" name="office_number" id="office_number" placeholder="02134566" value="<?= $owner_contact['office_number']; ?>">
            </div>
        </div>

        <hr class="sg_line1 mt2">
        <div class="block">
            Keterangan:
            <br><span style="color: red">*</span> : Wajib diisi
        </div>

        <button class="submit button1 mt1 wd100pc">
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
                        name: 'site_name'
                    }, {
                        name: 'address'
                    }, {
                        name: 'email'
                    }, {
                        name: 'phone_number'
                    }])
                    .collect(
                        (json) => {

                            // Start send API request
                            let url = $.makeURL.api().addPath('contact/web_profile').href,
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
                                type: 'PUT',
                                url: url,
                                headers: {
                                    'Authorization': `Bearer ${token}`
                                },
                                data: formData,
                                success: function(res) {

                                    if (res.code == 200 || res.code == 204) {

                                        alert('Data berhasil disimpan');

                                        setTimeout(() => {

                                            location.reload();
                                        }, 250);
                                    }

                                    $(btn).html(btnHTML).removeAttr('disabled');
                                },
                                error: function(err) {

                                    console.log(err);

                                    if (typeof err.responseJSON !== 'undefined') {

                                        err = err.responseJSON;

                                        if (err.code == 409) {

                                            $($(formTarget).find('input[name="old_pass"]').parents('*[class*="tx_field"]'))
                                                .attr('ptx_validation', 'invalid')
                                                .find('.notif_text').remove().end()
                                                .append(`
                                                    <div class="notif_text">
                                                        Password lama salah
                                                    </div>
                                                `)
                                                .find('input, textarea').focus().end();
                                        } else {

                                            alert(`Error: \nCode: ${err.code}\nDescription: ${err.description}`);
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