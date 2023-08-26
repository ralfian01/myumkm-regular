<?= $this->extend("MainLayout/template.php"); ?>

<!-- Pre-process - Start -->



<!-- Pre-process - Finish -->

<!-- Main content - Start -->
<?= $this->section('main'); ?>

<!-- Navigation -->
<?= $this->include("{$base_path}/components/navigation.php"); ?>

<div class="block p4 py8 mb_p2 mb_py3 tb_p2 tb_py4" style="background: rgb(var(--Col_theme-main)); color: #fff;">
    <div class="tx_bg5 mb_tx_bg2 tb_tx_bg3" style="color: inherit;">
        <?= $catalog_data['catalog_name']; ?>
    </div>
</div>

<div class="block mxa p2 py4" style="max-width: 1200px;">

    <div class="flex y_start x_start mb_block flex_gap5">
        <div class="flex_child">
            <div style="width: 100%; position: relative; background: var(--Col_ctx-netral1);">
                <div style="width: 100%; padding-bottom: 100%;">
                    <img src="<?= cdnURL("image/{$catalog_data['image_path'][0]['path']}"); ?>" alt="" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>
        </div>

        <div class="flex_child">
            <div class="tx_bg2 tx_fam_montserrat">
                <?= $catalog_data['catalog_name']; ?>
            </div>

            <div class="tx_bg1c5 tx_w_bolder mt1">
                Rp. <?= rupiah($catalog_data['price']); ?>
            </div>

            <div class="collapse1 clp_anim mt2">
                <div class="clp_init tx_bg0c5 button1 wd100pc p0" style="--bt_bg: transparent; --bt_border_color: transparent; --bt_color: rgb(var(--Col_theme-main)); --bt_hv: var(--bt_hv-dark);">
                    Pesan sekarang
                </div>

                <div class="clp_container">
                    <div class="form_target block p0c5">
                        <div class="tx_fam_montserrat tx_w_bold mt1" style="color: rgb(100, 100, 100);">
                            Detail pesanan
                        </div>

                        <input type="hidden" name="seller_name" value="<?= $seller_name; ?>">
                        <input type="hidden" name="wa_number" value="<?= $wa_number; ?>">
                        <input type="hidden" name="catalog_url" value="<?= $current_url; ?>">
                        <input type="hidden" name="catalog_name" value="<?= $catalog_data['catalog_name']; ?>">
                        <input type="hidden" name="price" value="<?= $catalog_data['price']; ?>">

                        <div class="qty_field1 mt1">
                            <div class="input_label">
                                <label for="quantity">
                                    Jumlah
                                </label>
                            </div>

                            <div class="input_item">
                                <button id="dec">
                                    <i class="ri-subtract-line"></i>
                                </button>

                                <input id="quantity" type="number" min="1" max="100" name="quantity" placeholder="1">

                                <button id="inc">
                                    <i class="ri-add-line"></i>
                                </button>
                            </div>
                        </div>

                        <div class="tx_fam_montserrat tx_w_bold mt2" style="color: rgb(100, 100, 100);">
                            Detail pengiriman
                        </div>

                        <div class="tx_field1 mt1">
                            <div class="input_label">
                                <label for="receiver_name">
                                    Nama penerima
                                </label>
                            </div>

                            <div class="input_item">
                                <input id="receiver_name" type="text" name="receiver_name" placeholder="ct: John">
                            </div>
                        </div>

                        <div class="tx_field1 mt1">
                            <div class="input_label">
                                <label for="phone_number">
                                    No. telp
                                </label>
                            </div>

                            <div class="input_item">
                                <input id="phone_number" type="number" name="phone_number" placeholder="081234567890">
                            </div>
                        </div>

                        <div class="tx_field1 mt1">
                            <div class="input_label">
                                <label for="address">
                                    Alamat
                                </label>
                            </div>

                            <div class="input_item">
                                <textarea id="address" name="address" placeholder="Jl. Sanggabuana No. 16" ptx_resizable></textarea>
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
                                            name: 'quantity'
                                        }, {
                                            name: 'receiver_name'
                                        }, {
                                            name: 'phone_number'
                                        }, {
                                            name: 'address'
                                        }])
                                        .collect(
                                            (json) => {

                                                text = "Halo " + json['seller_name'] + ", saya ingin memesan produk dengan detail pesanan sebagai berikut";

                                                json['price_total'] = json['price'] * json['quantity'];

                                                // Progress
                                                $.each(json, function(key, val) {

                                                    switch (key) {
                                                        case 'catalog_name':

                                                            text += "%0aNama produk: *" + val + "*";
                                                            break;
                                                        case 'quantity':

                                                            text += "%0aJumlah: *" + val + "*";
                                                            break;
                                                        case 'receiver_name':

                                                            text += "%0a%0a*Data pemesan:*";
                                                            text += "%0aNama pemesan: *" + val + "*";
                                                            break;
                                                        case 'phone_number':

                                                            text += "%0aNomor telepon: *" + val + "*";
                                                            break;
                                                        case 'address':

                                                            text += "%0aAlamat pemesan: *" + val + "*";
                                                            break;
                                                    }
                                                });

                                                text += "%0a%0a*Detail tagihan:*";
                                                text += "%0aHarga satuan: *Rp. " + rupiah(json['price']) + "*";
                                                text += "%0aTotal Harga: *Rp. " + rupiah(json['price_total']) + "*";
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
                                            <img src="<?= cdnURL("{$payment_method_opt[$val['method']]['img']}"); ?>" alt="" style="width: 50px;">
                                        </div>
                                        <div class="flex_child ml1">
                                            <?= "{$val['method']} a/n {$val['name']}"; ?>
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

            <div class="tx_bg0c5 mt2 tx_fam_montserrat" style="color: rgb(130, 130, 130);">
                Kategori

                <a href="<?= base_url($catalog_data['category_slug']); ?>" style="color: inherit">
                    <span class="tx_w_bolder"><?= $catalog_data['category_name']; ?></span>
                </a>
            </div>

            <div class="mt1">
                <div class="tx_bg0c5 tx_w_bold tx_fam_montserrat" style="color: rgb(130, 130, 130);">
                    Deskripsi
                </div>

                <div class="mt1 tx_bg0c5">
                    <?= $catalog_data['description']; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mt2 tx_fam_montserrat tx_w_bold tx_bg1">
        Foto produk
    </div>
    <div class="mt1">

        <?php foreach ($catalog_data['image_path'] as $imgKey => $imgVal) : ?>

            <img src="<?= cdnURL("image/{$imgVal['path']}"); ?>" alt="" style="width: 200px; max-height: 150px;">

        <?php endforeach; ?>

    </div>

    <div class="mt4 tx_fam_montserrat tx_w_bold tx_bg1">
        Katalog lainnya
    </div>

    <div class="block mt2">

        <?php if (isset($catalog_list) && count($catalog_list) >= 1) : ?>
            <?php foreach ($catalog_list as $ctKey => $ctVal) : ?>

                <a href="<?= base_url("product/catalog/{$ctVal['catalog_id']}"); ?>" class="card1 borad5">
                    <div class="card_img">
                        <div class="image">
                            <img src="<?= cdnURL("image/{$ctVal['image_path'][0]['path']}"); ?>" alt="">
                        </div>
                    </div>

                    <div class="card_info">
                        <div class="tx_fam_montserrat tx_overflow_mt2">
                            <?= $ctVal['catalog_name']; ?>
                        </div>

                        <div class="tx_w_bolder mt0c5">
                            <?= rupiah($ctVal['price']); ?>
                        </div>
                    </div>
                </a>

            <?php endforeach; ?>
        <?php else : ?>

            <div class="flex y_center x_center" style="min-height: 200px;">
                <div class="flex_child fits tx_al_ct tx_bg0c5" style="max-width: 400px;">
                    Data katalog tidak tersedia
                </div>
            </div>

        <?php endif; ?>

    </div>

</div>

<!-- Footer -->
<?= $this->include("{$base_path}/components/footer.php"); ?>

<?= $this->endSection('main'); ?>
<!-- Main content - Finish -->