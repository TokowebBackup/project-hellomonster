<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title ?? 'Admin Dashboard | Hellomonster' ?></title>
    <link rel="icon" type="image/x-icon" href="<?= base_url('/assets/favicon.ico') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#016BAF',
                        secondary: '#F8CD07',
                        softgray: '#EDECE6',
                        purple: '#5D5DA9',
                        green: '#35B043',
                    },
                    fontFamily: {
                        primary: ['Jaro', 'sans-serif'],
                        secondary: ['"Poiret One"', 'cursive'],
                    }
                }
            }
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Jaro&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .font-primary {
            font-family: 'Jaro', sans-serif;
        }

        .sidebar-mobile {
            transition: transform 0.3s ease;
        }

        .sidebar-hidden {
            transform: translateX(-100%);
        }

        .sidebar-visible {
            transform: translateX(0);
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">

    <!-- Sidebar + Content Layout -->
    <div class="flex flex-col md:flex-row min-h-screen w-full">

        <!-- Sidebar -->
        <aside id="sidebar" class="w-full md:w-64 bg-white shadow-md px-4 py-6 hidden md:block fixed md:static top-0 left-0 h-full z-50 md:h-auto md:relative">
            <div class="mb-8">
                <?php if (!empty($logo_src)) : ?>
                    <img src="<?= esc($logo_src) ?>" alt="Logo" class="w-32 mx-auto mb-4" />
                <?php endif; ?>
                <h1 class="text-center font-primary text-xl text-primary">Admin Panel</h1>
            </div>
            <nav class="space-y-2">
                <a href="<?= base_url('admin/dashboard') ?>" class="block py-2 px-4 rounded hover:bg-primary hover:text-white <?= url_is('admin/dashboard') ? 'bg-primary text-white' : 'text-gray-700' ?>">Dashboard</a>

                <a href="<?= base_url('admin/members') ?>" class="block py-2 px-4 rounded hover:bg-primary hover:text-white <?= url_is('admin/members') ? 'bg-primary text-white' : 'text-gray-700' ?>">Waiver Members</a>

                <a href="<?= base_url('admin/children') ?>" class="block py-2 px-4 rounded hover:bg-primary hover:text-white <?= url_is('admin/children') ? 'bg-primary text-white' : 'text-gray-700' ?>">Children</a>

                <a href="<?= base_url('admin/sign') ?>" class="block py-2 px-4 rounded hover:bg-primary hover:text-white <?= url_is('admin/sign') ? 'bg-primary text-white' : 'text-gray-700' ?>">Signatures</a>

                <a href="<?= base_url('admin/settings') ?>" class="block py-2 px-4 rounded hover:bg-primary hover:text-white <?= url_is('admin/settings') ? 'bg-primary text-white' : 'text-gray-700' ?>">Settings</a>

                <a href="<?= base_url('admin/logout') ?>" class="block py-2 px-4 text-red-600 hover:text-white hover:bg-red-600 rounded">Logout</a>
            </nav>


        </aside>

        <!-- Main Content -->
        <main class="flex-1 px-4 sm:px-6 py-6 mt-[64px] md:mt-0">

            <header class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800"><?= $title ?? 'Dashboard' ?></h2>
                    <p class="text-sm text-gray-500">Welcome, <?= session()->get('admin_name') ?></p>
                </div>

                <!-- Notification -->
                <div class="relative">
                    <button id="notifButton" class="relative focus:outline-none">
                        <i class="fas fa-bell text-xl text-gray-700 hover:text-primary"></i>
                        <span id="notifBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-semibold hidden">0</span>
                    </button>

                    <!-- Dropdown -->
                    <div id="notifDropdown" class="absolute right-0 mt-3 w-96 max-w-sm bg-white rounded-lg shadow-xl border border-gray-200 hidden z-50">
                        <div class="p-4 border-b flex items-center justify-between">
                            <h4 class="text-sm font-semibold text-gray-800">Notifications</h4>
                            <button id="markAllRead" class="text-xs text-primary hover:underline">Mark all as read</button>
                        </div>
                        <ul id="notifList" class="divide-y max-h-96 overflow-y-auto">
                            <!-- Notification items will be injected here -->
                        </ul>
                    </div>
                </div>
            </header>


            <!-- Mobile Navigation Toggle -->
            <div class="md:hidden mb-4">
                <button id="toggleSidebar" class="bg-primary text-white px-3 py-2 rounded">
                    ☰ Menu
                </button>
            </div>

            <section>
                <?= $this->renderSection('content') ?>
            </section>
        </main>
    </div>

    <script>
        document.getElementById("toggleSidebar")?.addEventListener("click", () => {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("hidden");
        });
    </script>

    <script>
        const notifButton = document.getElementById('notifButton');
        const notifDropdown = document.getElementById('notifDropdown');
        const notifList = document.getElementById('notifList');
        const notifBadge = document.getElementById('notifBadge');
        const markAllReadBtn = document.getElementById('markAllRead');

        notifButton.addEventListener('click', () => {
            notifDropdown.classList.toggle('hidden');
        });
        let lastNotifId = null;
        let lastNotifCount = 0;

        async function fetchNotifications() {
            const res = await fetch("<?= base_url('admin/notifications') ?>");
            const notifs = await res.json();

            notifList.innerHTML = '';
            let unreadCount = 0;

            if (notifs.length === 0) {
                notifList.innerHTML = `<li class="p-4 text-sm text-gray-500 text-center">No new notifications</li>`;
                notifBadge.classList.add('hidden');
                return;
            }

            let latestNotifId = notifs[0]?.id ?? null;

            notifs.forEach(n => {
                const item = document.createElement('li');
                item.className = 'p-4 hover:bg-gray-50 transition';

                item.innerHTML = `
            <div class="text-sm font-medium text-gray-800">${n.title}</div>
            <div class="text-xs text-gray-600 mt-1">${n.message}</div>
            <div class="text-[10px] text-gray-400 mt-1">${new Date(n.created_at).toLocaleString()}</div>
        `;

                notifList.appendChild(item);
                if (!n.is_read) unreadCount++;
            });

            notifBadge.innerText = unreadCount;
            notifBadge.classList.toggle('hidden', unreadCount === 0);

            // Buka dropdown jika ada notifikasi baru berdasarkan ID
            if (latestNotifId && latestNotifId !== lastNotifId) {
                notifDropdown.classList.remove('hidden'); // Buka otomatis
                lastNotifId = latestNotifId;
            }

            lastNotifCount = unreadCount;
        }


        markAllReadBtn?.addEventListener('click', async () => {
            const res = await fetch("<?= base_url('admin/notifications') ?>");
            const notifs = await res.json();

            await Promise.all(notifs.map(n => {
                return fetch("<?= base_url('admin/notifications/mark-read') ?>", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `id=${n.id}`
                });
            }));

            fetchNotifications();
        });

        // Fetch every 10s
        setInterval(fetchNotifications, 10000);
        fetchNotifications();
    </script>

