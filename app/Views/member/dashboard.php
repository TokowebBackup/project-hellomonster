<?= $this->extend('layouts/member') ?>
<?= $this->section('content') ?>

<div class="max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Selamat Datang, <?= esc($member['name'] ?? $member['email']) ?></h1>

    <div class="bg-white shadow rounded-lg p-6 space-y-4">

        <div>
            <p class="font-semibold">Nama:</p>
            <p><?= esc($member['name'] ?? '-') ?></p>
        </div>

        <div>
            <p class="font-semibold">Email:</p>
            <p><?= esc($member['email']) ?></p>
        </div>

        <div>
            <p class="font-semibold">No. HP:</p>
            <p><?= esc($member['phone'] ?? '-') ?></p>
        </div>

        <div>
            <p class="font-semibold">Tanggal Lahir:</p>
            <p><?= $member['birthdate'] ? date('d M Y', strtotime($member['birthdate'])) : '-' ?></p>
        </div>

        <div>
            <p class="font-semibold">Negara:</p>
            <p><?= esc($member['country'] ?? '-') ?></p>
        </div>

        <div>
            <p class="font-semibold">Kota:</p>
            <p><?= esc($member['city'] ?? '-') ?></p>
        </div>

        <div>
            <p class="font-semibold">Alamat:</p>
            <p><?= esc($member['address'] ?? '-') ?></p>
        </div>

        <div>
            <p class="font-semibold">Status Akun:</p>
            <?php
            $isActive = $member['is_active'];
            $badgeClass = $isActive
                ? 'bg-green-100 text-green-700'
                : 'bg-red-100 text-red-700';
            $badgeText = $isActive ? 'Aktif' : 'Belum Aktif';
            ?>
            <span class="inline-block px-3 py-1 text-sm rounded-full <?= $badgeClass ?>">
                <?= $badgeText ?>
            </span>
        </div>

        <div>
            <p class="font-semibold">Terdaftar Sejak:</p>
            <p><?= $member['created_at'] ? date('d M Y, H:i', strtotime($member['created_at'])) : '-' ?></p>
        </div>

    </div>

    <a href="/membership/profile" class="inline-block mt-6 px-4 py-2 bg-[#016BAF] text-white rounded-md hover:bg-blue-700">
        Edit Profil
    </a>
</div>

<?= $this->endSection() ?>