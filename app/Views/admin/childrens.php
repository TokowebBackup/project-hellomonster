<?php // File: app/Views/admin/children/index.php 
?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800">Children List</h2>
    </div>
    <table class="w-full text-sm text-left text-gray-700">
        <thead class="bg-gray-100 text-xs uppercase">
            <tr>
                <th class="px-6 py-3">Member</th>
                <th class="px-6 py-3">Name</th>
                <th class="px-6 py-3">Age</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            <?php foreach ($children as $child): ?>
                <tr>
                    <td class="px-6 py-4"><?= esc($child['member_name']) ?></td>
                    <td class="px-6 py-4"><?= esc($child['name']) ?></td>
                    <td class="px-6 py-4"><?= esc($child['age']) ?> Tahun</td>
                    <td class="px-6 py-4 flex gap-2">
                        <a href="<?= base_url('admin/children/edit/' . $child['id']) ?>"
                            class="border border-blue-600 text-blue-600 rounded px-3 py-1 hover:bg-blue-600 hover:text-white transition flex items-center gap-2">
                            <i class="fa fa-edit"></i> Edit
                        </a>

                        <button type="button"
                            class="border border-red-600 text-red-600 rounded px-3 py-1 hover:bg-red-600 hover:text-white transition flex items-center gap-2 btn-delete"
                            data-id="<?= $child['id'] ?>">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </td>

                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const childId = this.getAttribute('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "This action can't be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat form POST untuk hapus dan submit otomatis
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?= base_url('admin/children/delete/') ?>' + childId;

                    // Optional: CSRF token jika diaktifkan, contoh:
                    <?php if (function_exists('csrf_token')) : ?>
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '<?= csrf_token() ?>';
                        csrfInput.value = '<?= csrf_hash() ?>';
                        form.appendChild(csrfInput);
                    <?php endif; ?>

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>