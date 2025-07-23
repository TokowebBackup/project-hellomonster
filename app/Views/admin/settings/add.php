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

    <form action="<?= $setting['id'] ? base_url('admin/settings/update/' . $setting['id']) : base_url('admin/settings/save') ?>" method="post" enctype="multipart/form-data" class="space-y-4">
        <?= csrf_field() ?>
        <div>
            <label class="block font-semibold">Key </label>
            <input type="text" name="key_name" class="w-full border rounded px-3 py-2 <?= $setting['id'] ? 'bg-gray-100' : '' ?>"
                value="<?= esc($setting['key_name']) ?>">
        </div>

        <div>
            <label class="block font-semibold mb-1">Value</label>
            <textarea id="editor" name="content" class="w-full border rounded px-3 py-2"><?= esc($setting['content']) ?></textarea>
        </div>

        <!-- Upload Gambar (opsional) -->
        <div>
            <label class="block font-semibold mb-1">Upload Gambar</label>
            <input type="file" id="imageUploader" accept="image/*" class="border px-3 py-2 rounded w-full">
        </div>
        <div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                <?= $setting['id'] ? 'Simpan Perubahan' : 'Tambah Setting' ?>
            </button>
            <a href="<?= base_url('admin/settings') ?>" class="ml-4 text-gray-600 hover:underline">Kembali</a>
        </div>
    </form>
</div>

<script>
    tinymce.init({
        selector: '#editor',
        height: 300,
        plugins: 'image link code lists',
        toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright | bullist numlist | image link | code',
        image_title: true,
        automatic_uploads: false,
        relative_urls: false, // ⬅️ penting!
        remove_script_host: false, // ⬅️ penting!
    });

    document.getElementById('imageUploader').addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>'); // CSRF!

        fetch('<?= base_url('admin/settings/upload-image') ?>', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.location) {
                    // Tambahkan pemisah baris sebelum gambar supaya tidak menempel di <p>
                    tinymce.activeEditor.insertContent(`<p><img src="${data.location}" alt="Uploaded Image"></p>`);

                } else {
                    alert(data.error || 'Upload gagal.');
                }
            })
            .catch(err => {
                console.error('Upload error:', err);
                alert('Terjadi kesalahan saat upload.');
            });
    });
</script>

<?= $this->endSection() ?>