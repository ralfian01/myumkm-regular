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

            <div class="mt2 tx_bg0c5">
                <?= $catalog_category_data['description']; ?>
            </div>
        </div>
    </div>

    <div class="mt4 tx_fam_montserrat tx_w_bold tx_bg1">
        Katalog
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