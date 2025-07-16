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
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md px-4 py-6 hidden md:block">
            <div class="mb-8">
                <img src="<?= base_url('assets/img/Hello-Monster_Branding-Phase-1 - 1-_page-0003e2.png') ?>" alt="Logo" class="w-32 mx-auto mb-4" />
                <h1 class="text-center font-primary text-xl text-primary">Admin Panel</h1>
            </div>
            <nav class="space-y-2">
                <a href="<?= base_url('admin/dashboard') ?>" class="block py-2 px-4 rounded hover:bg-primary hover:text-white <?= url_is('admin/dashboard') ? 'bg-primary text-white' : 'text-gray-700' ?>">Dashboard</a>

                <a href="<?= base_url('admin/members') ?>" class="block py-2 px-4 rounded hover:bg-primary hover:text-white <?= url_is('admin/members') ? 'bg-primary text-white' : 'text-gray-700' ?>">Waiver Members</a>

                <a href="<?= base_url('admin/children') ?>" class="block py-2 px-4 rounded hover:bg-primary hover:text-white <?= url_is('admin/waiver/children') ? 'bg-primary text-white' : 'text-gray-700' ?>">Children</a>

                <a href="<?= base_url('admin/sign') ?>" class="block py-2 px-4 rounded hover:bg-primary hover:text-white <?= url_is('admin/waiver/sign') ? 'bg-primary text-white' : 'text-gray-700' ?>">Signatures</a>

                <a href="<?= base_url('admin/logout') ?>" class="block py-2 px-4 text-red-600 hover:text-white hover:bg-red-600 rounded">Logout</a>
            </nav>

        </aside>

        <!-- Main Content -->
        <main class="flex-1 px-4 sm:px-6 py-6">

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
            const sidebar = document.querySelector("aside");
            sidebar.classList.toggle("hidden");
        });
    </script>
</body>

</html>