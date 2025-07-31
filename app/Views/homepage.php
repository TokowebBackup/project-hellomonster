<?= $this->extend('layouts/main-second') ?>
<?= $this->section('content') ?>

<h1>Welcome to Hellomonster <?= $version ?></h1>
<p>This is the homepage content.</p>
<a href="<?= base_url() ?>start-waiver" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
    Go to Waiver Page
</a>


<?= $this->endSection() ?>