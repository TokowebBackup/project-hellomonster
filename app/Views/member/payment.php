<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2 class="text-xl font-bold mb-4">Pembayaran Pendaftaran</h2>
<p>Silakan selesaikan pembayaran Rp 25.000 untuk mendaftar.</p>

<div id="snap-container" class="my-6 text-center">
    <button id="pay-button" class="bg-blue-600 text-white px-4 py-2 rounded">Bayar Sekarang</button>
</div>

<!-- Midtrans Snap -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="<?= \App\Libraries\MidtransSnap::getClientKey() ?>"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const userEmail = '<?= esc($email) ?>';
</script>
<script>
    document.getElementById('pay-button').addEventListener('click', function() {
        // const csrfName = '<?= csrf_token() ?>';

        snap.pay("<?= $snapToken ?>", {
            onSuccess: function(result) {
                result.customer_details = {
                    email: userEmail // inject dari PHP, bukan dari metadata
                };
                fetch('/membership/payment-callback', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(result),
                    // [csrfName]: csrfName
                })
                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran Berhasil',
                    text: 'Silakan cek email untuk aktivasi akun.',
                    confirmButtonColor: '#3085d6'
                }).then(() => {
                    window.location.href = '/membership?success=1';
                });
            },
            onPending: function(result) {
                Swal.fire({
                    icon: 'info',
                    title: 'Pembayaran Diproses',
                    text: 'Pembayaran Anda masih dalam proses.',
                    confirmButtonColor: '#3085d6'
                });
            },
            onError: function(result) {
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Gagal',
                    text: 'Terjadi kesalahan dalam proses pembayaran.',
                    confirmButtonColor: '#d33'
                });
            },
            onClose: function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Dibatalkan',
                    text: 'Anda membatalkan pembayaran.',
                    confirmButtonColor: '#f6c23e'
                });
            }
        });
    });
</script>

<?= $this->endSection() ?>