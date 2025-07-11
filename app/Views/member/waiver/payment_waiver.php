<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="max-w-lg mx-auto mt-10 px-4 text-center">
    <h2 class="text-2xl font-bold mb-4">Pembayaran</h2>
    <p class="mb-4">Silakan selesaikan pembayaran untuk melanjutkan ke aktivasi akun.</p>

    <div id="snap-container" class="mb-4"></div>

    <p class="text-sm text-gray-500">Tunggu hingga proses selesai, Anda akan diarahkan secara otomatis.</p>
</div>

<!-- Midtrans Snap -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="<?= \App\Libraries\MidtransSnap::getClientKey() ?>"></script>
<script>
    window.snap.pay("<?= $snapToken ?>", {
        onSuccess: function(result) {
            window.location.href = "/membership?success=1";
        },
        onPending: function(result) {
            window.location.href = "/membership?success=1";
        },
        onError: function(result) {
            alert("Pembayaran gagal! Silakan coba lagi.");
            console.error(result);
        },
        onClose: function() {
            alert("Anda menutup popup tanpa menyelesaikan pembayaran.");
        }
    });
</script>

<?= $this->endSection() ?>