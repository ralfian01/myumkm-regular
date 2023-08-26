<?= $this->extend("MainLayout/template.php"); ?>

<!-- Pre-process - Start -->



<!-- Pre-process - Finish -->

<!-- Main content - Start -->
<?= $this->section('main'); ?>

<!-- Navigation -->
<?= $this->include("{$base_path}/components/navigation.php"); ?>

<div class="block p4 py8 mb_p2 mb_py3 tb_p2 tb_py4" style="background: rgb(var(--Col_theme-main)); color: #fff;">
    <div class="tx_bg5 mb_tx_bg2 tb_tx_bg3" style="color: inherit;">
        Hubungi Kami
    </div>
</div>

<div class="flex y_start x_center mb_flex_col mb_y_center flex_gap3 tx_al_ct p3 mt10 tx_bg0c5 tx_lh1c5" style="color: rgb(var(--Col_ctx-erro))">
    <div class="flex_child mb2" style="max-width: 300px;">
        <img src="<?= cdnURL('svg/original/phone_fill.svg'); ?>" alt="" style="width: 100px;">

        <div class="mt2" style="font-size: inherit;">
            <a href="<?= "tel:{$phone_number}"; ?>" class="button1 bt_small inblock p0" style="font-size: inherit; color: inherit !important; --bt_bg: transparent; --bt_border_color: tranparent;">
                <?= implode(' - ', str_split((substr($phone_number, 0, 2) == '62' ? '0' . substr($phone_number, 2) : $phone_number), 4)); ?>
            </a>
        </div>
    </div>

    <div class="flex_child mb2" style="max-width: 300px;">
        <img src="<?= cdnURL('svg/original/mail_fill.svg'); ?>" alt="" style="width: 100px;">

        <div class="mt2" style="font-size: inherit;">
            <a href="<?= "mailto:{$email}"; ?>" class="button1 bt_small inblock p0" style="font-size: inherit; color: inherit !important; --bt_bg: transparent; --bt_border_color: tranparent;">
                <?= $email; ?>
            </a>
        </div>
    </div>

    <div class="flex_child mb2" style="max-width: 300px;">
        <img src="<?= cdnURL('svg/original/map_pin_fill.svg'); ?>" alt="" style="width: 100px;">

        <div class="mt2" style="font-size: inherit;">
            <?= str_replace('\r\n', '<br>', $address); ?>
        </div>
    </div>
</div>

<?php if (isset($social_media) && count($social_media) >= 1) : ?>

    <div class="tx_w_bolder tx_al_ct tx_bg3 mt5">
        Sosial media
    </div>

    <div class="block tx_al_ct p3 tx_bg0c5 tx_lh1c5" style="color: rgb(var(--Col_theme-main))">

        <?php foreach ($social_media as $key => $val) : ?>
            <?php if (isset($val['username']) && !empty($val['username'])) : ?>

                <a href="<?= "{$val['url']}{$val['username']}"; ?>" class="wd_fit mb2 mx2" style="display: inline-block; text-decoration: none; color: inherit;">

                    <?php if (isset($val['img']) && !empty($val['img'])) : ?>
                        <img src="<?= cdnURL("{$val['img']}"); ?>" alt="" style="width: 100px;">
                    <?php else : ?>
                        <i class="<?= $val['icon']; ?> tx_w_regular" style="font-size: 6rem; color: inherit;"></i>
                    <?php endif; ?>
                </a>

            <?php endif; ?>
        <?php endforeach; ?>

    </div>

<?php endif; ?>

<?php if (isset($marketplace) && count($marketplace) >= 1) : ?>

    <div class="tx_w_bolder tx_al_ct tx_bg3 mt5">
        Marketplace
    </div>

    <div class="block tx_al_ct p3 tx_bg0c5 tx_lh1c5" style="color: rgb(var(--Col_ctx-erro))">

        <?php foreach ($marketplace as $key => $val) : ?>
            <?php if (isset($val['username']) && !empty($val['username'])) : ?>

                <a href="<?= "{$val['url']}{$val['username']}"; ?>" class="wd_fit mb2 mx2" style="display: inline-block;">
                    <img src="<?= cdnURL("{$val['img']}"); ?>" alt="" style="width: 70px;">
                </a>

            <?php endif; ?>
        <?php endforeach; ?>

    </div>

<?php endif; ?>

<!-- Footer -->
<?= $this->include("{$base_path}/components/footer.php"); ?>

<?= $this->endSection('main'); ?>
<!-- Main content - Finish -->