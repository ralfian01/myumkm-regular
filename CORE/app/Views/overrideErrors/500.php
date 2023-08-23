<?= $this->extend("MainLayout/template.php"); ?>

<!-- Pre-process - Start -->


<!-- Pre-process - Finish -->


<!-- Main content - Start -->
<?= $this->section('main'); ?>

<div class="flex y_center x_center p4" style="min-height: 100vh; min-width: 100vw;">
    <div class="flex_child fits tx_al_ct tx_lh1c5" style="max-width: 400px;">

        <!-- <img class="mxa" src="<?= cdnUrl('svg/original/404.svg'); ?>" alt="" style="height: 100px;"> -->

        <div class="mt5 tx_bg1c5 tx_w_bolder">
            KESALAHAN SERVER
        </div>

        <div class="mt3">
            Maaf, saat ini server sedang mengalami error.
            <br>Silakan muat ulang halaman dalam beberapa waktu.
            <br>
            <a href="<?= base_url(); ?>" style="color: rgb(var(--Col_theme-main));">
                Kembali ke halaman utama
            </a>
        </div>
    </div>
</div>

<?= $this->endSection('main'); ?>
<!-- Main content - Finish -->