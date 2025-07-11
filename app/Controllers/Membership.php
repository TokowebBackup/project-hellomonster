<?php

namespace App\Controllers;

use App\Libraries\MidtransSnap;
use App\Models\MemberModel;
use CodeIgniter\Email\Email;
use Midtrans\Snap;

class Membership extends BaseController
{
    public function index()
    {
        if ($this->request->getGet('success')) {
            session()->setFlashdata('message', 'Pendaftaran berhasil! Silakan cek email untuk aktivasi akun.');
        }

        return view('membership');
    }

    public function create()
    {
        return view('member/signup');
    }

    public function paymentCallback()
    {

        $json = $this->request->getJSON(true);
        $email = $json['customer_details']['email'] ?? null;
        // log_message('debug', 'Callback Data: ' . json_encode($json));

        // if (!$json || !in_array($json['transaction_status'], ['settlement', 'capture'])) {
        //     log_message('debug', 'STATUS transaksi bukan settlement/capture: ' . $json['transaction_status']);
        //     return $this->response->setJSON(['status' => 'ignored']);
        // }
        log_message('debug', 'Callback email: ' . $email);
        log_message('debug', 'Callback Data: ' . json_encode($json));

        if (!isset($json['customer_details']['email'])) {
            log_message('error', 'EMAIL TIDAK ADA di callback Midtrans');
        }

        if (!$email) {
            return $this->response->setJSON(['status' => 'no email']);
        }

        $memberModel = new MemberModel();
        $existing = $memberModel->where('email', $email)->first();

        if ($existing) {
            return $this->response->setJSON(['status' => 'already registered']);
        }

        $token = bin2hex(random_bytes(32));
        $memberModel->save([
            'email' => $email,
            'activation_token' => $token,
            'is_active' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Kirim email aktivasi
        $emailService = \Config\Services::email();
        $config = new \Config\Email();
        $emailService->setFrom($config->fromEmail, $config->fromName);
        $emailService->setTo($email);
        $emailService->setSubject('Aktivasi Akun Hellomonster');
        $activationLink = base_url('membership/activate/' . $token);
        $message = view('member/email_activation', ['activationLink' => $activationLink]);
        $emailService->setMessage($message);
        $emailService->send();

        return $this->response->setJSON(['status' => 'registered']);
    }


    public function register()
    {
        $email = $this->request->getPost('email');

        $memberModel = new MemberModel();
        $existing = $memberModel->where('email', $email)->first();

        if ($existing) {
            return redirect()->to('/membership/login')->with('error', 'Email sudah terdaftar. Silakan login.');
        }

        try {
            new MidtransSnap(); // setup Midtrans

            $orderId = 'HM-' . time();

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => 25000
                ],
                'customer_details' => [
                    'email' => $email
                ],
                'callbacks' => [
                    'finish' => base_url('membership/payment-callback')
                ],
                'metadata' => [
                    'email' => $email
                ]
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            return view('member/payment', [
                'snapToken' => $snapToken,
                'email' => $email,
                'orderId' => $orderId
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'MIDTRANS ERROR: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat transaksi Midtrans: ' . $e->getMessage());
        }
    }



    public function activate($token)
    {
        $memberModel = new MemberModel();
        $member = $memberModel->where('activation_token', $token)->first();

        if ($member) {
            $memberModel->update($member['id'], ['is_active' => 1, 'activation_token' => null]);
            session()->set('active_member_id', $member['id']);
            return redirect()->to('/membership/create-password');
        } else {
            return "Token tidak valid.";
        }
    }

    public function createPassword()
    {
        if (!session()->get('active_member_id')) {
            return redirect()->to('/membership');
        }

        return view('member/create_password');
    }

    public function savePassword()
    {
        $password = $this->request->getPost('password');
        $id = session()->get('active_member_id');

        if (!$id) {
            return redirect()->to('/membership');
        }

        $memberModel = new MemberModel();
        $memberModel->update($id, [
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ]);

        session()->remove('active_member_id');

        // return "Password berhasil dibuat! Silakan login.";
        // (Opsional) set session login langsung
        session()->set('member_id', $id);

        return redirect()->to('/membership/dashboard')->with('message', 'Password berhasil dibuat! Selamat datang!');
    }

    public function loginForm()
    {
        return view('member/login');
    }

    public function login()
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $memberModel = new MemberModel();
        $member = $memberModel->where('email', $email)->first();

        if (!$member) {
            return redirect()->back()->withInput()->with('error', 'Email tidak ditemukan.');
        }

        if (!$member['is_active']) {
            return redirect()->back()->withInput()->with('error', 'Akun belum diaktivasi.');
        }

        if (!password_verify($password, $member['password'])) {
            return redirect()->back()->withInput()->with('error', 'Password salah.');
        }

        // Simpan session login
        session()->set('member_id', $member['id']);

        return redirect()->to('/membership/dashboard')->with('message', 'Login berhasil!');
    }

    // public function check()
    // {
    //     $email = $this->request->getPost('email');

    //     if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    //         return redirect()->to('/membership')->with('error', 'Email tidak valid.');
    //     }

    //     $memberModel = new MemberModel();
    //     $member = $memberModel->where('email', $email)->first();

    //     if ($member) {
    //         if ($member['is_active']) {
    //             return redirect()->to('/membership/dashboard')->with('message', 'Email sudah terdaftar. Silakan login.');
    //         } else {
    //             $token = bin2hex(random_bytes(32));
    //             $memberModel->update($member['id'], ['activation_token' => $token]);

    //             $emailService = \Config\Services::email();
    //             $config = new \Config\Email();
    //             $emailService->setFrom($config->fromEmail, $config->fromName);
    //             $emailService->setTo($email);
    //             $emailService->setSubject('Aktivasi Akun Hellomonster');

    //             $activationLink = base_url('membership/activate/' . $token);
    //             $message = view('member/email_activation', ['activationLink' => $activationLink]);

    //             $emailService->setMessage($message);

    //             if ($emailService->send()) {
    //                 return redirect()->to('/membership')->with('message', 'Email aktivasi telah dikirim ulang!');
    //             } else {
    //                 return $emailService->printDebugger(['headers', 'subject', 'body']);
    //             }
    //         }
    //     } else {
    //         // Daftarkan baru & kirim email aktivasi
    //         $token = bin2hex(random_bytes(32));
    //         $memberModel->save([
    //             'email' => $email,
    //             'activation_token' => $token,
    //             'is_active' => 0
    //         ]);

    //         $emailService = \Config\Services::email();
    //         $config = new \Config\Email();
    //         $emailService->setFrom($config->fromEmail, $config->fromName);
    //         $emailService->setTo($email);
    //         $emailService->setSubject('Aktivasi Akun Hellomonster');

    //         $activationLink = base_url('membership/activate/' . $token);
    //         $message = view('member/email_activation', ['activationLink' => $activationLink]);

    //         $emailService->setMessage($message);

    //         if ($emailService->send()) {
    //             return redirect()->to('/membership')->with('message', 'Silakan cek email untuk aktivasi akun.');
    //         } else {
    //             // return $emailService->printDebugger(['headers', 'subject', 'body']);
    //             echo "<pre>";
    //             print_r($emailService->printDebugger(['headers', 'subject', 'body']));
    //             echo "</pre>";
    //             die;
    //         }
    //     }
    // }
    public function check()
    {
        if (session()->get('member_id')) {
            return redirect()->to('/membership/dashboard');
        }

        $email = trim($this->request->getPost('email'));
        log_message('debug', 'EMAIL YANG DITERIMA: [' . $email . ']');


        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            log_message('error', 'VALIDATION GAGAL - Email tidak valid: ' . $email);
            return redirect()->to('/membership')->with('error', 'Email tidak valid.');
        }

        $memberModel = new MemberModel();
        $member = $memberModel->where('email', $email)->first();

        if ($member) {
            // return redirect()->to('/waiver?id=' . $member['id']);
            return redirect()
                ->to('/membership/login')
                ->with('error', 'Email sudah terdaftar, silakan login.');
        } else {
            // Buat akun baru
            $token = bin2hex(random_bytes(32));
            $memberModel->save([
                'email' => $email,
                'activation_token' => $token,
                'is_active' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $memberId = $memberModel->getInsertID();

            try {
                $emailService = \Config\Services::email();
                $config = new \Config\Email();
                $emailService->setFrom($config->fromEmail, $config->fromName);
                $emailService->setTo($email);
                $emailService->setSubject('Aktivasi Akun Hellomonster');
                $activationLink = base_url('membership/activate/' . $token);
                $message = view('member/email_activation', ['activationLink' => $activationLink]);
                $emailService->setMessage($message);
                $emailService->send(); // tidak perlu if-else
            } catch (\Throwable $e) {
                log_message('error', 'Gagal mengirim email aktivasi ke ' . $email . ': ' . $e->getMessage());

                // Tambahkan flashdata jika ingin tampilkan di frontend (opsional)
                session()->setFlashdata('warning', 'Email aktivasi gagal dikirim. Silakan cek kembali pengaturan email Anda.');
            }

            session()->set('waiver_member_id', $memberId);
            session()->set('waiver_step_1', true);

            return redirect()->to('/waiver?id=' . $memberId);
        }
    }

    public function dashboard()
    {
        if (!session()->get('member_id')) {
            return redirect()->to('/membership')->with('error', 'Silakan login terlebih dahulu.');
        }

        $member = (new \App\Models\MemberModel())->find(session()->get('member_id'));

        return view('member/dashboard', ['member' => $member]);
    }

    public function profile()
    {
        if (!session()->get('member_id')) {
            return redirect()->to('/membership')->with('error', 'Silakan login terlebih dahulu.');
        }

        $member = (new MemberModel())->find(session()->get('member_id'));
        return view('member/profile', ['member' => $member]);
    }

    public function updateProfile()
    {
        $id = session()->get('member_id');

        if (!$id) {
            return redirect()->to('/membership')->with('error', 'Silakan login terlebih dahulu.');
        }

        $data = [
            'name'      => $this->request->getPost('name'),
            'phone'     => $this->request->getPost('phone'),
            'birthdate' => $this->request->getPost('birthdate'),
            'country'   => $this->request->getPost('country'),
            'city'      => $this->request->getPost('city'),
            'address'   => $this->request->getPost('address'),
        ];

        $data = array_filter($data, fn($val) => $val !== null && $val !== '');

        if (empty($data)) {
            return redirect()->to('/membership/profile')->with('error', 'Tidak ada data yang dikirim.');
        }

        $memberModel = new MemberModel();
        $memberModel->update($id, $data);

        return redirect()->to('/membership/dashboard')->with('message', 'Profil berhasil diperbarui!');
    }


    public function logout()
    {
        session()->destroy();
        return redirect()->to('/membership')->with('message', 'Berhasil logout.');
    }
}
