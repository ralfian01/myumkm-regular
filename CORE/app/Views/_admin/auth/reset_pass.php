<?= $this->extend("MainLayout/template.php"); ?>

<!-- Pre-process - Start -->



<!-- Pre-process - Finish -->

<!-- Main content - Start -->
<?= $this->section('main'); ?>

<div class="hero1 flex y_center h_center p2 mb_p0c5" style="height: 100vh; background: #fcf5eb;">
    <div class="hero_content p0" style="max-width: 350px;">
        <div class="main_content">

            <div class="block p2 py5 mb_p2 borad10" style="background: rgba(255, 255, 255, 0.9);">

                <div class="tx_w_bold tx_bg3 mb_tx_bg2 tx_al_ct">
                    Reset password
                </div>

                <div class="form_target block mt2">
                    <div class="tx_field1">
                        <div class="input_label">
                            <label for="usermail">
                                Username atau email
                            </label>
                        </div>

                        <div class="input_item">
                            <input id="usermail" type="text" name="usermail" placeholder="Username atau email">
                        </div>

                        <div class="notif_text"></div>
                    </div>

                    <button class="login button1 mt2 wd100pc">
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

    <div class="hero_background" style="filter: blur(20px); background-image: url(<?= cdnURL('svg/original/bg1.svg'); ?>);"></div>
</div>

<script type="text/javascript">
    // Event

    $('.form_target').on('keyup', 'input[name="usermail"], input[name="pass"]', function(evt) {

        let formTarget = $(this).parents('.form_target')[0];

        if (evt.keyCode == 13) {

            $(formTarget).find('.login').click();
        }
    }).on('click', '.login', function() {

        let formTarget = $(this).parents('.form_target')[0],
            button = this;

        $(formTarget)
            .find('*[class*="tx_field"], *[class*="qty_field"]')
            .attr('ptx_validation', '')

        // $.formCollect
        //     .target(formTarget)
        //     .required([{
        //             'name': 'email'
        //         },
        //         {
        //             'name': 'password'
        //         },
        //     ])
        //     .collect(
        //         (json) => {

        //             let url = $.makeURL.api().addPath('auth/account').href;

        //             let btnHTML = button.innerHTML;

        //             $(button)
        //                 .html(`
        //                         <div class="flex x_center y_center">
        //                             <div class="flex_child fits mr1">
        //                                 <div class="anim_spin flex y_center x_center">
        //                                     <i class="ri-loader-4-line" style="font-size: 2rem; line-height: 0;"></i>
        //                                 </div>
        //                             </div>
        //                             <div class="flex_child fits">
        //                                 ${$(button).text().trim()}
        //                             </div>
        //                         </div>
        //                     `)
        //                 .attr('disabled', '');

        //             // Start send 
        //             $.ajax({
        //                 type: 'POST',
        //                 url: url,
        //                 headers: {
        //                     'Authorization': 'Basic ' + btoa(json['email'] + ':' + json['password'])
        //                 },
        //                 success: function(res) {

        //                     if (res.code == 200) {

        //                         jsCookie.save({
        //                             name: '_PTS-Auth:Token',
        //                             value: res.data['token']
        //                         });

        //                         setTimeout(() => {

        //                             location.reload();
        //                         }, 150);
        //                     }

        //                     $(button).html(btnHTML).removeAttr('disabled');
        //                 },
        //                 error: function(err) {

        //                     alert('Username atau password salah');

        //                     $(button).html(btnHTML).removeAttr('disabled')

        //                     // let errJson = err.responseJSON;
        //                     // let notifValue = '';

        //                     // if (errJson.status == 'DATA_NOT_FOUND') {

        //                     //     notifValue = 'Kombinasi email dan password tidak tersedia';
        //                     // } else if (errJson.status == 'FORBIDDEN') {

        //                     //     notifValue = 'Anda belum bisa mengakses akun. Harap cek email konfirmasi';
        //                     // }

        //                     // $.notif.error(notifValue, 2000);

        //                     // $(button).html(btnHTML).removeAttr('disabled');
        //                 }
        //             });
        //         },
        //         (err) => {

        //             if ($.inArray(err.code, [undefined, null, '']) < 0 &&
        //                 err.code == 'REQUIRED_FORM_IS_EMPTY') {

        //                 let errDomParent = $(err.form.dom).parents('*[class*="tx_field"], *[class*="qty_field"]');

        //                 $(errDomParent).attr('ptx_validation', 'invalid')
        //                     .find('input, textarea').focus()
        //                     .end()
        //                     .find('.notif_text').remove()
        //                     .end();
        //             }
        //         }
        //     );
    });
</script>

<?= $this->endSection('main'); ?>
<!-- Main content - Finish -->