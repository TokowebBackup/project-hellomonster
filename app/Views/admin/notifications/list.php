<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="bg-white shadow rounded-lg p-6">
    <div class="flex justify-between items-center mb-4 mt-3">
        <h3 class="text-lg font-semibold">Notifications</h3>
        <form id="deleteAllForm" action="<?= base_url('admin/notifications/delete_all') ?>" method="post">
            <?= csrf_field() ?>
            <button type="button" id="deleteAllBtn" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                Delete All
            </button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left border">#</th>
                    <th class="px-4 py-2 text-left border">Title</th>
                    <th class="px-4 py-2 text-left border">Message</th>
                    <th class="px-4 py-2 text-left border">Type</th>
                    <th class="px-4 py-2 text-left border">Status</th>
                    <th class="px-4 py-2 text-left border">Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($notifications)) : ?>
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">No notifications found.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($notifications as $i => $notif) : ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border">
                                <?= ($pager->getCurrentPage() - 1) * $pager->getPerPage() + ($i + 1) ?>
                            </td>
                            <td class="px-4 py-2 border font-medium"><?= esc($notif['title']) ?></td>
                            <td class="px-4 py-2 border"><?= esc($notif['message']) ?></td>
                            <td class="px-4 py-2 border"><?= esc($notif['type'] ?? '-') ?></td>
                            <td class="px-4 py-2 border">
                                <?php if ($notif['is_read']) : ?>
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Read</span>
                                <?php else : ?>
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded">Unread</span>
                                    <button
                                        type="button"
                                        class="markReadBtn ml-2 px-2 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600"
                                        data-id="<?= $notif['id'] ?>">
                                        Mark as Read
                                    </button>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2 border text-sm text-gray-500">
                                <?= date('Y-m-d H:i', strtotime($notif['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>

    <!-- ✅ Pagination -->
    <div class="mt-4">
        <?= $pager->links() ?>
    </div>
</div>

<script>
    document.getElementById('deleteAllBtn').addEventListener('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "All notifications will be deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete all!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteAllForm').submit();
            }
        });
    });

    document.querySelectorAll('.markReadBtn').forEach(function(button) {
        button.addEventListener('click', function() {
            let notifId = this.dataset.id;
            let btn = this;

            fetch('<?= base_url("admin/notifications/mark-read") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'id=' + notifId + '&<?= csrf_token() ?>=' + '<?= csrf_hash() ?>'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'ok') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Marked as Read',
                            showConfirmButton: false,
                            timer: 1000
                        });

                        // ubah tampilan di table
                        let parentTd = btn.parentElement;
                        parentTd.innerHTML = '<span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Read</span>';
                    }
                })
                .catch(err => {
                    Swal.fire('Error', 'Failed to mark as read', 'error');
                });
        });
    });
</script>

<?= $this->endSection() ?>