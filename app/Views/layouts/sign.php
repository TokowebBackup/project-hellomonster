<!DOCTYPE html>
<html lang="<?= service('request')->getLocale() ?>">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/x-icon" href="<?= base_url('/assets/favicon.ico') ?>">

    <title><?= $title ?? 'Hellomonster' ?></title>
    <!-- Harus di atas dulu -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- intl-tel-input CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



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

        .select2-container--default .select2-selection--single {
            height: 2.5rem !important;
            /* sesuai py-2 */
            padding: 0.5rem 0.75rem !important;
            /* sesuai px-3 py-2 */
            border: 1px solid #d1d5db;
            /* border-gray-300 */
            border-radius: 0.375rem;
            /* rounded-md */
            display: flex;
            align-items: center;
            font-size: 1rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            top: 0.5rem;
            right: 0.5rem;
        }
    </style>
</head>

<body class="flex flex-col items-center justify-center min-h-screen bg-white p-6">

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
        <img src="<?= base_url('assets/img/Hello-Monster_Branding-Phase-1 - 1-_page-00071e5.png') ?>" alt="Logo" class="w-[60%]" />
    </div>

    <!-- Content -->
    <div class="mt-6 bg-white text-center space-y-6 w-[500px] max-w-full">
        <?= $this->renderSection('content') ?>
    </div>

    <?= $this->renderSection('modals') ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#city').select2({
                placeholder: 'Ketik nama kota atau kabupaten',
                width: '100%'
            });
        });
        window.addEventListener('DOMContentLoaded', () => {
            feather.replace();
        });
    </script>
</body>

</html>