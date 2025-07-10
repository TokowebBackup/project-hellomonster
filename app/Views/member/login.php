<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="font-bold text-xl mb-6">Login Member</h2>

<!-- Flash Message -->
<?php if (session()->getFlashdata('error')) : ?>
    <div class="bg-red-100 border border-red-300 text-red-800 text-sm px-4 py-3 rounded mb-4">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<form action="/membership/login" method="post" onsubmit="return showLoading()" class=" bg-white p-6 rounded shadow-md space-y-4 max-w-md">
    <?= csrf_field() ?>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" required class="w-full border px-3 py-2 rounded-md">
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" required class="w-full border px-3 py-2 rounded-md">
    </div>

    <button type="submit" class="w-full py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700">Login</button>
</form>

<a href="/membership" class="block mt-4 text-blue-600 hover:underline">← Kembali</a>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-white bg-opacity-75 z-50 hidden flex items-center justify-center">
    <div class="animate-spin rounded-full h-16 w-16 border-4 border-blue-500 border-t-transparent"></div>
</div>

<script>
    function showLoading() {
        const overlay = document.getElementById('loadingOverlay');
        overlay.classList.remove('hidden');
        return true; // lanjutkan submit form
    }

    const loginForm = document.querySelector('form');
    const loadingOverlay = document.getElementById('loadingOverlay');

    loginForm.addEventListener('submit', function() {
        loadingOverlay.classList.remove('hidden');
    });
</script>

<?= $this->endSection() ?>