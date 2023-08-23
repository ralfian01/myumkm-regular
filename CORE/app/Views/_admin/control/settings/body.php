<div class="content_box" style="max-width: 500px">
    <div class="box_head">
        <div class="head_title">
            <div class="title">
                Logout akun
            </div>
        </div>
    </div>
    <div class="box_body">
        <button class="button1 bt_small" onclick="location.href='logout';">
            Logout
        </button>
    </div>
</div>

<div class="content_box" style="max-width: 500px">
    <div class="box_head">
        <div class="head_title">
            <div class="title">
                Ganti password
            </div>
        </div>
    </div>
    <div class="form_target box_body">
        <div class="tx_field1 wd100pc">
            <div class="input_label">
                <label for="old_pass">
                    Password lama
                </label>
            </div>

            <div class="input_item">
                <input type="password" name="old_pass" id="old_pass" placeholder="Masukan password lama">
            </div>
        </div>

        <div class="tx_field1 mt2 wd100pc">
            <div class="input_label">
                <label for="new_pass">
                    Password baru
                </label>
            </div>

            <div class="input_item">
                <input type="password" name="new_pass" id="new_pass" placeholder="Masukan password baru">
            </div>
        </div>

        <div class="tx_field1 mt2 wd100pc">
            <div class="input_label">
                <label for="confirm_pass">
                    Konfirmasi password baru
                </label>
            </div>

            <div class="input_item">
                <input type="password" name="confirm_pass" id="confirm_pass" placeholder="Tulis ulang password baru">
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
                        name: 'old_pass'
                    }, {
                        name: 'new_pass'
                    }, {
                        name: 'confirm_pass'
                    }])
                    .collect(
                        (json) => {

                            // Check new pass and confirm pass
                            if (json['new_pass'] != json['confirm_pass']) {

                                $(formTarget).find('input[name="confirm_pass"]').parents('*[class*="tx_field1"]')
                                    .attr('ptx_validation', 'invalid')
                                    .find('.notif_text').remove()
                                    .end()
                                    .append(`<div class="notif_text">Konfirmasi password tidak sama</div>`)
                                    .end();

                                return;
                            }

                            // Start send API request
                            let url = $.makeURL.api().addPath('security/password').href,
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