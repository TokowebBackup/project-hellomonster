<?php

namespace App\Controllers;

use App\Models\MemberModel;
use CodeIgniter\Email\Email;

class Membership extends BaseController
{
    public function index()
    {
        return view('membership');
    }

    public function create()
    {
        return view('member/signup');
    }

    public function register()
    {
        $email = $this->request->getPost('email');
        $token = bin2hex(random_bytes(32));

        $memberModel = new MemberModel();
        $memberModel->save([
            'email' => $email,
            'activation_token' => $token,
            'is_active' => 0
        ]);

        // Kirim email
        $emailService = \Config\Services::email();
        $config = new \Config\Email();
        $emailService->setFrom($config->fromEmail, $config->fromName);
        $emailService->setTo($email);
        $emailService->setSubject('Aktivasi Akun Hellomonster');

        $activationLink = base_url('membership/activate/' . $token);
        $message = view('member/email_activation', ['activationLink' => $activationLink]);

        $emailService->setMessage($message);

        if ($emailService->send()) {
            return redirect()->to('/membership')->with('message', 'Email aktivasi telah dikirim! Silakan cek inbox atau folder spam.');
        } else {
            return $emailService->printDebugger(['headers', 'subject', 'body']);
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

    public function check()
    {
        $email = $this->request->getPost('email');

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->to('/membership')->with('error', 'Email tidak valid.');
        }

        $memberModel = new MemberModel();
        $member = $memberModel->where('email', $email)->first();

        if ($member) {
            if ($member['is_active']) {
                return redirect()->to('/membership/dashboard')->with('message', 'Email sudah terdaftar. Silakan login.');
            } else {
                $token = bin2hex(random_bytes(32));
                $memberModel->update($member['id'], ['activation_token' => $token]);

                $emailService = \Config\Services::email();
                $config = new \Config\Email();
                $emailService->setFrom($config->fromEmail, $config->fromName);
                $emailService->setTo($email);
                $emailService->setSubject('Aktivasi Akun Hellomonster');

                $activationLink = base_url('membership/activate/' . $token);
                $message = view('member/email_activation', ['activationLink' => $activationLink]);

                $emailService->setMessage($message);

                if ($emailService->send()) {
                    return redirect()->to('/membership')->with('message', 'Email aktivasi telah dikirim ulang!');
                } else {
                    return $emailService->printDebugger(['headers', 'subject', 'body']);
                }
            }
        } else {
            // Daftarkan baru & kirim email aktivasi
            $token = bin2hex(random_bytes(32));
            $memberModel->save([
                'email' => $email,
                'activation_token' => $token,
                'is_active' => 0
            ]);

            $emailService = \Config\Services::email();
            $config = new \Config\Email();
            $emailService->setFrom($config->fromEmail, $config->fromName);
            $emailService->setTo($email);
            $emailService->setSubject('Aktivasi Akun Hellomonster');

            $activationLink = base_url('membership/activate/' . $token);
            $message = view('member/email_activation', ['activationLink' => $activationLink]);

            $emailService->setMessage($message);

            if ($emailService->send()) {
                return redirect()->to('/membership')->with('message', 'Silakan cek email untuk aktivasi akun.');
            } else {
                // return $emailService->printDebugger(['headers', 'subject', 'body']);
                echo "<pre>";
                print_r($emailService->printDebugger(['headers', 'subject', 'body']));
                echo "</pre>";
                die;
            }
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

        // Ambil data dari form
        $name  = $this->request->getVar('name');
        $phone = $this->request->getVar('phone');

        // Hanya update jika ada data
        $data = array_filter([
            'name'  => $name,
            'phone' => $phone,
        ], fn($val) => $val !== null && $val !== '');

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
