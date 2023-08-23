<?= $this->extend("MainLayout/template.php"); ?>

<!-- Pre-process - Start -->



<!-- Pre-process - Finish -->


<!-- Main content - Start -->
<?= $this->section('main'); ?>

<div class="flex y_center x_center p4" style="min-height: 100vh; min-width: 100vw;">
    <div class="flex_child fits tx_al_ct tx_lh1c5" style="max-width: 400px;">
        <img class="mxa" src="<?= cdnUrl('svg/original/404.svg'); ?>" alt="" style="height: 100px;">

        <div class="mt5 tx_bg1 tx_w_bolder">
            OOPS! TIDAK DITEMUKAN
        </div>

        <div class="mt3">
            Halaman yang anda cari mungkin sudah dihapus atau sudah diganti oleh pemilik website.
            <br>
            <a href="<?= base_url(); ?>" style="color: rgb(var(--Col_theme-main));">
                Kembali ke halaman utama
            </a>
        </div>
    </div>
</div>

<!-- Footer -->
<?= $this->include("{$base_path}/components/footer.php"); ?>

<?= $this->endSection('main'); ?>
<!-- Main content - Finish -->