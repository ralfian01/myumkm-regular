<?= $this->extend("MainLayout/template.php"); ?>

<!-- Pre-process - Start -->



<!-- Pre-process - Finish -->

<!-- Main content - Start -->
<?= $this->section('main'); ?>

<!-- Navigation -->
<?= $this->include("{$base_path}/components/navigation.php"); ?>

<div class="block p4 py8 mb_p2 mb_py3 tb_p2 tb_py4" style="background: rgb(var(--Col_theme-main)); color: #fff;">
    <div class="tx_bg5 mb_tx_bg2 tb_tx_bg3" style="color: inherit;">
        Layanan
    </div>
</div>

<div class="block mxa p2 py4" style="max-width: 1500px;">

    <div class="tx_al_ct mxa mb3" style="max-width: 600px;">
        <div class="tx_w_bolder tx_fam_montserrat tx_bg3 mb_tx_bg2c5">
            Layanan
        </div>

        <div class="mt1 tx_bg0c5">
            Pilihan layanan lainnya yang tersedia di website Dapur Firdaus
        </div>
    </div>

    <?php foreach ($catalog_category['service']['list'] as $lsKey => $lsVal) : ?>

        <?php if ($lsKey % 2 == 0) : ?>
            <div class="flex y_stretch x_start flex_gap1 mb_flex_gap0 mb_block">
            <?php endif; ?>

            <div class="card2 wd50pc mb_wd100pc mb1">
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

            <?php if (($lsKey != 0 && $lsKey % 2 == 1) || $lsKey == count($catalog_category['product']['list']) - 1) : ?>
            </div>
        <?php endif; ?>

    <?php endforeach; ?>
</div>

<!-- Footer -->
<?= $this->include("{$base_path}/components/footer.php"); ?>

<?= $this->endSection('main'); ?>
<!-- Main content - Finish -->