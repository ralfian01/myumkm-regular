<div class="content_box">
    <div class="search_target box_head">
        <div class="tx_field1 wd100pc">
            <div class="input_item">
                <input type="text" name="keyword" placeholder="Cari Kategori Produk">
            </div>
        </div>

        <button class="search ml1 button1 bt_small">
            Cari
        </button>
    </div>
    <script type="text/javascript">
        // 
        $('body').find('.search_target')
            .on('click', '.search', function() {

                let searchPar = $(this).parents('.search_target')[0];

                $.formCollect
                    .target(searchPar)
                    .collect((json) => {

                        console.log(json);
                    });
                // 
            });
    </script>

    <div class="box_body">

        <?php if (isset($payment_method_list) && count($payment_method_list) >= 1) : ?>
            <table class="table1 wd100pc" cellpadding="0" cellspacing="0">
                <tr>
                    <th class="p0c5 mb_hide" style="width: 70px;">
                        Metode
                    </th>
                    <th class="tx_al_left p0c5" style="width: 150px;">
                        Nomor
                    </th>
                    <th class="tx_al_left p0c5">
                        Nama
                    </th>
                    <th class="p0c5" style="width: 100px;">
                        /
                    </th>
                </tr>
                <?php foreach ($payment_method_list as $key => $val) : ?>

                    <tr style="<?= $key % 2 == 0 ? 'background: rgba(var(--Col_theme-main3), 0.25);' : ''; ?>">
                        <td class="p0c5 mb_hide">

                            <?php if ($val['method'] != 'ANOTHER') : ?>

                                <img src="<?= cdnURL("{$payment_method_option[$val['method']]['img']}"); ?>" alt="" style="width: 100%; height: 50px; object-fit: contain;">

                            <?php else : ?>

                                <div class="tx_sm1 tx_al_ct tx_w_bolder">
                                    <?= $val['bank_name']; ?>
                                </div>

                            <?php endif; ?>

                        </td>
                        <td class="p0c5">
                            <?= $val['number']; ?>
                        </td>
                        <td class="p0c5">
                            <?= $val['name']; ?>
                        </td>
                        <td class="p0c5">
                            <div class="flex y_center h_center mb_flex_col" style="height: 100%;">
                                <a class="button1 bt_small p0" style="--bt_bg: none; --bt_color: rgb(var(--Col_theme-main)); --bt_border_color: none;" href="<?= adminURL("payment_method/edit/{$val['payment_method_id']}"); ?>">
                                    <i class="ri-edit-box-line mr0c5"></i> Edit
                                </a>

                                <div class="mt0c5 dk_hide tb_hide"></div>

                                <a class="del_cat button1 bt_small p0 ml1" data-id="<?= $val['payment_method_id']; ?>" style="--bt_bg: none; --bt_color: rgb(var(--Col_ctx-erro)); --bt_border_color: none;">
                                    <i class="ri-delete-bin-line mr0c5"></i> Hapus
                                </a>
                            </div>
                        </td>
                    </tr>

                <?php endforeach; ?>
            </table>
        <?php else : ?>

            <div class="flex y_center x_center" style="height: 200px;">
                Data tidak tersedia
            </div>

        <?php endif; ?>

    </div>
</div>
<script type="text/javascript">
    // 
    $('body')
        .find('.table1')
        .on('click', '.del_cat', function() {

            let btn = this,
                id = btoa($(this).data('id')),
                action = confirm('Hapus kategori?');

            if (action) {

                let url = $.makeURL.api().addPath(`payment_method/${id}`).href,
                    token = jsCookie.get('_PTS-Auth:Token');

                $(btn).attr('disabled', '');

                $.ajax({
                    type: 'DELETE',
                    url: url,
                    headers: {
                        'Authorization': `Bearer ${token}`
                    },
                    success: function(res) {

                        if (res.code == 200 || res.code == 204) {

                            $(btn).parents('tr').remove();
                            alert('Data berhasil dihapus');
                        }
                    },
                    error: function(err) {

                        console.log(err);

                        // alert('Username atau password salah');

                        // $(button).html(btnHTML).removeAttr('disabled')
                    }
                });
            }
        });
</script>