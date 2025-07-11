<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="font-bold text-xl mb-6">Login Member</h2>

<!-- Flash Message -->
<?php if (session()->getFlashdata('error')) : ?>
    <div class="bg-red-100 border border-red-300 text-red-800 text-sm px-4 py-3 rounded mb-4">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<form action="/membership/login" method="post" onsubmit="return showLoading()" class="bg-white p-6 rounded shadow-md space-y-6 max-w-md">
    <?= csrf_field() ?>

    <!-- Email -->
    <div class="relative">
        <input type="email" name="email" id="email"
            class="peer w-full px-4 pt-6 pb-2 border border-gray-300 rounded-md placeholder-transparent focus:border-blue-500 focus:ring-0"
            placeholder="Email" required />
        <label for="email"
            class="absolute left-4 top-2 text-sm text-gray-500 transition-all
                   peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400
                   peer-focus:top-2 peer-focus:text-sm peer-focus:text-blue-500">
            Email
        </label>
    </div>

    <!-- Password -->
    <div class="relative">
        <input type="password" name="password" id="password"
            class="peer w-full px-4 pt-6 pb-2 pr-10 border border-gray-300 rounded-md placeholder-transparent focus:border-blue-500 focus:ring-0"
            placeholder="Password" required />
        <label for="password"
            class="absolute left-4 top-2 text-sm text-gray-500 transition-all
                   peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400
                   peer-focus:top-2 peer-focus:text-sm peer-focus:text-blue-500">
            Password
        </label>
        <button type="button" id="togglePassword"
            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-xl select-none">
            👁️
        </button>
    </div>

    <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">
        Login
    </button>
</form>

<a href="/membership" class="block mt-4 text-blue-600 hover:underline">← Kembali</a>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-white bg-opacity-75 z-50 hidden flex items-center justify-center">
    <div class="animate-spin rounded-full h-16 w-16 border-4 border-blue-500 border-t-transparent"></div> <span class="text-blue-800 font-semibold text-1xl">Loading</span>
</div>

<script>
    function showLoading() {
        document.getElementById('loadingOverlay').classList.remove('hidden');
        return true;
    }

    // Toggle view password
    const toggleBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    toggleBtn.addEventListener('click', function() {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        toggleBtn.textContent = isPassword ? '🙈' : '👁️';
    });
</script>

<?= $this->endSection() ?>