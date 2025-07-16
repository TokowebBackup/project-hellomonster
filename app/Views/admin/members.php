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
                    <th class="px-4 sm:px-6 py-3 whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php foreach ($members as $m): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap"><?= esc($m['name']) ?></td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap"><?= esc($m['email']) ?></td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap"><?= esc($m['phone']) ?></td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <?php if (!empty($m['country_code'])): ?>
                                    <img src="https://flagcdn.com/w20/<?= esc($m['country_code']) ?>.png" alt="<?= esc($m['country']) ?>" class="inline-block w-5 h-auto rounded-sm">
                                <?php endif; ?>
                                <span><?= esc($m['country']) ?></span>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap flex gap-2">
                            <a href="/admin/member/edit/<?= esc($m['id']) ?>"
                                class="flex items-center gap-2 border border-blue-600 text-blue-600 rounded px-3 py-1 text-sm hover:bg-blue-600 hover:text-white transition">
                                <i class="fa-solid fa-pen-to-square"></i>
                                Edit
                            </a>
                            <button
                                data-delete-url="/admin/member/delete/<?= esc($m['id']) ?>"
                                class="delete-button flex items-center gap-2 border border-red-600 text-red-600 rounded px-3 py-1 text-sm hover:bg-red-600 hover:text-white transition">
                                <i class="fa-solid fa-trash"></i>
                                Delete
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.getAttribute('data-delete-url');

            Swal.fire({
                title: 'Yakin ingin menghapus member ini?',
                text: "Data yang sudah dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect ke URL hapus
                    window.location.href = url;
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>