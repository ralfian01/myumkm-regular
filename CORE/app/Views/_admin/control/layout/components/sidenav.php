<nav class="side_nav">
    <div class="nav_head">
        <img class="nav_logo" src="<?= cdnURL('logo/original/origin_simplied_v1.svg'); ?>" alt="">

        <button class="menu_btn">
            <i class="ri-arrow-left-line"></i>
            Close
        </button>
    </div>

    <div class="nav_body">
        <div class="nav_menu">
            <a href="<?= adminURL('dashboard'); ?>" class="menu_item <?= var_isset($current_url); ?>">
                <div class="menu_icon">
                    <i class="ri-home-7-line"></i>
                </div>

                <div class="menu_name">
                    Dashboard
                </div>

                <div class="notif"></div>
            </a>

            <div class="menu_item_group">
                <div class="group_init">
                    <div class="menu_icon">
                        <i class="ri-archive-line"></i>
                    </div>

                    <div class="menu_name">
                        Produk
                    </div>

                    <div class="notif"></div>
                </div>

                <div class="group_item_container">
                    <a href="<?= adminURL('catalog/product/category'); ?>" class="menu_item">
                        <div class="menu_name">
                            Kategori Produk
                        </div>

                        <div class="notif"></div>
                    </a>
                    <a href="<?= adminURL('catalog/product/list'); ?>" class="menu_item">
                        <div class="menu_name">
                            Daftar Produk
                        </div>

                        <div class="notif"></div>
                    </a>
                </div>
            </div>

            <a href="<?= adminURL('catalog/service/category'); ?>" class="menu_item">
                <div class="menu_icon">
                    <i class="ri-service-line"></i>
                </div>

                <div class="menu_name">
                    Layanan
                </div>

                <div class="notif"></div>
            </a>

            <div class="menu_item_group">
                <div class="group_init">
                    <div class="menu_icon">
                        <i class="ri-contacts-line"></i>
                    </div>

                    <div class="menu_name">
                        Kontak
                    </div>

                    <div class="notif"></div>
                </div>

                <div class="group_item_container">
                    <a href="<?= adminURL('contact/profile'); ?>" class="menu_item">
                        <div class="menu_name">
                            Profil
                        </div>

                        <div class="notif"></div>
                    </a>
                    <a href="<?= adminURL('contact/social_media'); ?>" class="menu_item">
                        <div class="menu_name">
                            Sosial media
                        </div>

                        <div class="notif"></div>
                    </a>
                    <a href="<?= adminURL('contact/marketplace'); ?>" class="menu_item">
                        <div class="menu_name">
                            Marketplace
                        </div>

                        <div class="notif"></div>
                    </a>
                </div>
            </div>

            <a href="<?= adminURL('payment_method'); ?>" class="menu_item">
                <div class="menu_icon">
                    <i class="ri-bank-card-line"></i>
                </div>

                <div class="menu_name">
                    Metode Pembayaran
                </div>

                <div class="notif"></div>
            </a>

            <a href="<?= adminURL('account_management'); ?>" class="menu_item">
                <div class="menu_icon">
                    <i class="ri-account-circle-line"></i>
                </div>

                <div class="menu_name">
                    Manajemen Akun
                </div>

                <div class="notif"></div>
            </a>

            <a href="<?= adminURL('settings'); ?>" class="menu_item">
                <div class="menu_icon">
                    <i class="ri-settings-4-fill"></i>
                </div>

                <div class="menu_name">
                    Pengaturan
                </div>

                <div class="notif"></div>
            </a>

            <div style="height: 100vh;"></div>
        </div>

        <button class="expand_btn">
            <i class="ri-expand-left-line"></i>
        </button>
    </div>
</nav>
<script type="text/javascript">
    // 
    $('body').on('click', '*[class*="panel_container"] .side_nav .menu_item_group > .group_init', function() {

        let elem = this,
            parGroup = $(this).parents('.menu_item_group')[0],
            parNav = $(this).parents('.side_nav')[0];

        if ($(parGroup).hasClass('expand')) {

            $(parGroup).removeClass('expand');
        } else {

            $(parNav).find('.menu_item_group').removeClass('expand');
            $(parGroup).addClass('expand');
        }
    }).on('click', '*[class*="panel_container"] .side_nav .expand_btn', function() {

        let elem = this,
            parNav = $(this).parents('.side_nav')[0];

        if ($(parNav).hasClass('narrow')) {

            $(parNav).removeClass('narrow');
        } else {

            $(parNav).addClass('narrow');
        }
    });
</script>