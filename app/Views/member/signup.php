<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="font-bold text-xl"><?= lang('Text.signup_title') ?></h2>

<form id="signupForm" action="/membership/register" method="post" class="space-y-5 text-left">
    <?= csrf_field() ?>

    <div>
        <label class="block text-sm text-gray-700 mb-1"><?= lang('Text.email') ?></label>
        <input type="email" name="email" required
            class="block w-full border border-gray-300 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-700" />
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