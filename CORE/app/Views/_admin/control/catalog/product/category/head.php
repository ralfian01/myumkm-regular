<div class="head_title">
    <div class="title">
        Kategori Produk
    </div>
    <div class="tagline">
        Atur Kategori Produk
    </div>
</div>
<div class="addon">
    <a class="button1 bt_small" href="<?= adminURL('catalog/product/category/new'); ?>">
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