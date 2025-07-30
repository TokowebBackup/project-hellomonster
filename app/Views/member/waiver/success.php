<?= $this->extend('layouts/success') ?>
<?= $this->section('content') ?>

<div class="flex flex-col items-center justify-center space-y-4 text-center">
    <svg viewBox="0 0 24 24" class="w-16 h-16 text-green-600" style="color: #16a34a;">
        <path fill="currentColor" d="M12,0A12,12,0,1,0,24,12,12.014,12.014,0,0,0,12,0Zm6.927,8.2-6.845,9.289a1.011,1.011,0,0,1-1.43.188L5.764,13.769a1,1,0,1,1,1.25-1.562l4.076,3.261,6.227-8.451A1,1,0,1,1,18.927,8.2Z"></path>
    </svg>
    <p class="text-2xl font-semibold text-emerald-800">Success!</p>
    <p class="text-emerald-600">Your process has been completed successfully.</p>
    <a href="<?= base_url('/start-waiver') ?>" class="inline-block mt-4 px-4 py-2 bg-primary text-white rounded hover:bg-blue-800 transition">
        Go to Homepage
    </a>
</div>

<?= $this->endSection() ?>