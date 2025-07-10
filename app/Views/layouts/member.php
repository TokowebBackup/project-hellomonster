<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Member - Hellomonster</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        tailwind.config = {
            safelist: [
                'bg-primary', 'bg-blue-700',
                'text-white', 'rounded-md',
                'font-semibold', 'font-secondary',
                'bg-green-100', 'text-green-700',
                'bg-red-100', 'text-red-700'
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
        <header class="bg-white shadow p-4">
            <div class="container mx-auto flex justify-between items-center">
                <div class="font-bold text-lg">Hellomonster Member</div>
                <a href="/logout" class="text-sm text-red-500 hover:underline">Logout</a>
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
</body>

</html>