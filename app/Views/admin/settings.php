<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="px-4 sm:px-6 py-4 border-b flex justify-between items-center">
        <div>
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">List of Settings</h2>
            <p class="text-sm text-gray-500">Berikut adalah semua konfigurasi website yang tersedia.</p>
        </div>
        <a href="<?= base_url('admin/settings/add') ?>" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
            + Add Setting
        </a>
    </div>

    <div class="w-full overflow-auto">
        <?php if (session()->getFlashdata('message')): ?>
            <div class="bg-green-100 text-green-800 p-3 rounded m-4">
                <?= session()->getFlashdata('message') ?>
            </div>
        <?php endif; ?>

        <table class="w-full sm:min-w-[640px] text-xs sm:text-sm text-left text-gray-600">
            <thead class="bg-gray-100 text-[10px] sm:text-xs uppercase text-gray-700">
                <tr>
                    <th class="px-4 sm:px-6 py-3">Key</th>
                    <th class="px-4 sm:px-6 py-3">Value</th>
                    <th class="px-4 sm:px-6 py-3">Updated</th>
                    <th class="px-4 sm:px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($settings as $s): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 sm:px-6 py-4"><?= esc($s['key_name']) ?></td>
                        <td class="px-4 sm:px-6 py-4 max-w-xs truncate">
                            <?= strip_tags($s['content']) ?>
                        </td>
                        <td class="px-4 sm:px-6 py-4"><?= date('d M Y H:i', strtotime($s['updated_at'])) ?></td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap flex gap-2">
                            <a href="<?= base_url('admin/settings/edit/' . $s['id']) ?>"
                                class="flex items-center gap-2 border border-blue-600 text-blue-600 rounded px-3 py-1 text-sm hover:bg-blue-600 hover:text-white transition">
                                <i class="fa-solid fa-pen-to-square"></i>
                                Edit
                            </a>

                            <button
                                data-modal-target="modal-<?= $s['id'] ?>"
                                class="flex items-center gap-2 border border-gray-600 text-gray-600 rounded px-3 py-1 text-sm hover:bg-gray-600 hover:text-white transition">
                                <i class="fa-solid fa-eye"></i> View
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($settings)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data setting.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php foreach ($settings as $s): ?>
            <!-- Modal -->
            <div id="modal-<?= $s['id'] ?>" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white w-full max-w-xl mx-auto rounded-lg overflow-hidden shadow-lg">
                    <div class="px-6 py-4 border-b flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Detail Setting: <?= esc($s['key_name']) ?></h3>
                        <button onclick="closeModal('modal-<?= $s['id'] ?>')" class="text-gray-600 hover:text-gray-900 text-xl">&times;</button>
                    </div>
                    <div class="px-6 py-4 space-y-3 text-sm text-gray-700">
                        <div>
                            <strong>Key:</strong> <?= esc($s['key_name']) ?>
                        </div>
                        <div>
                            <strong>Value:</strong>
                            <div class="border p-3 bg-gray-50 rounded max-h-64 overflow-auto"><?= nl2br($s['content']) ?></div>
                        </div>
                        <div>
                            <strong>Last Updated:</strong> <?= date('d M Y H:i', strtotime($s['updated_at'])) ?>
                        </div>
                    </div>
                    <div class="px-6 py-3 border-t text-right">
                        <button onclick="closeModal('modal-<?= $s['id'] ?>')" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Close</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>


<script>
    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }
    document.querySelectorAll('[data-modal-target]').forEach(button => {
        button.addEventListener('click', () => {
            const targetId = button.getAttribute('data-modal-target');
            document.getElementById(targetId).classList.remove('hidden');
        });
    });
</script>

<?= $this->endSection() ?>