<?= $this->extend('layouts/member') ?>
<?= $this->section('content') ?>

<div class="max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-6"><?= lang('Membership.edit_profile_title') ?></h1>

    <form action="/membership/profile" method="post" class="bg-white p-6 rounded-lg shadow-md space-y-4">
        <?= csrf_field() ?>

        <input type="hidden" name="id" value="<?= esc($member['id']) ?>" />

        <div class="relative">
            <input type="email" name="email" value="<?= esc($member['email']) ?>" class="peer w-full px-4 pt-6 pb-2 border border-gray-300 rounded-md placeholder-transparent focus:border-blue-500 focus:ring-0 focus:outline-none" readonly />
            <label class="absolute left-4 top-2 text-sm text-gray-500 transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-sm peer-focus:text-blue-500"><?= lang('Membership.email') ?></label>
        </div>

        <div class="relative">
            <input type="text" name="name" id="name" value="<?= esc($member['name']) ?>"
                class="peer w-full px-4 pt-6 pb-2 border border-gray-300 rounded-md placeholder-transparent focus:border-blue-500 focus:ring-0 focus:outline-none"
                placeholder="<?= lang('Membership.full_name') ?>" required />

            <label for="name"
                class="absolute left-4 top-2 text-sm text-gray-500 transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-sm peer-focus:text-blue-500">
                <?= lang('Membership.full_name') ?>
            </label>
        </div>

        <div class="relative">
            <input type="tel" name="phone" id="phone"
                value="<?= esc($member['phone']) ?>"
                class="peer w-full px-4 pt-6 pb-2 border border-gray-300 rounded-md placeholder-transparent focus:border-blue-500 focus:ring-0"
                placeholder="<?= lang('Membership.phone_number') ?>" required />
        </div>

        <div class="relative">
            <input type="date" name="birthdate" value="<?= esc($member['birthdate']) ?>" class="peer w-full px-4 pt-6 pb-2 border border-gray-300 rounded-md placeholder-transparent focus:border-blue-500 focus:ring-0 focus:outline-none" required />
            <label class="absolute left-4 top-2 text-sm text-gray-500 transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-sm peer-focus:text-blue-500"><?= lang('Membership.birthdate') ?></label>
        </div>

        <div class="relative w-full">
            <select name="country" id="country" required
                class="peer w-full appearance-none px-4 pt-6 pb-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:border-blue-500">
                <option value="" disabled selected hidden><?= lang('Membership.country') ?></option>
                <!-- diisi via JS -->
            </select>
            <label for="country"
                class="absolute left-4 top-2 text-sm text-gray-500 transition-all 
        peer-focus:top-2 peer-focus:text-sm peer-focus:text-blue-500 
        peer-[value='']:top-4 peer-[value='']:text-base peer-[value='']:text-gray-400">
                <?= lang('Membership.country') ?>
            </label>
        </div>

        <div class="relative w-full">
            <select name="city" id="city" required
                class="peer w-full appearance-none px-4 pt-6 pb-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:border-blue-500">
                <option value="" disabled selected hidden><?= lang('Membership.city') ?></option>
            </select>
            <label for="city"
                class="absolute left-4 top-2 text-sm text-gray-500 transition-all 
        peer-focus:top-2 peer-focus:text-sm peer-focus:text-blue-500 
        peer-[value='']:top-4 peer-[value='']:text-base peer-[value='']:text-gray-400">
                <?= lang('Membership.city') ?>
            </label>
        </div>


        <div class="relative w-full">
            <textarea name="address" id="address" rows="3" required
                class="peer w-full px-4 pt-6 pb-2 border border-gray-300 rounded-md placeholder-transparent focus:outline-none focus:border-blue-500"><?= esc($member['address']) ?></textarea>
            <label for="address"
                class="absolute left-4 top-2 text-sm text-gray-500 transition-all 
        peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 
        peer-focus:top-2 peer-focus:text-sm peer-focus:text-blue-500">
                <?= lang('Membership.address') ?>
            </label>
        </div>


        <button type="submit" class="inline-block mt-6 px-4 py-2 bg-[#016BAF] text-white rounded-md hover:bg-blue-700">
            <?= lang('Membership.save_changes') ?>
        </button>
    </form>

    <a href="/membership/dashboard" class="block mt-4 text-blue-600 hover:underline">← <?= lang('Membership.back_to_dashboard') ?></a>
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
        const itiContainer = phoneInput.closest('.iti');
        if (itiContainer) {
            itiContainer.classList.add('w-full');
        }
        // Optional: Format nomor saat submit
        document.querySelector("form").addEventListener("submit", function(e) {
            if (iti.isValidNumber()) {
                phoneInput.value = iti.getNumber(); // Format: +62xxx
            } else {
                e.preventDefault();
                alert("<?= lang('Membership.invalid_phone') ?>");
            }
        });

        // Format hanya angka
        phoneInput.addEventListener("input", function() {
            this.value = this.value.replace(/[^\d+]/g, '');
        });

        // Fetch countries
        const countrySelect = document.getElementById("country");
        const citySelect = document.getElementById("city");
        const csrfName = '<?= csrf_token() ?>';
        const csrfHash = '<?= csrf_hash() ?>';

        countrySelect.innerHTML = '<option value="">Loading...</option>';
        fetch('/api/countries')
            .then(res => res.json())
            .then(data => {
                countrySelect.innerHTML = '<option value="">Pilih negara...</option>';
                data.sort((a, b) => a.localeCompare(b));
                data.forEach(country => {
                    const opt = document.createElement("option");
                    opt.value = country;
                    opt.textContent = country;
                    if (country === "<?= esc($member['country']) ?>") opt.selected = true;
                    countrySelect.appendChild(opt);
                });

                // Trigger load cities if country already selected
                if (countrySelect.value) countrySelect.dispatchEvent(new Event('change'));
            });

        countrySelect.addEventListener("change", function() {
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
                    })
                })
                .then(res => res.json())
                .then(cities => {
                    citySelect.innerHTML = '<option value="">Pilih kota...</option>';
                    cities.forEach(city => {
                        const opt = document.createElement("option");
                        opt.value = city;
                        opt.textContent = city;
                        if (city === "<?= esc($member['city']) ?>") opt.selected = true;
                        citySelect.appendChild(opt);
                    });
                });
        });
    });
</script>
<?= $this->endSection() ?>