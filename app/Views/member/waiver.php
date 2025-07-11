<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="text-xl font-bold mb-4">Lengkapi Data Waiver</h2>

<form action="/waiver/save" method="post" class="space-y-4 max-w-lg">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= esc($member['id']) ?>">

    <div>
        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
        <input type="text" name="name" required value="<?= esc($member['name'] ?? '') ?>"
            class="w-full border px-3 py-2 rounded-md">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">No. HP / WhatsApp</label>
        <input type="text" name="phone" required value="<?= esc($member['phone'] ?? '') ?>"
            class="w-full border px-3 py-2 rounded-md">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
        <input type="date" name="birthdate" required value="<?= esc($member['birthdate'] ?? '') ?>"
            class="w-full border px-3 py-2 rounded-md">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Negara</label>
        <input type="text" name="country" required value="<?= esc($member['country'] ?? '') ?>"
            class="w-full border px-3 py-2 rounded-md">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Kota / Kabupaten</label>
        <input type="text" name="city" required value="<?= esc($member['city'] ?? '') ?>"
            class="w-full border px-3 py-2 rounded-md">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
        <textarea name="address" required rows="3"
            class="w-full border px-3 py-2 rounded-md"><?= esc($member['address'] ?? '') ?></textarea>
    </div>

    <button type="submit"
        class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition">
        Simpan & Lanjutkan
    </button>
</form>

<?= $this->endSection() ?>