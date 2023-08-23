<?= $this->extend("MainLayout/template.php"); ?>

<!-- Pre-process - Start -->



<!-- Pre-process - Finish -->

<?= $this->section('main'); ?>

<!-- Navigation -->
<?= $this->include("{$base_path}/components/navigation.php"); ?>

<div class="block p4 py8 mb_p2 mb_py3 tb_p2 tb_py4" style="background: rgb(var(--Col_theme-main)); color: #fff;">
    <div class="tx_bg5 mb_tx_bg2 tb_tx_bg3" style="color: inherit;">
        Blog
    </div>
</div>

<div class="block mxa p2 py4" style="max-width: 1200px;">

    <?php if (isset($blog_list) && count($blog_list) >= 1) : ?>

    <?php else : ?>

        <div class="flex y_center x_center" style="min-height: 200px;">
            <div class="flex_child fits tx_al_ct tx_bg0c5" style="max-width: 400px;">
                Data blog tidak tersedia
            </div>
        </div>

    <?php endif; ?>

</div>

<!-- Footer -->
<?= $this->include("{$base_path}/components/footer.php"); ?>

<?= $this->endSection('main'); ?>