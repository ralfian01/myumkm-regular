<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-language" content="id">
    <meta property="og:title" content="<?= var_isset($meta['title'], ''); ?>">
    <meta property="og:description" content="<?= var_isset($meta['description'], ''); ?>">
    <meta property="og:image:secure_url" itemprop="image" content="<?= var_isset($meta['img_url'], '') ?>">
    <meta property="og:image" itemprop="image" content="<?= var_isset($meta['img_url'], '') ?>">
    <meta property="og:url" content="<?= var_isset($meta['current_url'], ''); ?>">
    <meta property="og:site_name" content="<?= var_isset($meta['site_name'], ''); ?>">
    <meta property="og:type" content="website" />
    <meta property="og:updated_time" content="1440" />

    <meta name="keywords" content="<?= var_isset($meta['keywords'], 'website, company profile'); ?>">
    <meta name="author" content="Putsutech.com">
    <meta name="publisher" content="Putsutech.com">
    <meta name="description" content="<?= var_isset($meta['description'], ''); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="copyright" content="<?= 'Putsutech.com ' . date('Y'); ?>">
    <meta name="page-topic" content="Company Profile">
    <meta name="page-type" content="Company Profile">
    <meta name="audience" content="Everyone">
    <meta name="robots" content="index, follow">

    <title><?= var_isset($meta['title'], ''); ?></title>

    <link rel="image_src" href="<?= var_isset($meta['img_url'], '') ?>" />
    <link rel="shortcut icon" href="<?= var_isset($meta['site_icon'], cdnURL('logo/original/putsutech_simplied_v1.svg')); ?>" type="image/x-icon">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" as="style" type="text/css">

    <link rel="stylesheet" href="<?= cdnURL('css/original/origin_style.css'); ?>" as="style" type="text/css">
    <link rel="stylesheet" href="<?= cdnURL('css/original/self_icon.css'); ?>" as="style" type="text/css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" integrity="sha512-n/4gHW3atM3QqRcbCn6ewmpxcLAHGaDjpEBu4xZd47N0W2oQ+6q7oc3PXstrJYXcbNU1OHdQ1T7pAP+gi5Yu8g==" crossorigin="anonymous"></script>

    <script src="<?= cdnUrl('js/original/origin_classes.js'); ?>" type="text/javascript"></script>
    <script type="text/javascript">
        var baseUrl = "<?= base_url(); ?>";
    </script>
</head>

<body class="scr_bar set1">

    <?= $this->renderSection('main'); ?>

    <script id="origin_index" src="<?= cdnURL('js/original/origin_index.js'); ?>" type="text/javascript"></script>
</body>

</html>