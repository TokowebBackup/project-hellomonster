<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="max-w-md mx-auto bg-white p-6 rounded shadow mt-6">
    <h2 class="text-xl font-semibold mb-4">Edit Member</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 text-red-700 p-2 mb-4 rounded"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="/admin/member/update/<?= esc($member['id']) ?>" method="post">
        <?= csrf_field() ?>

        <label class="block mb-2">
            Name
            <input type="text" name="name" value="<?= esc($member['name']) ?>" class="w-full border rounded px-3 py-2" required>
        </label>

        <label class="block mb-2">
            Email
            <input type="email" name="email" value="<?= esc($member['email']) ?>" class="w-full border rounded px-3 py-2" required>
        </label>

        <label class="block mb-2">
            Phone
            <input type="text" name="phone" value="<?= esc($member['phone']) ?>" class="w-full border rounded px-3 py-2" required>
        </label>

        <label class="block mb-2">
            Country
            <input type="text" name="country" value="<?= esc($member['country']) ?>" class="w-full border rounded px-3 py-2" required>
        </label>

        <button type="submit" class="bg-blue-600 text-white rounded px-4 py-2 font-semibold hover:bg-blue-700">
            Update Member
        </button>
    </form>
</div>

<?= $this->endSection() ?>