<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="mb-4">
    <h2 class="text-lg font-semibold text-gray-800">Signatures List</h2>
</div>

<?php if (session()->getFlashdata('message')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
        <?= session()->getFlashdata('message') ?>
    </div>
<?php endif; ?>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="mb-4 flex flex-wrap items-center gap-3 p-2 w-full">
        <input type="text" id="searchInput" placeholder="Search member name..."
            class="border border-gray-300 rounded px-3 py-2 text-sm w-full sm:w-64" />
    </div>
    <table class="w-full text-sm text-left text-gray-700">
        <thead class="bg-gray-100 text-xs uppercase">
            <tr>
                <th class="px-6 py-3">ID</th>
                <th class="px-6 py-3">Member Name</th>
                <th class="px-6 py-3">Signed At</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            <?php foreach ($signs as $sign): ?>
                <tr>
                    <td class="px-6 py-4"><?= esc($sign['id']) ?></td>
                    <td class="px-6 py-4"><?= esc($sign['member_name'] ?? 'Unknown') ?></td>
                    <td class="px-6 py-4"><?= date('d M Y H:i', strtotime($sign['created_at'] ?? $sign['signed_at'] ?? '')) ?></td>
                    <td class="px-6 py-4 flex gap-2">
                        <button
                            type="button"
                            class="btn-view border border-blue-600 text-blue-600 rounded px-3 py-1 hover:bg-blue-600 hover:text-white transition flex items-center gap-2"
                            data-id="<?= esc($sign['id']) ?>">
                            <i class="fa fa-eye"></i> View
                        </button>

                        <button type="button"
                            class="border border-red-600 text-red-600 rounded px-3 py-1 hover:bg-red-600 hover:text-white transition flex items-center gap-2 btn-delete"
                            data-id="<?= $sign['id'] ?>">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
            <?php endforeach ?>
            <?php if (empty($signs)): ?>
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No signatures found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="px-4 py-3 flex justify-center">
        <?= $pager->links('signs', 'default_full', ['query' => $query, 'pageParam' => $pageParam]) ?>
    </div>

</div>

<div id="signatureModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg max-w-md w-full p-4 relative">
        <button id="modalCloseBtn" class="absolute top-2 right-2 text-gray-600 hover:text-gray-900 text-xl font-bold">&times;</button>
        <h3 class="text-lg font-semibold mb-4">Signature View</h3>
        <p><strong>Member:</strong> <span id="modalMemberName"></span></p>
        <p><strong>Signed At:</strong> <span id="modalSignedAt"></span></p>
        <div class="mt-4">
            <img id="modalSignatureImage" src="" alt="Signature Image" class="w-full border rounded" />
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.querySelector('tbody');

    searchInput.addEventListener('input', function() {
        const keyword = this.value;

        fetch(`<?= base_url('admin/sign/search?q=') ?>${encodeURIComponent(keyword)}`)
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No signatures found.</td>
                        </tr>
                    `;
                    return;
                }

                data.forEach(sign => {
                    const row = document.createElement('tr');

                    row.innerHTML = `
                        <td class="px-6 py-4">${sign.id}</td>
                        <td class="px-6 py-4">${sign.member_name ?? 'Unknown'}</td>
                        <td class="px-6 py-4">${new Date(sign.created_at ?? sign.signed_at).toLocaleString()}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <button type="button" class="btn-view border border-blue-600 text-blue-600 rounded px-3 py-1 hover:bg-blue-600 hover:text-white transition flex items-center gap-2" data-id="${sign.id}">
                                <i class="fa fa-eye"></i> View
                            </button>
                            <button type="button" class="btn-delete border border-red-600 text-red-600 rounded px-3 py-1 hover:bg-red-600 hover:text-white transition flex items-center gap-2" data-id="${sign.id}">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </td>
                    `;

                    tableBody.appendChild(row);
                });

                // Rebind event handler untuk view & delete
                bindSignatureButtons();
            });
    });

    function bindSignatureButtons() {
        document.querySelectorAll('.btn-view').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const url = '<?= base_url('admin/sign/view/') ?>' + id;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.error) {
                            Swal.fire('Error', data.error, 'error');
                            return;
                        }
                        modalMemberName.textContent = data.member_name || 'Unknown';
                        modalSignedAt.textContent = new Date(data.signed_at || data.created_at).toLocaleString();
                        modalSignatureImage.src = data.signature;

                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                    });
            });
        });

        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const signId = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '<?= base_url('admin/sign/delete/') ?>' + signId;

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
    }

    // Panggil saat awal load
    bindSignatureButtons();
</script>

<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const signId = this.getAttribute('data-id');
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?= base_url('admin/sign/delete/') ?>' + signId;

                    // CSRF token if enabled (adjust if needed)
                    <?php if (function_exists('csrf_token')): ?>
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

    // Modal elements
    const modal = document.getElementById('signatureModal');
    const modalCloseBtn = document.getElementById('modalCloseBtn');
    const modalMemberName = document.getElementById('modalMemberName');
    const modalSignedAt = document.getElementById('modalSignedAt');
    const modalSignatureImage = document.getElementById('modalSignatureImage');

    document.querySelectorAll('.btn-view').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const url = '<?= base_url('admin/sign/view/') ?>' + id;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire('Error', data.error, 'error');
                        return;
                    }
                    modalMemberName.textContent = data.member_name || 'Unknown';
                    modalSignedAt.textContent = new Date(data.signed_at || data.created_at).toLocaleString();
                    modalSignatureImage.src = data.signature;

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                })
                .catch(() => {
                    Swal.fire('Error', 'Failed to load signature data.', 'error');
                });
        });
    });


    modalCloseBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        modalSignatureImage.src = '';
    });
</script>

<?= $this->endSection() ?>