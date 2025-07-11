<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="font-bold text-xl mb-6"><?= lang('Text.signup_title') ?></h2>
<?php if (session()->getFlashdata('error')) : ?>
    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>
<form id="signupForm" action="/membership/register" method="post" class="space-y-6 text-left">
    <?= csrf_field() ?>

    <!-- Email Floating Input -->
    <div class="relative">
        <input type="email" name="email" id="email"
            class="peer w-full px-4 pt-6 pb-2 border border-gray-300 rounded-md placeholder-transparent focus:border-blue-500 focus:ring-0"
            placeholder="<?= lang('Text.email') ?>" required />
        <label for="email"
            class="absolute left-4 top-2 text-sm text-gray-500 transition-all
                   peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400
                   peer-focus:top-2 peer-focus:text-sm peer-focus:text-blue-500">
            <?= lang('Text.email') ?>
        </label>
    </div>

    <button type="submit"
        class="w-full py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">
        <?= lang('Text.next') ?>
    </button>

    <a href="/"
        class="block w-full mt-3 text-center py-2 border border-blue-600  text-blue-600 hover:text-gray-700 rounded-md hover:bg-softgray hover:border-white font-semibold">
        <?= lang('Text.fill_waiver') ?>
    </a>
</form>

<!-- Loading Overlay -->
<div id="loadingOverlay"
    class="fixed inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-50 hidden">
    <div class="flex items-center space-x-2 text-blue-600">
        <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10"
                stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
            </path>
        </svg>
        <span class="text-lg font-medium">Mengirim email aktivasi...</span>
    </div>
</div>

<script>
    document.getElementById('signupForm').addEventListener('submit', function() {
        document.getElementById('loadingOverlay').classList.remove('hidden');
    });
</script>

<?= $this->endSection() ?>