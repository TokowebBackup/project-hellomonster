<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login | Hellomonster</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            safelist: [
                'peer-placeholder-shown:top-4',
                'peer-placeholder-shown:text-base',
                'peer-placeholder-shown:text-gray-400',
                'peer-focus:top-2',
                'peer-focus:text-sm',
                'peer-focus:text-primary'
            ],
            theme: {
                extend: {
                    colors: {
                        primary: '#016BAF',
                        softgray: '#EDECE6',
                    },
                    fontFamily: {
                        primary: ['Jaro', 'sans-serif'],
                        secondary: ['"Poiret One"', 'cursive'],
                    }
                }
            }
        };
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Jaro&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .font-primary {
            font-family: 'Jaro', sans-serif;
        }
    </style>
</head>

<body class="bg-softgray min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <div class="flex justify-center p-4">
            <?php if (!empty($logo_src)) : ?>
                <img src="<?= base_url() ?>/assets/img/Hello-Monster_Branding-Phase-1 - 1-_page-0003e2.png" alt="hellomonster-logo" class="w-36 h-auto" />
            <?php endif; ?>
        </div>
        <h1 class=" text-2xl font-semibold text-center text-primary mb-4 font-primary">Admin Login</h1>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-100 text-red-700 px-4 py-2 mb-4 rounded text-sm">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('admin/login') ?>" class="space-y-6">
            <?= csrf_field() ?>

            <!-- Floating Label Email -->
            <div class="relative">
                <input
                    type="email"
                    name="email"
                    id="email"
                    required
                    placeholder=" "
                    class="peer w-full border border-gray-300 rounded px-3 pt-5 pb-2 focus:outline-none focus:ring focus:ring-primary" />
                <label for="email" class="absolute left-3 top-2 text-sm text-gray-600 transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-sm peer-focus:text-primary">
                    Email
                </label>
            </div>

            <!-- Floating Label Password + Toggle -->
            <div class="relative">
                <input
                    type="password"
                    name="password"
                    id="password"
                    required
                    placeholder=" "
                    class="peer w-full border border-gray-300 rounded px-3 pt-5 pb-2 focus:outline-none focus:ring focus:ring-primary" />
                <label for="password" class="absolute left-3 top-2 text-sm text-gray-600 transition-all peer-placeholder-shown:top-4 peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-sm peer-focus:text-primary">
                    Password
                </label>

                <!-- Eye toggle -->
                <button type="button" onclick="togglePassword()" class="absolute right-3 top-4 text-gray-400 hover:text-gray-700 focus:outline-none text-sm">
                    👁️
                </button>
            </div>

            <button type="submit" class="w-full bg-primary text-white py-2 rounded font-medium hover:bg-blue-700 transition">
                Login
            </button>
        </form>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>

</html>