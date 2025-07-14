<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?php if (session()->getFlashdata('message')): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
        <?= session()->getFlashdata('message') ?>
    </div>
<?php elseif (session()->getFlashdata('error')): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif ?>
<div class="max-w-xl mx-auto mt-0 bg-white p-2  rounded">
    <!-- Data Member -->
    <h2 class="text-lg font-bold text-gray-800 mb-2"><?= lang('Membership.personal_data') ?></h2>

    <div class="border rounded p-4 mb-4">
        <div class="flex justify-between mb-1">
            <span class="text-sm text-gray-500"><?= lang('Membership.full_name') ?></span>
            <span class="text-sm font-medium"><?= esc($member['name']) ?></span>
        </div>
        <div class="flex justify-between mb-1">
            <span class="text-sm text-gray-500"><?= lang('Membership.email') ?></span>
            <span class="text-sm font-medium"><?= esc($member['email']) ?></span>
        </div>
        <div class="flex justify-between mb-1">
            <span class="text-sm text-gray-500"><?= lang('Membership.phone_number') ?></span>
            <span class="text-sm font-medium"><?= esc($member['phone']) ?></span>
        </div>
        <div class="flex justify-between">
            <span class="text-sm text-gray-500"><?= lang('Membership.birthdate') ?></span>
            <span class="text-sm font-medium"><?= date('d M Y', strtotime($member['birthdate'])) ?></span>
        </div>

        <?php if ($signature): ?>
            <div class="mt-4 mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Your Signature:</h3>
                <img src="<?= esc($signature['signature']) ?>" alt="Signature" class="border rounded w-full max-h-48 object-contain bg-white">
                <p class="text-xs text-gray-500 mt-1">Signed at <?= date('d M Y H:i', strtotime($signature['signed_at'])) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <button onclick="location.href='/membership/profile'" class="w-full border border-red-600 text-red-600 rounded py-2 font-medium mb-6">
        <?= lang('Membership.edit') ?>
    </button>

    <!-- Minors -->
    <h2 class="text-lg font-bold text-gray-800 mb-3"><?= lang('Membership.any_minors') ?></h2>


    <?php if (count($children) > 0): ?>
        <div class="space-y-3 mb-6 max-h-64 overflow-y-auto pr-1">
            <?php foreach ($children as $child): ?>
                <div class="border rounded px-4 py-3">
                    <div class="flex justify-between mb-2">
                        <div>
                            <p class="text-sm text-gray-500"><?= lang('Membership.children_name') ?></p>
                            <p class="text-sm font-medium"><?= esc($child['name']) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500"><?= lang('Membership.birth_date') ?></p>
                            <p class="text-sm font-medium"><?= date('d M Y', strtotime($child['birthdate'])) ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500"><?= lang('Membership.gender') ?></p>
                            <p class="text-sm font-medium"><?= esc($child['gender'] === 'female' ? '🚺' : '🚹') ?> <?= esc($child['gender']) ?></p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <!-- Tombol Edit -->
                        <button type="button"
                            onclick="editChild('<?= esc($child['id']) ?>')"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-1.5 rounded flex items-center justify-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l6-6m2-2l2 2m-2 2L9 19H5v-4L15.232 5.232z" />
                            </svg>
                            <?= lang('Membership.edit') ?>
                        </button>

                        <!-- Tombol Remove -->
                        <form action="/waiver/children/delete/<?= esc($child['id']) ?>" method="post" onsubmit="return confirmDelete(event)" class="flex-1">
                            <?= csrf_field() ?>
                            <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-1.5 rounded flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <?= lang('Membership.remove') ?>
                            </button>
                        </form>
                    </div>
                </div>

            <?php endforeach ?>
        </div>
    <?php endif; ?>

    <!-- Tombol Modal -->
    <div class="text-center">
        <button onclick="openModal()" class="w-full text-blue-700 border border-blue-600 rounded py-2 font-semibold flex items-center justify-center gap-2 mb-6">
            <span>+</span> <?= lang('Membership.add_minors') ?>
        </button>
    </div>

    <!-- Tombol Next -->
    <form action="/waiver/sign" method="get">
        <input type="hidden" name="id" value="<?= esc($uuid) ?>">
        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 rounded">
            <?= lang('Membership.next') ?>
        </button>
    </form>
</div>

