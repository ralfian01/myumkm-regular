<?= $this->extend("MainLayout/template.php"); ?>

<!-- Pre-process - Start -->

<?= $this->section('nav/product_list'); ?>
<?php if (isset($catalog_category['product'])) : ?>
    <?php foreach ($catalog_category['product']['list'] as $lsKey => $lsVal) : ?>

        <a href="<?= base_url($lsVal['slug']); ?>" class="group_item">
            <?= $lsVal['category_name']; ?>
        </a>

    <?php endforeach; ?>
<?php endif; ?>
<?= $this->endSection('nav/product_list'); ?>

<?= $this->section('nav/service_list'); ?>
<?php if (isset($catalog_category['service'])) : ?>
    <?php foreach ($catalog_category['service']['list'] as $lsKey => $lsVal) : ?>

        <a href="<?= base_url($lsVal['slug']); ?>" class="group_item">
            <?= $lsVal['category_name']; ?>
        </a>

    <?php endforeach; ?>
<?php endif; ?>
<?= $this->endSection('nav/service_list'); ?>

<!-- Pre-process - Finish -->

<!-- Main content - Start -->
<?= $this->section('main'); ?>

<div class="hero1 hero_light_tx" style="background: #3D93AD;">

    <nav class="nav1 mb_px0" style="z-index: 2; margin-bottom: -50px; --nv_color: #fff; --nv_item_hv_color: rgb(50, 50, 50); --nv_gr_item_hv_bg: rgb(245, 245, 245); --nv_gr_item_hv_color: rgb(50, 50, 50);">
        <div class="nav_container">
            <a href="<?= base_url(); ?>" class="nav_item py1c5 tx_lh0 mb_py0c5 tb_py0c5">
                <img class="dk_hide" src="<?= cdnURL('logo/original/origin_full_v1.svg'); ?>" style="height: 50px;">
            </a>

            <div class="flex y_center x_center flex_gap5 mb_hide tb_hide">

                <div class="nav_item_group">
                    <div class="group_init">
                        Produk
                    </div>

                    <?php if (isset($catalog_category['product'])) : ?>

                        <div class="group_item_container">
                            <?= $this->renderSection('nav/product_list'); ?>
                        </div>

                    <?php endif; ?>
                </div>

                <div class="nav_item_group">
                    <div class="group_init">
                        Layanan
                    </div>

                    <?php if (isset($catalog_category['service'])) : ?>

                        <div class="group_item_container">
                            <?= $this->renderSection('nav/service_list'); ?>
                        </div>

                    <?php endif; ?>
                </div>

                <a href="<?= base_url(); ?>" class="nav_item py1c5 tx_lh0 mb_py0c5 tb_py0c5 mb_hide tb_hide">
                    <img src="<?= cdnURL('logo/original/origin_full_v1.svg'); ?>" style="height: 80px;">
                </a>

                <a href="<?= base_url('blog'); ?>" class="nav_item">
                    Blog
                </a>

                <a href="<?= base_url('contact'); ?>" class="nav_item">
                    Kontak
                </a>
            </div>

            <div class="mb_hide tb_hide nav_item"></div>

            <div class="nav_item">
                <div class="nav_alter_btn"></div>
            </div>
        </div>

        <div class="nav_container_alter nv_item_hv3" style="--nv_color: rgb(50, 50, 50);">

            <div class="nav_item_group">
                <div class="group_init">
                    Produk
                </div>

                <?php if (isset($catalog_category['product'])) : ?>

                    <div class="group_item_container">
                        <?= $this->renderSection('nav/product_list'); ?>
                    </div>

                <?php endif; ?>
            </div>

            <div class="nav_item_group">
                <div class="group_init">
                    Layanan
                </div>

                <?php if (isset($catalog_category['service'])) : ?>

                    <div class="group_item_container">
                        <?= $this->renderSection('nav/service_list'); ?>
                    </div>

                <?php endif; ?>
            </div>

            <a href="<?= base_url('blog'); ?>" class="nav_item">
                Blog
            </a>

            <a href="<?= base_url('contact'); ?>" class="nav_item">
                Kontak
            </a>
        </div>

        <div class="alter_fixed"></div>
    </nav>
    <script type="text/javascript">
        // 
        $('body')
            .find('nav')
            .on('click', '.nav_container_alter .nav_item_group .group_init', function() {

                let mxHeight = $(this).parents('nav').height();

                $(this).parents('.nav_item_group')
                    .toggleClass('expand')
                    .css({
                        maxHeight: `calc(100vh - ${mxHeight}px)`
                    });
            })
            .on('click', '.nav_alter_btn, .alter_fixed', function() {

                let nav = $(this).parents('nav');

                $(nav)
                    .find('.nav_container_alter').toggleClass('expand');
            })
            .end();
    </script>

    <div class="hero_content">
        <div class="main_content">

            <div class="tx_w_bold tx_bg5 tb_tx_bg4 mb_tx_bg3 tx_lh1">
                Selamat datang di website Dapur Firdaus
            </div>

            <div class="mt2 tx_fam_nunito_sans tx_bg0c5 tx_lh1c9">
                Temukan pilihan produk camilan kue kering, kue basah, dan katering dengan pilihan menu yang beragam.
            </div>

            <div class="flex y_center x_start mt5">
                <a href="<?= base_url('product'); ?>" class="button1 bt_white_bg">
                    Lihat produk
                </a>

                <a href="#more" class="button1 ml0c5" style="--bt_bg: transparent; --bt_border_color: transparent;">
                    Lebih lanjut
                    <i class="ri-arrow-right-line ml0c5"></i>
                </a>
            </div>
        </div>

        <div class="addon_content tx_al_ct mb_mb4 tb_mb4" style="position: relative; z-index: -1;">
            <img class="wd100pc tb_mxa" src="<?= cdnURL('svg/original/catering_obj-anim.svg'); ?>" style="z-index: 0; max-width: 600px;">
        </div>
    </div>

    <div class="hero_background" style="filter: blur(30px); opacity: 0.5; background-image: url(<?= cdnURL('image/230316/23140105.jpg'); ?>);"></div>
