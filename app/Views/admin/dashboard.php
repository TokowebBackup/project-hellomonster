<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white shadow p-6 rounded">
        <h3 class="text-lg font-medium text-gray-800">Total Members</h3>
        <p class="text-3xl mt-2 text-primary font-bold"><?= $totalMembers ?? '0' ?></p>
    </div>
    <div class="bg-white shadow p-6 rounded">
        <h3 class="text-lg font-medium text-gray-800">Recent Activities</h3>
        <p class="text-sm text-gray-600 mt-2">Coming soon...</p>
    </div>
</div>

<?= $this->endSection() ?>