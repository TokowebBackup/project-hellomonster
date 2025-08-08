<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="bg-white shadow rounded-lg overflow-hidden p-6">
    <h2 class="text-2xl font-semibold mb-6 text-gray-800">Change Password</h2>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-500 text-white p-3 rounded mb-4">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="bg-green-500 text-white p-3 rounded mb-4">
            <?= session()->getFlashdata('message') ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/change-password') ?>" method="post">
        <?= csrf_field() ?> <!-- Include CSRF token here -->
        <div class="mb-4">
            <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
            <div class="relative">
                <input type="password" name="current_password" id="current_password" required class="mt-1 block w-full h-12 border border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 transition duration-150 ease-in-out">
                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500" onclick="togglePasswordVisibility('current_password')">
                    <i id="current_password_icon" class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        <div class="mb-4">
            <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
            <div class="relative">
                <input type="password" name="new_password" id="new_password" required class="mt-1 block w-full h-12 border border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 transition duration-150 ease-in-out">
                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500" onclick="togglePasswordVisibility('new_password')">
                    <i id="new_password_icon" class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        <div class="mb-4">
            <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
            <div class="relative">
                <input type="password" name="confirm_password" id="confirm_password" required class="mt-1 block w-full h-12 border border-gray-300 rounded-md shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 transition duration-150 ease-in-out">
                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500" onclick="togglePasswordVisibility('confirm_password')">
                    <i id="confirm_password_icon" class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-md hover:bg-blue-700 transition duration-150 ease-in-out">Change Password</button>
    </form>
</div>

<script>
    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '_icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>

<?= $this->endSection() ?>