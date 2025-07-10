<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="font-bold text-xl"><?= lang('Text.create_password') ?></h2>
<form action="/membership/save-password" method="post" class="space-y-5 text-left">
    <?= csrf_field() ?>
    <div>
        <label class="block text-sm text-gray-700 mb-1"><?= lang('Text.password') ?></label>
        <input type="password" name="password" required class="block w-full border px-4 py-2 rounded-md" />
    </div>
    <button type="submit" class="w-full py-2 bg-red-500 text-white rounded-md hover:bg-red-600 font-semibold">
        <?= lang('Text.save_password') ?>
    </button>
</form>

<?= $this->endSection() ?>