<div class="content_box" style="max-width: 500px">
    <div class="box_head">
        <div class="head_title">
            <div class="title">
                Marketplace
            </div>
            <div class="tagline">
                Informasi akun marketplace
            </div>
        </div>
    </div>
    <div class="form_target box_body">

        <?php foreach ($owner_contact['marketplace'] as $key => $val) : ?>

            <div class="tx_field1 mb2 wd100pc">
                <div class="input_label">
                    <label class="flex y_center x_start wd100pc" for="<?= $key; ?>">
                        <div class="flex_child fits mr1">

                            <?php if (isset($val['img']) && !empty($val['img'])) : ?>
                                <img src="<?= cdnURL($val['img']); ?>" alt="" style="width: 17px; fit-content: contain;">
                            <?php else : ?>
                                <i class="tx_w_regular <?= $val['icon']; ?>" style="font-size: 1.8rem;"></i>
                            <?php endif; ?>

                        </div>

                        <div class="flex_child">
                            Akun <?= ucfirst($key); ?>
                        </div>
                    </label>
                </div>

                <div class="input_item">
                    <div class="addon inline">
                        <?= $val['url']; ?>
                    </div>
                    <input type="text" name="<?= $key; ?>" id="<?= $key; ?>" placeholder="<?= $val['placeholder']; ?>" value="<?= $val['username']; ?>">
                </div>
            </div>

        <?php endforeach; ?>

        <button class="submit button1 wd100pc">
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
                    .collect(
                        (json) => {

                            // Start send API request
                            let url = $.makeURL.api().addPath('contact/marketplace').href,
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