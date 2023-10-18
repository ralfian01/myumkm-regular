<?= $this->extend("MainLayout/template.php"); ?>

<!-- Pre-process - Start -->



<!-- Pre-process - Finish -->

<!-- Main content - Start -->
<?= $this->section('main'); ?>

<!-- Navigation -->
<?= $this->include("{$base_path}/components/navigation.php"); ?>

<div class="block p4 py8 mb_p2 mb_py3 tb_p2 tb_py4" style="background: rgb(var(--Col_theme-main)); color: #fff;">
    <div class="tx_bg5 mb_tx_bg2 tb_tx_bg3" style="color: inherit;">
        <?= $catalog_category_data['category_name']; ?>
    </div>
</div>

<div class="block mxa p2 py4" style="max-width: 1200px;">

    <div class="flex y_start x_start mb_block flex_gap5">
        <div class="flex_child">
            <img src="<?= cdnURL("image/{$catalog_category_data['image_path']}"); ?>" alt="" style="max-height: 450px; max-width: 100%;">
        </div>

        <div class="flex_child">
            <div class="tx_bg2 tx_w_bolder tx_fam_montserrat">
                <?= $catalog_category_data['category_name']; ?>
            </div>

            <div class="collapse1 clp_anim mt2">
                <div class="clp_init tx_bg0c5 button1 wd100pc p0" style="--bt_bg: transparent; --bt_border_color: transparent; --bt_color: rgb(var(--Col_theme-main)); --bt_hv: var(--bt_hv-dark);">
                    Pesan sekarang
                </div>

                <div class="clp_container">
                    <div class="form_target block p0c5">
                        <input type="hidden" name="site_name" value="<?= $seller_name; ?>">
                        <input type="hidden" name="wa_number" value="<?= $wa_number; ?>">
                        <input type="hidden" name="catalog_url" value="<?= $current_url; ?>">
                        <input type="hidden" name="catalog_name" value="<?= $catalog_category_data['category_name']; ?>">

                        <div class="tx_fam_montserrat tx_w_bold" style="color: rgb(100, 100, 100);">
                            Detail pemesan
                        </div>

                        <div class="tx_field1 mt1">
                            <div class="input_label">
                                <label for="receiver_name">
                                    Nama pemesan
                                </label>
                            </div>

                            <div class="input_item">
                                <input id="receiver_name" type="text" name="receiver_name" placeholder="ct: John">
                            </div>
                        </div>

                        <div class="tx_field1 mt2">
                            <div class="input_label">
                                <label for="phone_number">
                                    No. telp
                                </label>
                            </div>

                            <div class="input_item">
                                <input id="phone_number" type="number" name="phone_number" placeholder="081234567890">
                            </div>
                        </div>

                        <button class="order button1 wd100pc mt2">
                            Pesan
                        </button>

                        <script type="text/javascript">
                            // 
                            $('body')
                                .find('.form_target')
                                .on('click', 'button.order', function() {

                                    let formTarget = $(this).parents('.form_target')[0];

                                    $(formTarget)
                                        .find('*[class*="tx_field"], *[class*="qty_field"]')
                                        .attr('ptx_validation', '')

                                    $.formCollect
                                        .target(formTarget)
                                        .required([{
                                            name: 'receiver_name'
                                        }, {
                                            name: 'phone_number'
                                        }])
                                        .collect(
                                            (json) => {

                                                text = "Halo " + encodeURIComponent(json['site_name']) + ", saya ingin memesan layanan dengan detail pesanan sebagai berikut";

                                                json['price_total'] = json['price'] * json['quantity'];

                                                // Progress
                                                $.each(json, function(key, val) {

                                                    switch (key) {
                                                        case 'receiver_name':

                                                            text += "%0a%0a*Data pemesan:*";
                                                            text += "%0aNama pemesan: *" + encodeURIComponent(val) + "*";
                                                            break;
                                                        case 'phone_number':

                                                            text += "%0aNomor telepon: *" + encodeURIComponent(val) + "*";
                                                            break;
                                                    }
                                                });

                                                text += "%0a%0aTautan produk: " + json['catalog_url'];

                                                window.open('https://wa.me/' + json['wa_number'] + '/?text=' + text, '_blank');
                                            },
                                            (err) => {

                                                if ($.inArray(err.code, [undefined, null, '']) < 0 &&
                                                    err.code == 'REQUIRED_FORM_IS_EMPTY') {

                                                    let errDomParent = $(err.form.dom).parents('*[class*="tx_field"], *[class*="qty_field"]');

                                                    $(errDomParent)
                                                        .attr('ptx_validation', 'invalid')
                                                        .find('input, textarea').focus()
                                                        .end()
                                                        .find('.notif_text').remove()
                                                        .end();

                                                    $(`
                                                        <div class="notif_text">
                                                            Form harus diisi
                                                        </div>
                                                    `).insertAfter($(errDomParent).find('.input_item'))
                                                }
                                            }
                                        )
                                });
                        </script>

                        <?php if (isset($payment_method) && is_array($payment_method)) : ?>

                            <hr class="sg_line mt3">
                            <div class="tx_fam_montserrat tx_w_bold mt1" style="color: rgb(100, 100, 100);">
                                Metode pembayaran
                            </div>
                            <div class="block mt1">

                                <?php foreach ($payment_method as $val) : ?>

                                    <div class="flex x_start y_center">
                                        <div class="flex_child fits">

                                            <?php if ($val['method'] != "ANOTHER") : ?>

                                                <img src="<?= cdnURL("{$payment_method_opt[$val['method']]['img']}"); ?>" alt="" style="width: 50px;">

                                            <?php endif; ?>

                                        </div>
                                        <div class="flex_child ml1">
                                            <?= $val['method'] == "ANOTHER" ? $val['bank_name'] : $val['method'] . "a/n {$val['name']}"; ?>
                                            <br>
                                            <?= $val['number']; ?>
                                        </div>
                                    </div>

                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <script type="text/javascript">
                // Collapse box with animation
                $('body')
                    .find('.collapse1.clp_anim')
                    .on('click', '.clp_init', function() {

                        let parent = $(this).parents('.collapse1'),
                            clpContainer = $(parent).find('.clp_container')[0],
                            cntMxHeight = clpContainer.scrollHeight;

                        $(clpContainer).css('--clp_cntr_mx_height', cntMxHeight + 'px');

                        setTimeout(() => {

                            $(parent).toggleClass('expand')

                            setTimeout(() => {

                                $(clpContainer).css('--clp_cntr_mx_height', 'none');
                            }, 301);
                        }, 1);
                    })
                    .end()
            </script>

            <hr class="sg_line mt2">

            <div class="mt2 tx_bg0c5">
                <?= $catalog_category_data['description']; ?>
            </div>
        </div>
    </div>

</div>

<!-- Footer -->
<?= $this->include("{$base_path}/components/footer.php"); ?>

<?= $this->endSection('main'); ?>
<!-- Main content - Start -->