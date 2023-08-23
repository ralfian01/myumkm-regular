<?= $this->extend('_administrator/auth/layout/template.php'); ?>

<?= $this->section('content'); ?>

<div class="login_box1" style="background-image: url(<?= cdnURL(''); ?>);">
    <div class="bx_item">

        <div class="mb_hide mt3"></div>
        <h2 class="tx_ct my0">
            Reset Password
        </h2>

        <div class="tx_left mt2" style="width: 100%; box-sizing: border-box;">

            <p class="bold my0" style="padding-left: 5px;">
                Email
            </p>
            <input class="input_item mt1" type="text" placeholder="Ex: myemail@mail.com">

            <button class="button_item mt3" style="background: rgba(242, 78, 30, 1);">
                Reset Now
            </button>

            <a href="<?= adminURL('login'); ?>" class="block mt3">
                <button class="button_item">
                    Login
                </button>
            </a>
        </div>

        <div class="mb_hide mb3"></div>
    </div>
</div>

<?= $this->endSection(); ?>