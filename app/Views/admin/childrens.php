<?php // File: app/Views/admin/children/index.php 
?>
<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="p-4 border-b">
        <h2 class="text-lg font-semibold text-gray-800">Children List</h2>
    </div>

    <form method="get" class="p-4 border-b flex flex-wrap gap-3 items-center">
        <input type="text" name="keyword" id="realtimeSearch" value="<?= esc($keyword ?? '') ?>" placeholder="Cari nama anak atau member..."
            class="border border-gray-300 rounded px-3 py-2 text-sm w-full sm:w-64" />

        <select name="member" class="border border-gray-300 rounded px-3 py-2 text-sm w-full sm:w-48">
            <option value="">Filter by Member</option>
            <?php foreach ($memberOptions as $member): ?>
                <option value="<?= esc($member['uuid']) ?>" <?= $selectedMember == $member['uuid'] ? 'selected' : '' ?>>
                    <?= esc($member['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit"
            class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition">Filter</button>
    </form>


    <table class="w-full text-sm text-left text-gray-700">
        <thead class="bg-gray-100 text-xs uppercase">
            <tr>
                <th class="px-6 py-3">Member</th>
                <th class="px-6 py-3">Name</th>
                <th class="px-6 py-3">Age</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y" id="childrenTable">
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

    <div class="px-4 py-3 flex justify-center">
        <?= $pager->links('children', 'default_full', ['query' => $query, 'pageParam' => $pageParam]) ?>
    </div>
</div>

<script>
    const searchInput = document.getElementById('realtimeSearch');
    const tableBody = document.getElementById('childrenTable');

    let debounceTimeout;

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => {
            const keyword = this.value;
            fetch(`<?= base_url('admin/children/search') ?>?q=${encodeURIComponent(keyword)}`)
                .then(res => res.json())
                .then(data => {
                    tableBody.innerHTML = '';

                    if (data.length === 0) {
                        tableBody.innerHTML = `
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No children found.</td>
                            </tr>`;
                        return;
                    }

                    data.forEach(child => {
                        tableBody.innerHTML += `
                            <tr>
                                <td class="px-6 py-4">${child.member_name}</td>
                                <td class="px-6 py-4">${child.name}</td>
                                <td class="px-6 py-4">${child.age} Tahun</td>
                                <td class="px-6 py-4 flex gap-2">
                                    <a href="<?= base_url('admin/children/edit/') ?>${child.id}" class="border border-blue-600 text-blue-600 rounded px-3 py-1 hover:bg-blue-600 hover:text-white transition flex items-center gap-2">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <button type="button" class="border border-red-600 text-red-600 rounded px-3 py-1 hover:bg-red-600 hover:text-white transition flex items-center gap-2 btn-delete" data-id="${child.id}">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                })
                .catch(() => {
                    console.error('Failed to fetch data.');
                });
        }, 300); // debounce
    });
</script>


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