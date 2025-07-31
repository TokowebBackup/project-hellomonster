<?= $this->extend('layouts/sign') ?>
<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto p-6 rounded">
    <div style="height: 50vh; overflow: auto;" class="text-left">
        <!-- <h2 class="text-xl font-bold mb-4 text-left"><?= lang('Membership.header_exclusion') ?></h2>
        <h6 class="text-xl font-normal mb-4 text-left"><?= lang('Membership.exclusion_title') ?></h6> -->

        <!-- Scrollable Box untuk Waiver Panjang -->
        <div class="text-sm text-gray-800 leading-relaxed mb-6 space-y-3">
            <?= nl2br($content) ?>
        </div>

        <p class="text-sm text-gray-700 mb-4"><?= lang('Membership.please_sign_below') ?></p>

        <!-- Canvas Signature -->
        <div class="border p-2 rounded bg-gray-50 mb-4">
            <canvas id="signature-pad" class="w-full h-48 border rounded bg-white"></canvas>
            <input type="hidden" id="csrf_token" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

            <button
                type="button"
                onclick="clearCanvas()"
                class="mt-2 inline-flex items-center px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-white font-medium rounded transition-colors duration-200">
                <!-- Icon Clear (Trash Bin) -->
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7L5 7M10 11v6M14 11v6M5 7l1 12a2 2 0 002 2h8a2 2 0 002-2l1-12" />
                </svg>
                Clear Signature
            </button>
        </div>

        <!-- Checkbox Konfirmasi -->
        <div class="mb-4 space-y-2">
            <label class="flex items-start space-x-2">
                <input type="checkbox" id="checkbox1" class="mt-1">
                <span><?= lang('Membership.waiver_notice_en_id') ?></span>
            </label>

            <label class="flex items-start space-x-2">
                <input type="checkbox" id="checkbox2" class="mt-1">
                <span><?= lang('Membership.waiver_confirmation_truth') ?></span>
            </label>
        </div>
    </div>


    <div class="flex justify-between gap-2 mb-6">
        <button type="button" onclick="clearSignature()" class="w-1/2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 rounded">
            <?= lang('Membership.decline'); ?>
        </button>
        <button
            type="button"
            id="submitBtn"
            onclick="submitSignature()"
            class="w-1/2 text-gray-400 bg-gray-100 cursor-not-allowed font-bold py-2 rounded transition-all duration-200"
            disabled>
            <?= lang('Membership.agree'); ?>
        </button>
    </div>
</div>

<!-- Fullscreen Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-white bg-opacity-70 flex items-center justify-center z-50 hidden">
    <div class="text-center">
        <svg class="animate-spin h-10 w-10 text-primary mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
        </svg>
        <p class="text-primary font-medium">Submitting your signature...</p>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas);

    // Resize canvas to match container width
    function clearCanvas() {
        signaturePad.clear();
    }

    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear();
    }

    window.addEventListener("resize", resizeCanvas);
    resizeCanvas();

    function clearSignature() {
        const uuid = '<?= esc($uuid) ?>';
        document.getElementById('loadingOverlay').classList.remove('hidden');
        Swal.fire({
            title: 'Yakin?',
            text: 'Data akan dihapus dan Anda akan kembali ke halaman awal.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, batalkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                signaturePad.clear();
                document.getElementById('loadingOverlay').classList.add('hidden');
                window.location.href = '/waiver/decline?id=' + uuid;
            }
        });
    }

    function submitSignature() {
        if (signaturePad.isEmpty()) {
            Swal.fire('Oops!', 'Please provide your signature.', 'warning');
            return;
        }
        document.getElementById('loadingOverlay').classList.remove('hidden');
        const signatureData = signaturePad.toDataURL();
        const uuid = '<?= esc($uuid) ?>';
        const csrfTokenName = document.getElementById('csrf_token').name;
        const csrfTokenValue = document.getElementById('csrf_token').value;

        const bodyData = new URLSearchParams({
            uuid: uuid,
            signature: signatureData,
        });
        bodyData.append(csrfTokenName, csrfTokenValue); // Tambah token CSRF ke body

        fetch('/waiver/sign/save', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: bodyData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire('Success!', data.message, 'success').then(() => {
                        window.location.href = '/waiver/success?id=<?= esc($uuid) ?>';
                    });
                } else {
                    document.getElementById('loadingOverlay').classList.add('hidden');
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(() => {
                document.getElementById('loadingOverlay').classList.add('hidden');
                Swal.fire('Error', 'Failed to submit signature.', 'error');
            });
    }


    const checkbox1 = document.getElementById('checkbox1');
    const checkbox2 = document.getElementById('checkbox2');
    const submitBtn = document.getElementById('submitBtn');

    function toggleSubmitButton() {
        const enabled = checkbox1.checked && checkbox2.checked;

        submitBtn.disabled = !enabled;

        if (enabled) {
            submitBtn.classList.remove('bg-gray-100', 'text-gray-400', 'cursor-not-allowed');
            submitBtn.classList.add('bg-primary', 'text-white', 'hover:bg-blue-800', 'cursor-pointer');
        } else {
            submitBtn.classList.add('bg-gray-100', 'text-gray-400', 'cursor-not-allowed');
            submitBtn.classList.remove('bg-primary', 'text-white', 'hover:bg-blue-800', 'cursor-pointer');
        }
    }

    checkbox1.addEventListener('change', toggleSubmitButton);
    checkbox2.addEventListener('change', toggleSubmitButton);
</script>

<?= $this->endSection() ?>