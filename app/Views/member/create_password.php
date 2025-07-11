<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="font-bold text-xl mb-6"><?= lang('Text.create_password') ?></h2>

<form action="/membership/save-password" method="post" class="space-y-6 text-left">
    <?= csrf_field() ?>

    <!-- Floating label + emoji toggle -->
    <div class="relative">
        <input type="password" name="password" id="password"
            class="peer w-full px-4 pt-6 pb-2 pr-10 border border-gray-300 rounded-md placeholder-transparent focus:border-blue-500 focus:ring-0"
            placeholder="<?= lang('Text.password') ?>" required />
        <label for="password"
            class="absolute left-4 top-2 text-sm text-gray-500 transition-all
                   peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400
                   peer-focus:top-2 peer-focus:text-sm peer-focus:text-blue-500">
            <?= lang('Text.password') ?>
        </label>

        <!-- Toggle Emoji -->
        <button type="button" id="togglePassword"
            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-xl cursor-pointer select-none">
            👁️
        </button>
    </div>

    <button type="submit" class="w-full py-2 bg-red-500 text-white rounded-md hover:bg-red-600 font-semibold">
        <?= lang('Text.save_password') ?>
    </button>
</form>

<!-- Toggle Password Script -->
<script>
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    toggleBtn.addEventListener('click', function() {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        toggleBtn.textContent = isPassword ? '🙈' : '👁️';
    });
</script>

<?= $this->endSection() ?>