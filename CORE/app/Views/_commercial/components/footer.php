<footer class="flex_col y_start x_center tx_lh1c5" style="background: rgb(240, 240, 240);">

    <div class="flex y_start x_center mb_flex_col mb_y_center flex_gap3 p2 tx_al_ct">
        <div class="flex_child fits mb2" style="min-width: 200px; max-width: 250px;">
            <div class="tx_fam_lato tx_w_bolder tx_bg0c5">
                Alamat
            </div>

            <div class="block mt1c5 tx_fam_montserrat">
                <?= str_replace('\r\n', '<br>', $address); ?>
            </div>
        </div>

        <div class="flex_child fits mb2" style="min-width: 200px; max-width: 250px;">
            <div class="tx_fam_lato tx_w_bolder tx_bg0c5">
                Menu
            </div>

            <div class="block mt1c5">
                <a href="<?= base_url('product'); ?>" class="button1" style="--bt_bg: transparent; --bt_border_color: transparent; --bt_color: rgb(50, 50, 50);">
                    Produk
                </a>

                <a href="<?= base_url('service'); ?>" class="button1" style="--bt_bg: transparent; --bt_border_color: transparent; --bt_color: rgb(50, 50, 50);">
                    Layanan
                </a>

                <a href="<?= base_url('blog'); ?>" class="button1" style="--bt_bg: transparent; --bt_border_color: transparent; --bt_color: rgb(50, 50, 50);">
                    Blog
                </a>

                <a href="<?= base_url('contact'); ?>" class="button1" style="--bt_bg: transparent; --bt_border_color: transparent; --bt_color: rgb(50, 50, 50);">
                    Kontak
                </a>
            </div>
        </div>

        <div class="flex_child fits mb2" style="min-width: 200px; max-width: 250px;">
            <div class="tx_fam_lato tx_w_bolder tx_bg0c5">
                Ikuti kami
            </div>

            <div class="flex y_center x_center mt1c5">
                <?php
                $icon = [
                    'facebook' => 'ri-facebook-circle-fill',
                    'instagram' => 'ri-instagram-line',
                    'twitter' => '',
                    'tiktok' => ''
                ];
                ?>
                <?php if (isset($social_media)) : ?>
                    <?php foreach ($social_media as $key => $val) : ?>
                        <?php if (!in_array($val, [null, ''])) : ?>

                            <a href="<?= $val; ?>" class="button1 tx_bg1c5" style="--bt_bg: transparent; --bt_border_color: transparent; --bt_color: rgb(50, 50, 50);">
                                <i class="<?= $icon[$key]; ?> tx_w_regular"></i>
                            </a>

                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="block tx_al_ct p2" style="width: 100%; color: #fff; background: rgb(100, 100, 100);">
        &copy <?= date('Y'); ?> <?= var_isset($meta['site_name'], 'Website'); ?>, Powered by <a href="https://putsutech.com/" class="orig_udline" style="color: inherit">Putsutech.com</a>
    </div>
</footer>