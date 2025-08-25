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