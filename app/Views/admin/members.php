<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="px-4 sm:px-6 py-4 border-b">
        <h2 class="text-lg sm:text-xl font-semibold text-gray-800">List of Waiver Members</h2>
        <p class="text-sm text-gray-500">Berikut adalah data member yang sudah mengisi form waiver.</p>
    </div>

    <div class="w-full overflow-auto">
        <table class="w-full sm:min-w-[640px] text-xs sm:text-sm text-left text-gray-600">
            <thead class="bg-gray-100 text-[10px] sm:text-xs uppercase text-gray-700">
                <tr>
                    <th class="px-4 sm:px-6 py-3 whitespace-nowrap">Name</th>
                    <th class="px-4 sm:px-6 py-3 whitespace-nowrap">Email</th>
                    <th class="px-4 sm:px-6 py-3 whitespace-nowrap">Phone</th>
                    <th class="px-4 sm:px-6 py-3 whitespace-nowrap">Country</th>
                    <th class="px-4 sm:px-6 py-3 whitespace-nowrap">City</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($members as $m): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap"><?= esc($m['name']) ?></td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap"><?= esc($m['email']) ?></td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap"><?= esc($m['phone']) ?></td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap"><?= esc($m['country']) ?></td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap"><?= esc($m['city']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>