<?= $this->section('modals') ?>
<!-- Modal Add Minor -->
<div id="minorModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded p-6 w-full max-w-md shadow-lg relative">
        <h3 id="modalTitle" class="text-lg font-semibold mb-4">Add Minors</h3>
        <form id="minorForm" action="/waiver/children/add" method="post" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="member_uuid" value="<?= esc($uuid) ?>">
            <input type="hidden" name="id" id="child-id" />

            <div class="relative">
                <input
                    type="text"
                    name="name"
                    id="children-name"
                    required
                    placeholder=" "
                    class="peer w-full border border-gray-300 rounded px-3 pt-5 pb-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                <label for="children-name"
                    class="absolute left-3 top-2 text-sm text-gray-500 transition-all peer-placeholder-shown:top-3.5 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-sm peer-focus:text-blue-600">
                    <?= lang('Membership.children_name') ?>
                </label>
            </div>

            <div>
                <label class="block text-sm mb-1"><?= lang('Membership.birth_date') ?></label>
                <div class="flex gap-2">
                    <select name="birth_month" id="birth-month" class="w-1/3 border px-2 py-1 rounded" required>
                        <option value=""><?= lang('Membership.birth_month') ?></option>
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>"><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                        <?php endfor ?>
                    </select>
                    <select name="birth_day" id="birth-day" class="w-1/3 border px-2 py-1 rounded" required>
                        <option value=""><?= lang('Membership.birth_day') ?></option>
                        <?php for ($d = 1; $d <= 31; $d++): ?>
                            <option value="<?= $d ?>"><?= $d ?></option>
                        <?php endfor ?>
                    </select>
                    <select name="birth_year" id="birth-year" class="w-1/3 border px-2 py-1 rounded" required>
                        <option value=""><?= lang('Membership.birth_year') ?></option>
                        <?php for ($y = date('Y'); $y >= date('Y') - 18; $y--): ?>
                            <option value="<?= $y ?>"><?= $y ?></option>
                        <?php endfor ?>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm mb-1"><?= lang('Membership.gender') ?></label>
                <div class="flex gap-4">
                    <input type="radio" name="gender" value="male" id="gender-male" class="hidden peer/male" required>
                    <label for="gender-male" class="gender-label-male px-4 py-2 border rounded cursor-pointer peer-checked/male:bg-white peer-checked/male:text-gray-900 peer-checked/male:ring-2 peer-checked/male:ring-blue-500 text-gray-700 bg-gray-100 hover:bg-gray-200 transition-all">
                        <?= lang('Membership.male') ?>
                    </label>

                    <input type="radio" name="gender" value="female" id="gender-female" class="hidden peer/female" required>
                    <label for="gender-female" class="gender-label-female px-4 py-2 border rounded cursor-pointer peer-checked/female:bg-blue-600 peer-checked/female:text-white text-gray-700 bg-gray-100 hover:bg-gray-200 transition-all">
                        <?= lang('Membership.female') ?>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-100 text-gray-800 rounded"><?= lang('Membership.cancel') ?></button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded"><?= lang('Membership.save') ?></button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>

<?php if (empty($children)): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: 'info',
                title: '<?= lang('Membership.alert_title') ?>',
                text: '<?= lang('Membership.alert_text') ?>',
                confirmButtonText: 'OK'
            });
        });
    </script>
<?php endif; ?>

<script>
    function openModal(isEdit = false, child = null) {
        const modal = document.getElementById('minorModal');
        const form = document.getElementById('minorForm');
        const title = document.getElementById('modalTitle');

        // Reset form
        form.reset();

        if (isEdit && child) {
            // Set edit mode
            title.innerText = 'Edit Minor';
            form.action = '/waiver/children/update/' + child.id;
            document.getElementById('child-id').value = child.id;
            document.getElementById('children-name').value = child.name;

            const birth = new Date(child.birthdate);
            document.getElementById('birth-year').value = birth.getFullYear();
            document.getElementById('birth-month').value = birth.getMonth() + 1;
            document.getElementById('birth-day').value = birth.getDate();

            document.getElementById('gender-' + child.gender).checked = true;
        } else {
            title.innerText = 'Add Minor';
            form.action = '/waiver/children/add';
        }

        modal.classList.remove('hidden');
    }

    function editChild(id) {
        fetch(`/waiver/children/get/${id}`)
            .then(res => res.json())
            .then(child => openModal(true, child))
            .catch(err => alert('Failed to fetch data.'));
    }

    function closeModal() {
        document.getElementById('minorModal').classList.add('hidden');
    }

    function confirmDelete(event) {
        event.preventDefault();
        const form = event.target;

        Swal.fire({
            title: '<?= lang('Membership.confirm_delete_title') ?>',
            text: '<?= lang('Membership.confirm_delete_text') ?>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });

        return false;
    }
</script>


<?= $this->endSection() ?>