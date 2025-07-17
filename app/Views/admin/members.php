<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="px-4 sm:px-6 py-4 border-b">
        <h2 class="text-lg sm:text-xl font-semibold text-gray-800">List of Waiver Members</h2>
        <p class="text-sm text-gray-500">Berikut adalah data member yang sudah mengisi form waiver.</p>
    </div>

    <div class="w-full overflow-auto">
        <form method="get" class="px-4 sm:px-6 py-4 border-b flex flex-wrap items-center gap-3">
            <input type="text" name="keyword" id="searchInput" value="<?= esc($keyword ?? '') ?>" placeholder="Cari nama, email, atau negara..."
                class="border border-gray-300 rounded px-3 py-2 text-sm w-full sm:w-64" />
            <select name="country" id="filterCountry" class="border border-gray-300 rounded px-3 py-2 text-sm w-full sm:w-48">
                <option value="">Filter Negara</option>
                <?php foreach ($countries as $c): ?>
                    <option value="<?= esc($c) ?>" <?= ($c == ($selectedCountry ?? '')) ? 'selected' : '' ?>><?= esc($c) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 transition">Filter</button>
        </form>

        <table class="w-full sm:min-w-[640px] text-xs sm:text-sm text-left text-gray-600">
            <thead class="bg-gray-100 text-[10px] sm:text-xs uppercase text-gray-700">
                <tr>
                    <th class="px-4 sm:px-6 py-3 whitespace-nowrap">Name</th>
                    <th class="px-4 sm:px-6 py-3 whitespace-nowrap">Email</th>
                    <th class="px-4 sm:px-6 py-3 whitespace-nowrap">Phone</th>
                    <th class="px-4 sm:px-6 py-3 whitespace-nowrap">Country</th>
                    <th class="px-4 sm:px-6 py-3 whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200" id="memberTable">
                <?php foreach ($members as $m): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap"><?= esc($m['name']) ?></td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap"><?= esc($m['email']) ?></td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap"><?= esc($m['phone']) ?></td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <?php if (!empty($m['country_code'])): ?>
                                    <img src="https://flagcdn.com/w20/<?= esc($m['country_code']) ?>.png" alt="<?= esc($m['country']) ?>" class="inline-block w-5 h-auto rounded-sm">
                                <?php endif; ?>
                                <span><?= esc($m['country']) ?></span>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap flex gap-2">
                            <a href="/admin/member/edit/<?= esc($m['id']) ?>"
                                class="flex items-center gap-2 border border-blue-600 text-blue-600 rounded px-3 py-1 text-sm hover:bg-blue-600 hover:text-white transition">
                                <i class="fa-solid fa-pen-to-square"></i>
                                Edit
                            </a>
                            <button
                                data-delete-url="/admin/member/delete/<?= esc($m['id']) ?>"
                                class="delete-button flex items-center gap-2 border border-red-600 text-red-600 rounded px-3 py-1 text-sm hover:bg-red-600 hover:text-white transition">
                                <i class="fa-solid fa-trash"></i>
                                Delete
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchInput');
    const countrySelect = document.getElementById('filterCountry');
    const tableBody = document.getElementById('memberTable');
    let debounceTimeout;

    function fetchMembers() {
        const q = searchInput.value;
        const country = countrySelect.value;

        fetch(`<?= base_url('admin/member/search') ?>?q=${encodeURIComponent(q)}&country=${encodeURIComponent(country)}`)
            .then(res => res.json())
            .then(data => {
                tableBody.innerHTML = '';

                if (data.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No members found.</td>
                        </tr>`;
                    return;
                }

                data.forEach(m => {
                    const flagIcon = m.country_code ?
                        `<img src="https://flagcdn.com/w20/${m.country_code}.png" alt="${m.country}" class="inline-block w-5 h-auto rounded-sm">` :
                        '';
                    tableBody.innerHTML += `
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">${m.name}</td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">${m.email}</td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">${m.phone}</td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">${flagIcon}<span>${m.country}</span></div>
                            </td>
                            <td class="px-4 sm:px-6 py-4 whitespace-nowrap flex gap-2">
                                <a href="/admin/member/edit/${m.id}" class="flex items-center gap-2 border border-blue-600 text-blue-600 rounded px-3 py-1 text-sm hover:bg-blue-600 hover:text-white transition">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <button data-delete-url="/admin/member/delete/${m.id}" class="delete-button flex items-center gap-2 border border-red-600 text-red-600 rounded px-3 py-1 text-sm hover:bg-red-600 hover:text-white transition">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    `;
                });

                attachDeleteEvents();
            })
            .catch(err => console.error('Error:', err));
    }

    function attachDeleteEvents() {
        document.querySelectorAll('.delete-button').forEach(button => {
            button.addEventListener('click', function() {
                const url = this.getAttribute('data-delete-url');

                Swal.fire({
                    title: 'Yakin ingin menghapus member ini?',
                    text: "Data yang sudah dihapus tidak bisa dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
            });
        });
    }

    // Realtime input
    searchInput.addEventListener('input', () => {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(fetchMembers, 300);
    });

    // Change country filter
    countrySelect.addEventListener('change', fetchMembers);
</script>


<script>
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.getAttribute('data-delete-url');

            Swal.fire({
                title: 'Yakin ingin menghapus member ini?',
                text: "Data yang sudah dihapus tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e3342f',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect ke URL hapus
                    window.location.href = url;
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>