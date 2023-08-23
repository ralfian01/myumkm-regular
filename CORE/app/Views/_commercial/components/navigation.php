<nav class="nav1" style="border-bottom: 1px solid rgb(240, 240, 240); --nv_bg: #fff; --nv_gr_item_hv_color: rgb(50, 50, 50); --nv_gr_item_hv_bg: rgb(245, 245, 245); --nv_item_hv_color: rgb(var(--Col_theme-main));">
    <div class="nav_container">
        <a href="<?= base_url(); ?>" class="nav_item py1c5 tx_lh0 mb_py0c5">
            <img class="tb_hide mb_hide" src="<?= cdnURL('logo/original/origin_full_v1.svg'); ?>" style="height: 60px;">
            <img class="dk_hide" src="<?= cdnURL('logo/original/origin_full_v1.svg'); ?>" style="height: 40px;">
        </a>

        <div class="flex y_center h_center flex_gap4 mb_hide tb_hide">

            <div class="nav_item_group">
                <div class="group_init">
                    Produk
                </div>

                <div class="group_item_container gr_item_hv2">

                    <?php foreach ($catalog_category['product']['list'] as $lsKey => $lsVal) : ?>

                        <a href="<?= base_url($lsVal['slug']); ?>" class="group_item">
                            <?= $lsVal['category_name']; ?>
                        </a>

                    <?php endforeach; ?>

                </div>
            </div>

            <div class="nav_item_group">
                <div class="group_init">
                    Layanan
                </div>

                <div class="group_item_container gr_item_hv2">

                    <?php foreach ($catalog_category['service']['list'] as $lsKey => $lsVal) : ?>

                        <a href="<?= base_url($lsVal['slug']); ?>" class="group_item">
                            <?= $lsVal['category_name']; ?>
                        </a>

                    <?php endforeach; ?>

                </div>
            </div>

            <a href="<?= base_url('blog'); ?>" class="nav_item">
                Blog
            </a>

            <a href="<?= base_url('contact'); ?>" class="nav_item">
                Kontak
            </a>
        </div>

        <div class="nav_item">
            <div class="nav_alter_btn"></div>
        </div>
    </div>

    <div class="nav_container_alter nv_item_hv3">

        <div class="nav_item_group">
            <div class="group_init">
                Produk
            </div>

            <div class="group_item_container gr_item_hv2">

                <?php foreach ($catalog_category['product']['list'] as $lsKey => $lsVal) : ?>

                    <a href="<?= base_url($lsVal['slug']); ?>" class="group_item">
                        <?= $lsVal['category_name']; ?>
                    </a>

                <?php endforeach; ?>

            </div>
        </div>

        <div class="nav_item_group">
            <div class="group_init">
                Layanan
            </div>

            <div class="group_item_container gr_item_hv2">

                <?php foreach ($catalog_category['service']['list'] as $lsKey => $lsVal) : ?>

                    <a href="<?= base_url($lsVal['slug']); ?>" class="group_item">
                        <?= $lsVal['category_name']; ?>
                    </a>

                <?php endforeach; ?>

            </div>
        </div>

        <a href="<?= base_url('blog'); ?>" class="nav_item">
            Blog
        </a>

        <a href="<?= base_url('contact'); ?>" class="nav_item">
            Kontak
        </a>
    </div>

    <div class="alter_fixed"></div>
</nav>

<script type="text/javascript">
    // 
    $('body')
        .find('nav')
        .on('click', '.nav_container_alter .nav_item_group .group_init', function() {

            let mxHeight = $(this).parents('nav').height();

            $(this).parents('.nav_item_group')
                .toggleClass('expand')
                .css({
                    maxHeight: `calc(100vh - ${mxHeight}px)`
                });
        })
        .on('click', '.nav_alter_btn, .alter_fixed', function() {

            let nav = $(this).parents('nav');

            $(nav)
                .find('.nav_container_alter').toggleClass('expand');
        })
        .end();
</script>