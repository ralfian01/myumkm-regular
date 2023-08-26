<div class="head_title">
    <div class="title">
        Metode Pembayaran
    </div>
    <div class="tagline">
        Pengaturan metode pembayaran
    </div>
</div>
<div class="addon">
    <a class="button1 bt_small" href="<?= adminURL('payment_method/new'); ?>">
        Tambah
    </a>
</div>

<script type="text/javascript">
    // 
    $('body')
        .find('*[class*="panel_container"] .content_container').on('scroll', function() {

            let scrollPosY = $(this).scrollTop();

            if (scrollPosY > 5) $(this).find('.content_head').addClass('active');
            else $(this).find('.content_head').removeClass('active');
        });
</script>