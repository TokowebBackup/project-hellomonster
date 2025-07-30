<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="max-w-xl mx-auto mt-10 bg-white px-4 py-2 sm:px-6 sm:py-8 rounded">
    <h2 class="text-xl font-bold mb-4"><?= lang('Membership.waiver_title') ?></h2>
    <?php
    $birthdate = isset($member['birthdate']) ? explode('-', $member['birthdate']) : ['', '', ''];
    $birthYear = $birthdate[0] ?? '';
    $birthMonth = $birthdate[1] ?? '';
    $birthDay = $birthdate[2] ?? '';
    ?>
    <form id="waiverForm" action="/waiver/save" method="post" class="space-y-6">
        <?= csrf_field() ?>
        <!-- Stepper -->
        <div class="flex flex-col sm:flex-row sm:justify-between gap-2 sm:gap-0 mb-6 text-center sm:text-left">
            <div class="step-indicator text-blue-600 font-bold"><?= lang('Membership.step_1') ?></div>
            <div class="step-indicator text-gray-400"><?= lang('Membership.step_2') ?></div>
        </div>

        <!-- Step 1 -->
        <div id="step1" class="step">
            <input type="hidden" name="email" value="<?= esc($member['email']) ?>">
            <input type="hidden" name="id" value="<?= $member['id'] ?>">

            <div class="mb-6">
                <input type="text" name="name" value="<?= esc($member['name'] ?? '') ?>" required placeholder="<?= lang('Membership.full_name') ?>" class="w-full border px-3 py-2 rounded-md">
            </div>

            <div class="mb-6">
                <input id="phone" name="phone" value="<?= esc($member['phone'] ?? '') ?>" <?= esc($member['phone'] ?? '') ?> type="tel" required
                    class="w-full border px-3 py-2 rounded-md"
                    placeholder="<?= lang('Membership.phone_number') ?>">
            </div>
        </div>

        <!-- Step 2 -->
        <div id="step2" class="step hidden">
            <!-- <div class="flex gap-2 mb-6">
                <input type="hidden" name="birthdate" id="birthdate" value="<?= esc($member['birthdate'] ?? '') ?>">
                <select name="birth_day" required class="w-1/3 border px-3 py-2 rounded-md">
                    <option value=""><?= lang('Membership.birth_day') ?></option>
                    <?php for ($i = 1; $i <= 31; $i++): ?>
                        <option value="<?= $i ?>" <?= $birthDay == $i ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>

                <select name="birth_month" required class="w-1/3 border px-3 py-2 rounded-md">
                    <option value=""><?= lang('Membership.birth_month') ?></option>
                    <?php foreach (lang('Membership.months') as $num => $name): ?>
                        <option value="<?= $num ?>" <?= ($birthMonth == $num) ? 'selected' : '' ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="birth_year" required class="w-1/3 border px-3 py-2 rounded-md">
                    <option value=""><?= lang('Membership.birth_year') ?></option>
                    <?php for ($y = date('Y'); $y >= 1900; $y--): ?>
                        <option value="<?= $y ?>" <?= ($birthYear == $y) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div> -->

            <!-- <div class="mb-6">
                <label for="birthdate" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                <input type="date" name="birthdate" id="birthdate"
                    value="<?= esc($member['birthdate'] ?? '') ?>"
                    required
                    class="w-full border px-3 py-2 rounded-md" />
            </div> -->
            <div class="mb-6 flex gap-2">
                <!-- Hari -->
                <input type="number" name="birth_day" id="birth_day" min="1" max="31"
                    value="<?= esc($birthDay) ?>" placeholder="<?= lang('Membership.placehoder_day') ?>"
                    required class="w-[35%] border px-3 py-2 rounded-md" />

                <!-- Bulan (Select2) -->
                <select name="birth_month" id="birth_month" required
                    class="select2-custom w-[20%] border px-3 py-2 rounded-md">
                    <option value=""><?= lang('Membership.placehoder_month') ?></option>
                    <?php foreach (lang('Membership.months') as $num => $name): ?>
                        <option value="<?= $num ?>" <?= ($birthMonth == $num) ? 'selected' : '' ?>>
                            <?= $name ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Tahun -->
                <input type="number" name="birth_year" id="birth_year"
                    value="<?= esc($birthYear) ?>" placeholder="<?= lang('Membership.placehoder_year') ?>"
                    min="1900" max="<?= date('Y') ?>"
                    required class="w-[55%] border px-3 py-2 rounded-md" />
            </div>


            <input type="hidden" name="birthdate" id="birthdate" />



            <div class="mb-6">
                <select name="country" id="country" required class="select2 w-full border px-3 py-2 rounded-md">
                    <option value="<?= $member['country'] ?>"><?= lang('Membership.country') ?> (<?= lang('Membership.loading_countries') ?>)</option>
                </select>
            </div>

            <!-- <div class="mb-6">
                <select name="city" id="city" required class="select2 w-full border px-3 py-2 rounded-md">
                    <option value=""><?= lang('Membership.select_country_first') ?></option>
                </select>
            </div>

            <div>
                <textarea name="address" rows="3" required placeholder="<?= lang('Membership.address') ?>" class="w-full border px-3 py-2 rounded-md"></textarea>
            </div> -->

            <div class="mb-6">
                <label class="flex items-start gap-2">
                    <input type="checkbox" name="agree_terms" required class="mt-1" <?= $member['agree_terms'] ? 'checked' : '' ?>>
                    <span class="text-sm text-gray-700">
                        <?= lang('Membership.agree_terms') ?>
                    </span>
                </label>
            </div>
        </div>


        <!-- Navigation -->
        <div class="flex flex-col sm:flex-row justify-between gap-2 mt-6">
            <!-- Tombol Kembali -->
            <button type="button" id="prevBtn" class="hidden text-gray-600 flex items-center gap-1 w-full sm:w-auto">
                <i data-feather="arrow-left" class="w-4 h-4"></i>
                <?= lang('Membership.back') ?>
            </button>

            <!-- Tombol Lanjut -->
            <button type="button" id="nextBtn" class="text-white bg-blue-600 px-4 py-2 rounded flex items-center gap-1 w-full sm:w-auto">
                <?= lang('Membership.next') ?>
                <i data-feather="arrow-right" class="w-4 h-4"></i>
            </button>

            <!-- Tombol Submit -->
            <button type="submit" id="submitBtn" disabled class="text-white bg-blue-600 px-4 py-2 rounded flex items-center gap-1 w-full sm:w-auto">
                <svg id="loadingSpinner" class="animate-spin hidden w-4 h-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <i id="submitIcon" data-feather="check-circle" class="w-4 h-4"></i>
                <span id="submitText"><?= lang('Membership.submit') ?></span>
            </button>

        </div>
    </form>
