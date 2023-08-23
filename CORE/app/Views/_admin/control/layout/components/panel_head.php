<div class="panel_head">
    <button class="menu_btn">
        <i class="ri-menu-line"></i>
        Menu
    </button>
    <div class="head_logo">
        <img src="<?= cdnURL('logo/original/origin_simplied_v1.svg'); ?>" alt="">
    </div>
</div>
<script type="text/javascript">
    // 
    $('body')
        .on('click', '*[class*="panel_container"]', function(elem) {

            let panel = $(elem.target).parents('*[class*="panel_container"]')[0],
                sideBarPar = $(elem.target).parents('.side_nav');

            if (sideBarPar.length <= 0) {

                $(panel).find('.side_nav.active').removeClass('active');
            }
        })
        .on('click', '*[class*="panel_container"] .menu_btn', function() {

            setTimeout(() => {

                let panel = $(this).parents('*[class*="panel_container"]')[0],
                    sideBar = $(panel).find('.panel_body .side_nav');

                $(sideBar).toggleClass('active');
            }, 5);

        });
</script>