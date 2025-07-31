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

        .select2-container .select2-selection--single {
            height: 42px !important;
            /* sesuaikan dengan input kamu */
            padding: 6px 12px !important;
            display: flex;
            align-items: center;
            border: 1px solid #d1d5db;
            /* warna border Tailwind gray-300 */
            border-radius: 0.375rem;
            /* rounded-md */
        }

        /* Atur posisi icon dropdown agar center */
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 50% !important;
            transform: translateY(-50%);
        }

        /* Biar font sama */
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-size: 0.875rem;
            /* Tailwind text-sm */
            line-height: 1.25rem;
        }

        .created-at-style {
            background-color: #eef6ff;
            /* background biru muda misal */
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }

        .created-at-style span:first-child {
            color: #2563eb;
            /* biru lebih gelap */
            font-weight: 600;
        }

        .created-at-style span:last-child {
            color: #1d4ed8;
            /* biru pekat */
            font-weight: 700;
        }
    </style>
</head>

<body class="flex flex-col items-center justify-center min-h-screen bg-white p-6">

    <!-- Top Bar -->
    <!-- Di disabled dulu copy lagi dari main layout -->

    <!-- Content -->
    <div class="mt-6 bg-white  p-10 rounded-lg text-center space-y-6 w-[500px] max-w-full">
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
            $('.select2-custom').select2({
                placeholder: "<?= lang('Membership.placehoder_month') ?>",
                allowClear: true,
                width: 'resolve'
            });
            $('#city').select2({
                placeholder: 'Ketik nama kota atau kabupaten',
                width: '100%'
            });
        });
        window.addEventListener('DOMContentLoaded', () => {
            feather.replace();
        });
    </script>
    <script>
        $(document).ready(function() {
            const currentPath = window.location.pathname;
            const currentQuery = window.location.search;

            // Jalankan hanya jika path = "/waiver" dan ada query "id="
            if (currentPath === "/waiver" && currentQuery.includes("id=")) {

                // Inisialisasi Select2
                $('#birth_month_wiver').select2({
                    placeholder: "<?= lang('Membership.placehoder_month') ?>...",
                    allowClear: true,
                    width: '100%'
                });


                // Variabel step
                let currentStep = 0;
                const steps = document.querySelectorAll(".step");
                const nextBtn = document.getElementById("nextBtn");

                function showStep(step) {
                    steps.forEach((s, i) => {
                        s.classList.toggle("hidden", i !== step);
                    });
                }

                function autoOpenSelect2IfNeeded() {
                    if (currentStep === 1) {
                        setTimeout(() => {
                            const $select = $('#birth_month');
                            const select2Container = $select.next('.select2');

                            if (select2Container.is(':visible')) {
                                $select.select2('open');
                            } else {
                                setTimeout(() => {
                                    if ($select.next('.select2').is(':visible')) {
                                        $select.select2('open');
                                    }
                                }, 300);
                            }
                        }, 300);
                    }
                }

                if (nextBtn) {
                    nextBtn.addEventListener("click", () => {
                        if (currentStep < steps.length - 1) {
                            currentStep++;
                            showStep(currentStep);
                            console.log('Step saat ini:', currentStep);
                            autoOpenSelect2IfNeeded();
                        }
                    });
                }

                if (currentStep === 1) {
                    autoOpenSelect2IfNeeded();
                }

                showStep(currentStep);
            }
        });
    </script>

</body>

</html>