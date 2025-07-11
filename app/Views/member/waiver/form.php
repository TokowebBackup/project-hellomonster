<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="max-w-xl mx-auto mt-10 bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4"><?= lang('Membership.waiver_title') ?></h2>

    <form id="waiverForm" action="/waiver/save" method="post" class="space-y-6">
        <?= csrf_field() ?>
        <!-- Stepper -->
        <div class="flex justify-between mb-6">
            <div class="step-indicator text-blue-600 font-bold"><?= lang('Membership.step_1') ?></div>
            <div class="step-indicator text-gray-400"><?= lang('Membership.step_2') ?></div>
        </div>

        <!-- Step 1 -->
        <div id="step1" class="step">
            <input type="hidden" name="email" value="<?= esc($member['email']) ?>">
            <input type="hidden" name="id" value="<?= $member['id'] ?>">

            <div class="mb-6">
                <input type="text" name="name" required placeholder="<?= lang('Membership.full_name') ?>" class="w-full border px-3 py-2 rounded-md">
            </div>

            <div class="mb-6">
                <input id="phone" name="phone" type="tel" required
                    class="w-full border px-3 py-2 rounded-md"
                    placeholder="<?= lang('Membership.phone_number') ?>">
            </div>
        </div>

        <!-- Step 2 -->
        <div id="step2" class="step hidden">
            <div class="mb-6">
                <input type="date" name="birthdate" required placeholder="<?= lang('Membership.birthdate') ?>" class="w-full border px-3 py-2 rounded-md">
            </div>

            <div class="mb-6">
                <select name="country" id="country" required class="w-full border px-3 py-2 rounded-md">
                    <option value=""><?= lang('Membership.country') ?> (<?= lang('Membership.loading_countries') ?>)</option>
                </select>
            </div>

            <div class="mb-6">

                <select name="city" id="city" required class="w-full border px-3 py-2 rounded-md">
                    <option value=""><?= lang('Membership.select_country_first') ?></option>
                </select>
            </div>

            <div>
                <textarea name="address" rows="3" required placeholder="<?= lang('Membership.address') ?>" class="w-full border px-3 py-2 rounded-md"></textarea>
            </div>

            <div class="mb-6">
                <label class="flex items-start gap-2">
                    <input type="checkbox" name="agree_terms" required class="mt-1">
                    <span class="text-sm text-gray-700">
                        <?= lang('Membership.agree_terms') ?>
                    </span>
                </label>
            </div>
        </div>


        <!-- Navigation -->
        <div class="flex justify-between">
            <!-- Tombol Kembali -->
            <button type="button" id="prevBtn" class="hidden text-gray-600 flex items-center gap-1">
                <i data-feather="arrow-left" class="w-4 h-4"></i>
                <?= lang('Membership.back') ?>
            </button>

            <!-- Tombol Lanjut -->
            <button type="button" id="nextBtn" class="text-white bg-blue-600 px-4 py-2 rounded flex items-center gap-1">
                <?= lang('Membership.next') ?>
                <i data-feather="arrow-right" class="w-4 h-4"></i>
            </button>

            <!-- Tombol Submit -->
            <button type="submit" id="submitBtn" class="hidden text-white bg-blue-600 px-4 py-2 rounded flex items-center gap-1">
                <i data-feather="check-circle" class="w-4 h-4"></i>
                <?= lang('Membership.submit') ?>
            </button>

        </div>
    </form>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const phoneInput = document.querySelector("#phone");
        const iti = window.intlTelInput(phoneInput, {
            initialCountry: "id",
            preferredCountries: ["id", "sg", "us"],
            separateDialCode: true,
            nationalMode: false,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
        });
        document.getElementById("waiverForm").addEventListener("submit", function(e) {
            if (iti.isValidNumber()) {
                phoneInput.value = iti.getNumber(); // Format: +628...
            } else {
                e.preventDefault();
                alert("<?= lang('Membership.invalid_phone') ?>");
            }
        });
    });
</script>

<script>
    document.getElementById('phone').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^\d+]/g, ''); // Hanya izinkan angka
    });
</script>


<script>
    const csrfName = '<?= csrf_token() ?>';
    const csrfHash = '<?= csrf_hash() ?>';
    const steps = document.querySelectorAll('.step');
    let currentStep = 0;

    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const submitBtn = document.getElementById('submitBtn');
    const countrySelect = document.getElementById('country');
    const citySelect = document.getElementById('city');

    function showStep(index) {
        steps.forEach((step, i) => {
            step.classList.toggle('hidden', i !== index);
        });
        prevBtn.classList.toggle('hidden', index === 0);
        nextBtn.classList.toggle('hidden', index === steps.length - 1);
        submitBtn.classList.toggle('hidden', index !== steps.length - 1);
    }

    nextBtn.addEventListener('click', () => {
        if (currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
        }
    });

    prevBtn.addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });

    // Fetch countries
    countrySelect.innerHTML = '<option value="">Loading negara...</option>';
    fetch('/api/countries')
        .then(res => res.json())
        .then(data => {
            if (!Array.isArray(data)) {
                console.error('Data bukan array:', data);
                countrySelect.innerHTML = '<option value="">Gagal memuat negara</option>';
                return;
            }

            countrySelect.innerHTML = '<option value="">Pilih negara...</option>';
            data.sort((a, b) => a.localeCompare(b));
            data.forEach(country => {
                const opt = document.createElement('option');
                opt.value = country;
                opt.textContent = country;
                countrySelect.appendChild(opt);
            });
        })
        .catch(err => {
            console.error('Gagal fetch negara:', err);
            countrySelect.innerHTML = '<option value="">Gagal memuat negara</option>';
        });

    // Trigger fetch cities after country selected
    countrySelect.addEventListener('change', function() {
        const selectedCountry = this.value;

        citySelect.innerHTML = '<option value="">Loading kota...</option>';

        if (!selectedCountry) {
            citySelect.innerHTML = '<option value="">Pilih negara dulu...</option>';
            return;
        }

        fetch('/api/cities', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    country: selectedCountry,
                    [csrfName]: csrfHash
                }),
            })
            .then(res => res.json())
            .then(cities => {
                citySelect.innerHTML = '<option value="">Pilih kota...</option>';
                if (Array.isArray(cities)) {
                    cities.forEach(city => {
                        const opt = document.createElement('option');
                        opt.value = city;
                        opt.textContent = city;
                        citySelect.appendChild(opt);
                    });
                } else {
                    citySelect.innerHTML = '<option value="">Data kota tidak ditemukan</option>';
                }
            })
            .catch(err => {
                console.error('Gagal ambil kota:', err);
                citySelect.innerHTML = '<option value="">Gagal memuat kota</option>';
            });
    });

    // Phone auto-format (basic example)
    document.getElementById('phone').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^\d+]/g, '');
    });

    showStep(currentStep);
</script>

<?= $this->endSection() ?>