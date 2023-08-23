<?= $this->extend("MainLayout/template.php"); ?>

<!-- Pre-process - Start -->



<!-- Pre-process - Finish -->

<!-- Main content - Start -->
<?= $this->section('main'); ?>

<div class="hero1 flex y_center h_center p2 mb_p0c5" style="height: 100vh;">
    <div class="hero_content p0">
        <div class="main_content">

            <div class="block p5 mb_p2 borad10" style="background: rgba(0, 0, 0, 0.15);">

                <div class="tx_w_bold tx_bg5 mb_tx_bg2 tx_al_ct">
                    Reset Password
                </div>

                <div class="block mt2">
                    <div class="tx_field1">
                        <div class="input_label">
                            <label for="email">
                                Email
                            </label>
                        </div>

                        <div class="input_item">
                            <input id="email" type="text" name="email" placeholder="emailsaya@mail.com">
                        </div>

                        <div class="notif_text"></div>
                    </div>

                    <button class="button1 mt2 wd100pc">
                        Submit
                    </button>

                    <div class="flex mt2">
                        Sudah punya akun?
                        <a href="<?= adminURL('login'); ?>" class="orig_udline ml0c5">
                            Login sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hero_background anim_spin" style="filter: blur(80px); background-image: url(<?= cdnURL('svg/original/origin_bg1.svg'); ?>);"></div>
</div>

<?= $this->endSection('main'); ?>
<!-- Main content - Finish -->