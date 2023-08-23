<?= $this->extend("MainLayout/template.php"); ?>

<?= $this->section('main'); ?>

<div class="panel_container1">

    <?php include('components/panel_head.php'); ?>

    <div class="panel_body">
        <?php include('components/sidenav.php'); ?>

        <section class="content_container">
            <?= $this->renderSection('content_head'); ?>

            <?= $this->renderSection('content_body'); ?>
        </section>
    </div>
</div>

<?= $this->endSection('main'); ?>