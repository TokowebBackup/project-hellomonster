<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<h1 class="text-xl font-semibold mb-4">Edit Child</h1>

<form action="<?= base_url('admin/children/update/' . $child['id']) ?>" method="post" class="space-y-4 max-w-md">
    <?= csrf_field() ?>

    <div>
        <label for="member_uuid" class="block mb-1 font-medium">Member</label>
        <select name="member_uuid" id="member_uuid" class="w-full border border-gray-300 rounded px-3 py-2">
            <?php foreach ($members as $member): ?>
                <option value="<?= esc($member['uuid']) ?>" <?= $member['uuid'] === $child['member_uuid'] ? 'selected' : '' ?>>
                    <?= esc($member['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label for="name" class="block mb-1 font-medium">Child Name</label>
        <input type="text" name="name" id="name" value="<?= esc($child['name']) ?>" class="w-full border border-gray-300 rounded px-3 py-2" required />
    </div>

    <div>
        <label for="birthdate" class="block mb-1 font-medium">Birthdate</label>
        <input type="date" name="birthdate" id="birthdate" value="<?= esc($child['birthdate']) ?>" class="w-full border border-gray-300 rounded px-3 py-2" required />
    </div>

    <div>
        <label for="gender" class="block mb-1 font-medium">Gender</label>
        <select name="gender" id="gender" class="w-full border border-gray-300 rounded px-3 py-2" required>
            <option value="">-- Select Gender --</option>
            <option value="male" <?= (isset($child['gender']) && $child['gender'] === 'male') ? 'selected' : '' ?>>Male</option>
            <option value="female" <?= (isset($child['gender']) && $child['gender'] === 'female') ? 'selected' : '' ?>>Female</option>
        </select>
    </div>

    <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-blue-700 transition">Update Child</button>
</form>

<?= $this->endSection() ?>