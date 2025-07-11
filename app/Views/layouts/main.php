<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title ?? 'Hellomonster' ?></title>
    <!-- Harus di atas dulu -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- intl-tel-input CSS -->
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

        .iti {
            width: 100%;
            position: relative;
        }

        .iti__country-list {
            max-height: 250px;
            /* batasi tinggi dropdown */
            overflow-y: auto;
            /* biar scrollable */
            z-index: 1000;
            /* pastikan dropdown muncul di atas */
        }

        .iti input {
            width: 100%;
        }
    </style>
</head>

<body class="flex flex-col items-center justify-center min-h-screen bg-white">

    <!-- Top Bar -->
    <div class="relative w-[500px] max-w-full px-4">

        <!-- Language Selector (kanan atas) -->
        <form action="<?= base_url('language') ?>" method="post" class="absolute top-4 right-6">
            <?= csrf_field() ?>
            <select name="lang" onchange="this.form.submit()" class="text-sm border border-gray-300 px-2 py-1 rounded">
                <option value="en" <?= service('request')->getLocale() === 'en' ? 'selected' : '' ?>>English</option>
                <option value="id" <?= service('request')->getLocale() === 'id' ? 'selected' : '' ?>>Indonesia</option>
            </select>
        </form>

        <!-- Logo + Version -->
        <div class="text-sm text-gray-400 mb-1">v<?= env('app.version') ?></div>
        <img src="<?= base_url('assets/img/Hello-Monster_Branding-Phase-1 - 1-_page-00071e4.png') ?>" alt="Logo" class="w-[60%]" />
    </div>

    <!-- Content -->
    <div class="mt-6 bg-white shadow-lg p-10 rounded-lg text-center space-y-6 w-[500px] max-w-full">
        <?= $this->renderSection('content') ?>
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