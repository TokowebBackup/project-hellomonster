<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="max-w-xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">
        <?= $setting['id'] ? 'Edit Setting: ' . esc($setting['key_name']) : 'Tambah Setting Baru' ?>
    </h1>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <form action="<?= $setting['id'] ? base_url('admin/settings/update/' . $setting['id']) : base_url('admin/settings/save') ?>" method="post" class="space-y-4">
        <?= csrf_field() ?>
        <div>
            <label class="block font-semibold">Key <?= $setting['id'] ? '(readonly)' : '' ?></label>
            <input type="text" name="key_name" class="w-full border rounded px-3 py-2 <?= $setting['id'] ? 'bg-gray-100' : '' ?>"
                value="<?= esc($setting['key_name']) ?>" <?= $setting['id'] ? 'readonly' : '' ?>>
        </div>

        <div>
            <label class="block font-semibold mb-1">Value</label>
            <textarea id="editor" name="content" class="w-full border rounded px-3 py-2"><?= esc($setting['content']) ?></textarea>
        </div>

        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                <?= $setting['id'] ? 'Simpan Perubahan' : 'Tambah Setting' ?>
            </button>
            <a href="<?= base_url('admin/settings') ?>" class="ml-4 text-gray-600 hover:underline">Kembali</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>