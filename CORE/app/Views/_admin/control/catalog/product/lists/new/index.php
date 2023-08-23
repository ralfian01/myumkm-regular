<?= $this->extend("{$base_path}/control/layout/layout.php"); ?>

<!-- Pre-process - Start -->


<!-- Pre-process - Finish -->

<!-- Content Head - Start -->
<?= $this->section('content_head'); ?>

<div class="content_head">
    <?php include("head.php"); ?>
</div>

<?= $this->endSection('content_head'); ?>
<!-- Content Head - Finish -->

<!-- Content Body - Start -->
<?= $this->section('content_body'); ?>

<div class="content_body">
    <?php include("body.php"); ?>
</div>

<?= $this->endSection('content_body'); ?>
<!-- Content Body - Finish -->