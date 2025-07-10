<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Aktivasi Akun - Hellomonster</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9fafb;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            background-color: #016BAF;
            color: #ffffff;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .link-text {
            word-break: break-all;
            color: #016BAF;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }

        .logo {
            max-width: 120px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <img src="<?= base_url('assets/img/Hello-Monster_Branding-Phase-1 - 1-_page-00071e4.png') ?>" alt="Hellomonster" class="logo">

        <h2>Halo 👋</h2>

        <p>Terima kasih telah mendaftar sebagai member Hellomonster.</p>
        <p>Untuk mengaktifkan akun kamu, silakan klik tombol di bawah ini:</p>

        <a href="<?= $activationLink ?>" class="btn" style="color: #ffffff;">Aktivasi Sekarang</a>

        <p>Jika tombol tidak berfungsi, kamu juga bisa menyalin link berikut dan buka di browser kamu:</p>

        <p class="link-text"><?= $activationLink ?></p>

        <div class="footer">
            Email ini dikirim otomatis oleh sistem Hellomonster.<br>
            Abaikan jika kamu tidak merasa melakukan pendaftaran.
        </div>
    </div>

</body>

</html>