</body>

</html>


<!-- admin old -->
<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title ?? 'Admin Dashboard | Hellomonster' ?></title>
    <link rel="icon" type="image/x-icon" href="<?= base_url('/assets/favicon.ico') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#016BAF',
                        secondary: '#F8CD07',
                        softgray: '#EDECE6',
                        purple: '#5D5DA9',
                        green: '#35B043',
                    },
                    fontFamily: {
                        primary: ['Jaro', 'sans-serif'],
                        secondary: ['"Poiret One"', 'cursive'],
                    }
                }
            }
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Jaro&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .font-primary {
            font-family: 'Jaro', sans-serif;
        }

        .sidebar-mobile {
            transition: transform 0.3s ease;
        }

        .sidebar-hidden {
            transform: translateX(-100%);
        }

        .sidebar-visible {
            transform: translateX(0);
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">

    <!-- Sidebar + Content Layout -->
    <div class="flex flex-col md:flex-row min-h-screen w-full">

        <!-- Sidebar -->
        <aside id="sidebar" class="w-full md:w-64 bg-white shadow-md px-4 py-6 hidden md:block fixed md:static top-0 left-0 h-full z-50 md:h-auto md:relative">
            <div class="mb-8">
                <?php if (!empty($logo_src)) : ?>
                    <img src="<?= esc($logo_src) ?>" alt="Logo" class="w-32 mx-auto mb-4" />
                <?php endif; ?>
                <h1 class="text-center font-primary text-xl text-primary">Admin Panel</h1>
            </div>
            <nav class="space-y-2">
                <a href="<?= base_url('admin/dashboard') ?>" class="block py-2 px-4 rounded hover:bg-primary hover:text-white <?= url_is('admin/dashboard') ? 'bg-primary text-white' : 'text-gray-700' ?>">Dashboard</a>

                <a href="<?= base_url('admin/members') ?>" class="block py-2 px-4 rounded hover:bg-primary hover:text-white <?= url_is('admin/members') ? 'bg-primary text-white' : 'text-gray-700' ?>">Waiver Members</a>

                <a href="<?= base_url('admin/children') ?>" class="block py-2 px-4 rounded hover:bg-primary hover:text-white <?= url_is('admin/children') ? 'bg-primary text-white' : 'text-gray-700' ?>">Children</a>

                <a href="<?= base_url('admin/sign') ?>" class="block py-2 px-4 rounded hover:bg-primary hover:text-white <?= url_is('admin/sign') ? 'bg-primary text-white' : 'text-gray-700' ?>">Signatures</a>

                <a href="<?= base_url('admin/settings') ?>" class="block py-2 px-4 rounded hover:bg-primary hover:text-white <?= url_is('admin/settings') ? 'bg-primary text-white' : 'text-gray-700' ?>">Settings</a>

                <a href="<?= base_url('admin/logout') ?>" class="block py-2 px-4 text-red-600 hover:text-white hover:bg-red-600 rounded">Logout</a>
            </nav>


        </aside>

        <!-- Main Content -->
        <main class="flex-1 px-4 sm:px-6 py-6 mt-[64px] md:mt-0">

            <header class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-800"><?= $title ?? 'Dashboard' ?></h2>
                <p class="text-sm text-gray-500">Welcome, <?= session()->get('admin_name') ?></p>
            </header>

            <!-- Mobile Navigation Toggle -->
            <div class="md:hidden mb-4">
                <button id="toggleSidebar" class="bg-primary text-white px-3 py-2 rounded">
                    ☰ Menu
                </button>
            </div>

            <section>
                <?= $this->renderSection('content') ?>
            </section>
        </main>
    </div>

    <script>
        document.getElementById("toggleSidebar")?.addEventListener("click", () => {
            const sidebar = document.getElementById("sidebar");
            sidebar.classList.toggle("hidden");
        });
    </script>
</body>

</html>