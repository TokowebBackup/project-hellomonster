<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Member - Hellomonster</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="<?= base_url('/assets/favicon.ico') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">

    <script>
        tailwind.config = {
            safelist: [
                'bg-primary', 'bg-blue-700',
                'text-white', 'rounded-md',
                'font-semibold', 'font-secondary',
                'bg-green-100', 'text-green-700',
                'bg-red-100', 'text-red-700',
                'peer-placeholder-shown:top-4',
                'peer-placeholder-shown:text-base',
                'peer-placeholder-shown:text-gray-400',
                'peer-focus:top-2',
                'peer-focus:text-sm',
                'peer-focus:text-blue-500',
                'peer-[value=\'\']:top-4',
                'peer-[value=\'\']:text-base',
                'peer-[value=\'\']:text-gray-400'
            ],
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
        }
    </script>
    <!-- HARUS DI BAWAH config -->
    <script src="https://cdn.tailwindcss.com"></script>


    <link href="https://fonts.googleapis.com/css2?family=Jaro&family=Passion+One:wght@300;400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --font-primary: 'Jaro', sans-serif;
            --font-secondary: 'Passion One', cursive;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .font-primary {
            font-family: var(--font-primary);
        }

        .font-secondary {
            font-family: var(--font-secondary);
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-900 font-sans">
    <div class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <header class="bg-white shadow px-4 py-4">
            <div class="max-w-screen-xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="font-bold text-lg">
                    <a href="/">
                        <img src="<?= base_url(); ?>/assets/img/Hello-Monster_Branding-Phase-1 - 1-_page-00071e5.png" alt="hellomonster-logo" class="w-20 sm:w-24 h-auto">
                    </a>
                </div>
                <div class="flex items-center gap-2 flex-wrap justify-center sm:justify-end">
                    <!-- Language Selector -->
                    <form action="<?= base_url('language') ?>" method="post">
                        <?= csrf_field() ?>
                        <select name="lang" onchange="this.form.submit()" class="text-sm border border-gray-300 px-2 py-1 rounded">
                            <option value="en" <?= service('request')->getLocale() === 'en' ? 'selected' : '' ?>>English</option>
                            <option value="id" <?= service('request')->getLocale() === 'id' ? 'selected' : '' ?>>Indonesia</option>
                        </select>
                    </form>

                    <!-- Logout -->
                    <a href="/logout" class="text-sm text-red-500 hover:underline">Logout</a>
                </div>
            </div>
        </header>


        <!-- Content -->
        <main class="flex-1 container mx-auto py-6">
            <?= $this->renderSection('content') ?>
        </main>

        <!-- Footer -->
        <footer class="bg-white text-center text-gray-500 text-sm py-4 border-t">
            &copy; <?= date('Y') ?> Hellomonster. All rights reserved.
        </footer>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            feather.replace();
        });
    </script>
</body>

</html>