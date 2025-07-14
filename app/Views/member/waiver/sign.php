<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4 text-center">EXCLUSION AND CONSENT</h2>
    <p class="text-sm text-gray-700 mb-6">
        PLEASE READ THIS DOCUMENT CAREFULLY. By signing this waiver, you acknowledge that all data submitted is correct and complete, and you grant consent for Hellomonster.id to process the data in accordance with applicable regulations. You also agree to release Hellomonster from any legal claims if the information provided is false or misleading.
    </p>

    <p class="text-sm text-gray-700 mb-4">Please sign below:</p>

    <div class="border p-2 rounded bg-gray-50 mb-4">
        <canvas id="signature-pad" class="w-full h-48 border rounded bg-white"></canvas>
        <input type="hidden" id="csrf_token" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
    </div>
    <div class="mb-4 space-y-2">
        <label class="flex items-start space-x-2">
            <input type="checkbox" id="checkbox1" class="mt-1">
            <span>This Waiver and Consent is executed in both English and Bahasa Indonesia. In the event of discrepancy, the Bahasa Indonesia version shall prevail.</span>
        </label>

        <label class="flex items-start space-x-2">
            <input type="checkbox" id="checkbox2" class="mt-1">
            <span>I certify and confirm that the information I have entered is accurate and true.</span>
        </label>
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

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas);

    // Resize canvas to match container width
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
        signaturePad.clear();
    }

    function submitSignature() {
        if (signaturePad.isEmpty()) {
            Swal.fire('Oops!', 'Please provide your signature.', 'warning');
            return;
        }

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
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(() => {
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