<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>

<div class="bg-white shadow rounded-lg p-6">
    <h3 class="text-lg font-semibold mb-4">Notifications</h3>

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
                            <td class="px-4 py-2 border"><?= $i + 1 ?></td>
                            <td class="px-4 py-2 border font-medium"><?= esc($notif['title']) ?></td>
                            <td class="px-4 py-2 border"><?= esc($notif['message']) ?></td>
                            <td class="px-4 py-2 border"><?= esc($notif['type'] ?? '-') ?></td>
                            <td class="px-4 py-2 border">
                                <?php if ($notif['is_read']) : ?>
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Read</span>
                                <?php else : ?>
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded">Unread</span>
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
</div>

<?= $this->endSection() ?>