</div>



<script>
    const agreeCheckbox = document.querySelector('input[name="agree_terms"]');

    function toggleSubmitButton() {
        if (agreeCheckbox.checked && currentStep === steps.length - 1) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
    }

    agreeCheckbox.addEventListener('change', toggleSubmitButton);
    nextBtn.addEventListener('click', toggleSubmitButton);
    prevBtn.addEventListener('click', toggleSubmitButton);
</script>

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

        // document.getElementById("waiverForm").addEventListener("submit", function(e) {
        //     if (iti.isValidNumber()) {
        //         phoneInput.value = iti.getNumber(); // Format: +628...
        //     } else {
        //         e.preventDefault();
        //         Swal.fire({
        //             icon: 'error',
        //             title: 'Oops...',
        //             text: "<?= lang('Membership.invalid_phone') ?>",
        //         });
        //         return;
        //     }

        //     // const day = document.querySelector('[name="birth_day"]').value;
        //     // const month = document.querySelector('[name="birth_month"]').value;
        //     // const year = document.querySelector('[name="birth_year"]').value;

        //     // if (!day || !month || !year) {
        //     //     e.preventDefault();
        //     //     Swal.fire({
        //     //         icon: 'warning',
        //     //         title: 'Tanggal lahir belum lengkap',
        //     //         text: 'Silakan lengkapi tanggal lahir.',
        //     //     });
        //     //     return;
        //     // }

        //     // // Hitung usia
        //     // const birthDate = new Date(`${year}-${month}-${day}`);
        //     // const today = new Date();
        //     // let age = today.getFullYear() - birthDate.getFullYear();
        //     // const m = today.getMonth() - birthDate.getMonth();
        //     // if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        //     //     age--;
        //     // }

        //     // if (age < 18) {
        //     //     e.preventDefault();
        //     //     Swal.fire({
        //     //         icon: 'error',
        //     //         title: "<?= lang('Membership.alert_age_title') ?>",
        //     //         text: "<?= lang('Membership.alert_age_desc') ?>",
        //     //     });
        //     //     return;
        //     // }
        //     // document.getElementById('birthdate').value = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;

        //     const birthdateInput = document.getElementById('birthdate');
        //     const birthdateValue = birthdateInput.value;

        //     if (!birthdateValue) {
        //         e.preventDefault();
        //         Swal.fire({
        //             icon: 'warning',
        //             title: 'Tanggal lahir kosong',
        //             text: 'Silakan isi tanggal lahir.',
        //         });
        //         return;
        //     }

        //     const birthDate = new Date(birthdateValue);
        //     const today = new Date();
        //     let age = today.getFullYear() - birthDate.getFullYear();
        //     const m = today.getMonth() - birthDate.getMonth();
        //     if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        //         age--;
        //     }

        //     if (age < 18) {
        //         e.preventDefault();
        //         Swal.fire({
        //             icon: 'error',
        //             title: "<?= lang('Membership.alert_age_title') ?>",
        //             text: "<?= lang('Membership.alert_age_desc') ?>",
        //         });
        //         return;
        //     }


        // });

        document.getElementById("waiverForm").addEventListener("submit", function(e) {
            if (iti.isValidNumber()) {
                phoneInput.value = iti.getNumber(); // Format: +628...
            } else {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "<?= lang('Membership.invalid_phone') ?>",
                });
                return;
            }

            const day = document.getElementById("birth_day").value;
            const month = document.getElementById("birth_month").value;
            const year = document.getElementById("birth_year").value;

            if (!day || !month || !year) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Lengkapi tanggal lahir',
                    text: 'Hari, bulan, dan tahun harus diisi.',
                });
                return;
            }

            const formattedBirthdate = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
            console.log('formatted birthdate:', formattedBirthdate);

            // Validasi usia minimal 18 tahun
            const birthDate = new Date(`${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            if (age < 18) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: "<?= lang('Membership.alert_age_title') ?>",
                    text: "<?= lang('Membership.alert_age_desc') ?>",
                });
                return;
            }

            // Set nilai input hidden birthdate
            document.getElementById('birthdate').value = formattedBirthdate;

            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitIcon').classList.add('hidden');
            document.getElementById('loadingSpinner').classList.remove('hidden');
            document.getElementById('submitText').textContent = "<?= lang('Membership.loading') ?>";
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
    const selectedCountry = "<?= esc($member['country'] ?? 'Indonesia') ?>";

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
                if (country === selectedCountry) {
                    opt.selected = true; // Set selected jika sama dengan data member
                }
                countrySelect.appendChild(opt);
            });

            $(countrySelect).select2({
                placeholder: "Pilih negara...",
                allowClear: true,
                width: '100%'
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Cek apakah country sudah diisi (misalnya user sudah edit sebelumnya)
        const userCountry = "<?= esc($member['country'] ?? '') ?>";
        if (!userCountry) {
            // Hanya jika belum ada country dari data member
            fetch('https://ipapi.co/json/')
                .then(response => response.json())
                .then(data => {
                    if (data && data.country_name) {
                        const detectedCountry = data.country_name;

                        // Tunggu select2 selesai populate
                        const interval = setInterval(() => {
                            const options = countrySelect.options;
                            for (let i = 0; i < options.length; i++) {
                                if (options[i].value === detectedCountry) {
                                    countrySelect.value = detectedCountry;
                                    $(countrySelect).val(detectedCountry).trigger('change');
                                    clearInterval(interval);
                                    break;
                                }
                            }
                        }, 500);
                    }
                })
                .catch(error => {
                    console.warn("Gagal deteksi lokasi dari IP:", error);
                });
        }
    });
</script>


<?= $this->endSection() ?>