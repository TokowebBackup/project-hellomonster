# Hellomonster Waiver System
<img width="1915" height="862" alt="Screenshot 2025-07-11 142134" src="https://github.com/user-attachments/assets/593553a6-312a-41f7-b818-e55636b68561" />

**Hellomonster** adalah platform pendaftaran anggota berbasis web dengan sistem waiver form (formulir persetujuan) yang modern. Proyek ini dibangun menggunakan **CodeIgniter 4** dan **Tailwind CSS**, dan dirancang untuk memproses pendaftaran member dengan verifikasi email, form dua langkah, serta dashboard pribadi bagi setiap member.

## 🌐 Live Demo
[!Hellomonster](https://hellomonster.tokoweb.live/)

## 🚀 Fitur Utama

- ✅ Pendaftaran berbasis email
- ✅ Pengiriman email aktivasi otomatis (SMTP)
- ✅ Formulir Waiver 2 langkah (multi-step)
- ✅ Input dengan floating label
- ✅ Validasi email & nomor HP
- ✅ Plugin intl-tel-input untuk nomor telepon
- ✅ Dropdown negara dan kota dinamis (fetch dari API)
- ✅ Dashboard member (edit profil, lihat status akun)
- ✅ Layout terpisah untuk area publik dan area member

## 🛠️ Teknologi yang Digunakan

- [CodeIgniter 4](https://codeigniter.com/)
- [Tailwind CSS](https://tailwindcss.com/)
- [intl-tel-input](https://github.com/jackocnr/intl-tel-input)
- JavaScript (fetch API, DOM manipulation)
- SMTP Email (menggunakan konfigurasi `Config\Email`)
- MySQL / MariaDB

## 📦 Struktur Direktori (Singkat)

```
app/
├── Controllers/
│ ├── Membership.php
│ └── Waiver.php
├── Models/
│ └── MemberModel.php
├── Views/
│ ├── member/
│ │ ├── dashboard.php
│ │ ├── form.php
│ │ ├── login.php
│ │ └── email_activation.php
│ ├── layouts/
│ │ ├── main.php
│ │ └── member.php
│ └── membership.php
public/
├── css/
├── js/
├── index.php
.env
```  


## ⚙️ Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/TokowebBackup/project-hellomonster.git
cd project-hellomonster
```  

2. Konfigurasi Environment
Salin file .env.example (jika tersedia) atau buat file .env sendiri:

```
cp env .env
```  

Isi konfigurasi dasar, misalnya:
```
app.baseURL = 'http://localhost:8080'
database.default.hostname = localhost
database.default.database = hellomonster
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi

email.fromEmail = "noreply@hellomonster.id"
email.fromName = "Hellomonster"
email.SMTPHost = smtp.yourserver.com
email.SMTPUser = your@email.com
email.SMTPPass = yourpassword
email.SMTPPort = 465
email.SMTPCrypto = ssl
```  

3. Setup Database
- Import file SQL (jika tersedia) atau buat tabel members secara manual.

- Contoh struktur tabel:
```
CREATE TABLE `members` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(255) NOT NULL,
  `name` VARCHAR(100),
  `phone` VARCHAR(50),
  `birthdate` DATE,
  `country` VARCHAR(100),
  `city` VARCHAR(100),
  `address` TEXT,
  `activation_token` VARCHAR(64),
  `is_active` TINYINT(1) DEFAULT 0,
  `created_at` DATETIME
);
```  
### Database seeder  
```
php spark db:seed AdminSeeder
```  

4. Jalankan Development Server  
```
php spark serve
```  

Akses di browser: http://localhost:8080

🧪 Testing Manual
- unjungi /membership

- Masukkan email → kirim → buka email (cek inbox/spam) → klik aktivasi

- Lanjutkan pengisian waiver (form 2 langkah)

- Login ke dashboard dan edit profil


#### Simulate Payment  
***Gunakan credit / debit card***
```
| Data        | Nilai                 |
| ----------- | --------------------- |
| Nomor Kartu | `4811 1111 1111 1114` |
| Expiry Date | `12/30` (bebas)       |
| CVV         | `123`                 |
| OTP         | `112233`              |
```

📌 Catatan
Semua data member hanya dapat diakses setelah aktivasi email.

Pastikan fungsi mail server sudah aktif di hosting Anda.

Gunakan shared hosting atau VPS yang mendukung PHP 8+ dan rewrite module.

📄 Lisensi
Project ini berada di bawah lisensi MIT — silakan gunakan dan modifikasi sesuai kebutuhan.

🤝 Kontribusi
Pull Request, feedback, dan issue report sangat dihargai!
Untuk kontribusi langsung, silakan fork repositori ini dan kirimkan PR Anda.

🧠 Dibuat Oleh
[Tokoweb Backup Team](https://tokoweb.co/)

Untuk Hellomonster.id
“Empowering Creative Community”
