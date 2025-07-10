<?= $this->extend('layouts/member') ?>
<?= $this->section('content') ?>

<div class="max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Edit Profil</h1>

    <form action="/membership/profile" method="post" class="bg-white p-6 rounded-lg shadow-md space-y-4">
        <?= csrf_field() ?>

        <input type="hidden" name="id" value="<?= esc($member['id']) ?>" />

        <div>
            <label class="block text-sm mb-1 text-gray-700">Email</label>
            <input type="email" name="email" value="<?= esc($member['email']) ?>" class="w-full px-4 py-2 border rounded-md bg-gray-100" readonly />
        </div>
        <div>
            <label class="block text-sm mb-1 text-gray-700">Nama</label>
            <input type="text" name="name" value="<?= esc($member['name']) ?>" class="w-full px-4 py-2 border rounded-md" />
        </div>
        <div>
            <label class="block text-sm mb-1 text-gray-700">No. HP</label>
            <input type="text" name="phone" value="<?= esc($member['phone']) ?>" class="w-full px-4 py-2 border rounded-md" />
        </div>
        <button type="submit" class="inline-block mt-6 px-4 py-2 bg-[#016BAF] text-white rounded-md hover:bg-blue-700">Simpan Perubahan</button>
    </form>

    <a href="/membership/dashboard" class="block mt-4 text-blue-600 hover:underline">← Kembali ke Dashboard</a>
</div>

<?= $this->endSection() ?>