</div>


<!-- Card section -->
<div id="more" class="block my4 p3 px5 mb_px2">

    <?php if (isset($catalog_category['product'])) : ?>

        <div class="tx_al_ct mxa mb3" style="max-width: 600px;">
            <div class="tx_w_bolder tx_fam_montserrat tx_bg3 mb_tx_bg2c5">
                Produk
            </div>

            <div class="mt1 tx_bg0c5">
                Pilihan produk camilan kue kering dan kue basah yang berkualitas dan katering dengan menu yang beragam.
            </div>
        </div>

        <?php if (isset($catalog_category)) : ?>
            <?php foreach ($catalog_category['product']['list'] as $lsKey => $lsVal) : ?>

                <?php
                $values = [];

                if ($lsKey % 2 == 0) {

                    $values[0] = 'x_start';
                    $values[1] = 'color: #fff; background: rgb(var(--Col_theme-main));';
                    $values[2] = 'tx_al_right';
                    $values[3] = 'x_end';
                    $values[4] = '--bt_bg: #fff; --bt_color: rgb(var(--Col_theme-main));';
                } else {

                    $values[0] = 'x_end';
                    $values[1] = '';
                    $values[2] = '';
                    $values[3] = '';
                    $values[4] = '';
                }
                ?>

                <div class="flex <?= $values[0]; ?> mxa mt1" style="max-width: 1200px;">
                    <div class="card2 <?= ($lsKey % 2 == 0) ? 'flex flx_rev' : ''; ?>" style="max-width: 900px; <?= $values[1]; ?>">

                        <div class="card_img">
                            <div class="image">
                                <img src="<?= cdnURL('image/' . $lsVal['image_path']); ?>">
                            </div>
                        </div>

                        <div class="card_info <?= $values[2]; ?>">
                            <div class="tx_fam_montserrat tx_w_bolder dk_tx_bg1c5 tx_overflow_mt2 wd100pc">
                                <?= $lsVal['category_name']; ?>
                            </div>

                            <div class="dk_tx_bg0c5 tx_overflow_mt4 mb_tx_overflow_mt3 mt2 tb_mt1 mb_mt1">
                                <?= $lsVal['description']; ?>
                            </div>

                            <div class="flex <?= $values[3]; ?> mt1c5 wd100pc">
                                <a href="<?= base_url($lsVal['slug']); ?>" class="button1 bt_small mb_mb0c5 wd_fit" style="<?= $values[4]; ?>">
                                    Lihat
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($catalog_category['service'])) : ?>

        <div class="tx_al_ct mxa mb3 mt10" style="max-width: 600px;">
            <div class="tx_w_bolder tx_fam_montserrat tx_bg3 mb_tx_bg2c5">
                Layanan
            </div>

            <div class="mt1 tx_bg0c5">
                Pilihan layanan lainnya yang tersedia di website Dapur Firdaus
            </div>
        </div>

        <?php if (isset($catalog_category)) : ?>
            <?php foreach ($catalog_category['service']['list'] as $lsKey => $lsVal) : ?>

                <?php if ($lsKey % 2 == 0) : ?>
                    <div class="flex y_stretch x_start flex_gap1">
                    <?php endif; ?>

                    <div class="card2 mb_crd_wrap mb1" style="width: 50%;">
                        <div class="card_img">
                            <div class="image">
                                <img src="<?= cdnURL('image/' . $lsVal['image_path']); ?>">
                            </div>
                        </div>

                        <div class="card_info">
                            <div class="tx_fam_montserrat tx_w_bolder dk_tx_bg1c5 tx_overflow_mt2">
                                <?= $lsVal['category_name']; ?>
                            </div>

                            <div class="dk_tx_bg0c5 tx_overflow_mt4 mb_tx_overflow_mt3 mt2 tb_mt1 mb_mt1">
                                <?= $lsVal['description']; ?>
                            </div>

                            <div class="mt1c5 wd100pc">
                                <a href="<?= base_url($lsVal['slug']); ?>" class="button1 bt_small mb_mb0c5 wd_fit">
                                    Lihat
                                </a>
                            </div>
                        </div>
                    </div>

                    <?php if (($lsKey != 0 && $lsKey % 2 == 1) || ($lsKey == (count($catalog_category['service']['list']) - 1))) : ?>
                    </div>
                <?php endif; ?>

            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>


<!-- Footer -->
<?= $this->include("{$base_path}/components/footer.php"); ?>

<?= $this->endSection('main'); ?>
<!-- Main content - Finish -->