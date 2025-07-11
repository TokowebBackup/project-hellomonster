<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>404 Not Found | HellMonster</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Press Start 2P', cursive;
        }
    </style>
</head>

<body class="bg-zinc-900 text-white flex items-center justify-center min-h-screen p-6">
    <div class="text-center max-w-xl">
        <img src="<?= base_url('assets/img/Hello-Monster_Branding-Phase-1 - 1-_page-00071e3.png') ?>" alt="HellMonster Logo"
            class="mx-auto w-48 mb-8 animate-bounce" />

        <h1 class="text-6xl font-extrabold text-orange-500 mb-4">404</h1>
        <p class="text-lg text-zinc-300 mb-6">
            Oops! The monster chewed this page. <br />
            But don't worry, we’ve got more adventures waiting!
        </p>

        <a href="<?= base_url('/') ?>"
            class="inline-block bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-6 rounded transition duration-300">
            ← Back to Home
        </a>
    </div>
</body>

</html>