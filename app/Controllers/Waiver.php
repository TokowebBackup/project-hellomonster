<?php

namespace App\Controllers;

use App\Models\{MemberModel, ChildrenModel, SignatureModel, SettingModel};
use Ramsey\Uuid\Uuid;

class Waiver extends BaseController
{
    public function index()
    {
        $uuid = $this->request->getGet('id');
        $isEdit = !empty($uuid);

        if (!$isEdit && !session()->get('waiver_step_1')) {
            return redirect()->to('/waiver?id=' . $uuid)->with('error', 'Silakan mulai dari awal.');
        }


        // if (!$id || !is_numeric($id)) {
        //     return redirect()->to('/membership')->with('error', 'ID tidak valid.');
        // }

        $member = (new MemberModel())->where('uuid', $uuid)->first();
        // if (!$member) {
        //     return redirect()->to('/membership')->with('error', 'Member tidak ditemukan.');
        // }

        return view('member/waiver/form', ['member' => $member]);
    }

    // public function save()
    // {
    //     $id = $this->request->getPost('id');

    //     if (!$id) {
    //         return redirect()->back()->with('error', 'ID tidak ditemukan.');
    //     }

    //     $data = [
    //         'name'      => $this->request->getPost('name'),
    //         'phone'     => $this->request->getPost('phone'),
    //         'birthdate' => $this->request->getPost('birthdate'),
    //         'country'   => $this->request->getPost('country'),
    //         'city'      => $this->request->getPost('city'),
    //         'address'   => $this->request->getPost('address'),
    //         'agree_terms' => $this->request->getPost('agree_terms') ? 1 : 0
    //     ];
    //     // var_dump($id);
    //     // var_dump($data);
    //     // die;

    //     $model = new MemberModel();
    //     $affected = $model->update($id, $data);

    //     if (!$affected) {
    //         dd("Update gagal atau tidak ada perubahan");
    //     }
    //     session()->remove('waiver_step_1');
    //     session()->remove('waiver_step_2');
    //     session()->remove('waiver_member_id');
    //     return redirect()->to('/membership')->with('message', 'Data berhasil disimpan. Silakan lanjut aktivasi email.');
    // }

    // public function save()
    // {
    //     $id = $this->request->getPost('id');

    //     if (!$id) {
    //         return redirect()->back()->with('error', 'ID tidak ditemukan.');
    //     }

    //     $data = [
    //         'name'         => $this->request->getPost('name'),
    //         'phone'        => $this->request->getPost('phone'),
    //         'birthdate'    => $this->request->getPost('birthdate'),
    //         'country'      => $this->request->getPost('country'),
    //         'city'         => $this->request->getPost('city'),
    //         'address'      => $this->request->getPost('address'),
    //         'agree_terms'  => $this->request->getPost('agree_terms') ? 1 : 0,
    //     ];

    //     $model = new MemberModel();
    //     $model->update($id, $data);

    //     // Ambil email member
    //     $member = $model->find($id);
    //     if (!$member) {
    //         return redirect()->back()->with('error', 'Data member tidak ditemukan.');
    //     }

    //     // 🔹 Setup Midtrans
    //     try {
    //         new \App\Libraries\MidtransSnap(); // Panggil konfigurasi Midtrans

    //         $orderId = 'HM-WAIVER-' . time();

    //         $params = [
    //             'transaction_details' => [
    //                 'order_id'     => $orderId,
    //                 'gross_amount' => 25000 // atau nominal yang kamu tentukan
    //             ],
    //             'customer_details' => [
    //                 'email' => $member['email'],
    //                 'first_name' => $member['name'] ?? 'User'
    //             ],
    //             'callbacks' => [
    //                 'finish' => base_url('membership/payment-callback') // bisa reuse callback yang sama
    //             ],
    //             'metadata' => [
    //                 'email' => $member['email'],
    //                 'waiver' => true
    //             ]
    //         ];

    //         $snapToken = \Midtrans\Snap::getSnapToken($params);
    //         session()->remove('waiver_step_1');
    //         session()->remove('waiver_step_2');
    //         session()->remove('waiver_member_id');
    //         return view('member/waiver/payment_waiver', [
    //             'snapToken' => $snapToken,
    //             'email'     => $member['email'],
    //             'orderId'   => $orderId
    //         ]);
    //     } catch (\Throwable $e) {
    //         log_message('error', 'MIDTRANS WAIVER ERROR: ' . $e->getMessage());
    //         return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
    //     }
    // }


    public function save()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.');
        }

        $model = new MemberModel();
        $member = $model->find($id);
        if (!$member) {
            return redirect()->back()->with('error', 'Member tidak ditemukan.');
        }

        // JANGAN BIKIN UUID BARU!!
        $uuid = $member['uuid'];

        $data = [
            'uuid'         => $uuid,
            'name'         => $this->request->getPost('name'),
            'phone'        => $this->request->getPost('phone'),
            'birthdate'    => $this->request->getPost('birthdate'),
            'country'      => $this->request->getPost('country'),
            'city'         => $this->request->getPost('city'),
            'address'      => $this->request->getPost('address'),
            'agree_terms'  => $this->request->getPost('agree_terms') ? 1 : 0,
            'created_at'   => date('Y-m-d H:i:s')
        ];

        $model = new MemberModel();
        $updated = $model->update($id, $data);

        if (!$updated) {
            return redirect()->back()->with('error', 'Gagal menyimpan data.');
        }

        // Hapus session langkah
        session()->remove('waiver_step_1');
        session()->remove('waiver_step_2');
        session()->remove('waiver_member_id');

        // Redirect ke tahap children (seperti dreamus)
        return redirect()->to('/waiver/children?id=' . $uuid);
    }

    // app/Controllers/Waiver.php

    public function children()
    {
        $uuid = $this->request->getGet('id');

        if (!$uuid) {
            return redirect()->to('/')->with('error', 'UUID tidak ditemukan.');
        }

        $memberModel = new MemberModel();
        $member = $memberModel->where('uuid', $uuid)->first();

        if (!$member) {
            return redirect()->to('/')->with('error', 'Member tidak ditemukan.');
        }

        $childrenModel = new ChildrenModel();
        $children = $childrenModel->where('member_uuid', $uuid)->findAll();
        $signatureModel = new SignatureModel();
        $signature = $signatureModel->where('member_uuid', $uuid)->orderBy('signed_at', 'desc')->first();

        return view('member/waiver/children', [
            'member'    => $member,
            'children'  => $children,
            'uuid'      => $uuid,
            'signature' => $signature,
            'version'   => env('app.version'),
        ]);
    }

    public function addChild()
    {
        $uuid = $this->request->getPost('member_uuid');

        if (!$uuid) {
            return redirect()->back()->with('error', 'Member UUID tidak ditemukan.');
        }

        // Validasi input
        $rules = [
            'name'        => 'required',
            'birth_day'   => 'required|numeric',
            'birth_month' => 'required|numeric',
            'birth_year'  => 'required|numeric',
            'gender'      => 'required|in_list[male,female]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid.');
        }

        $birthdate = sprintf(
            '%04d-%02d-%02d',
            $this->request->getPost('birth_year'),
            $this->request->getPost('birth_month'),
            $this->request->getPost('birth_day')
        );

        $data = [
            'member_uuid' => $uuid,
            'name'        => $this->request->getPost('name'),
            'birthdate'   => $birthdate,
            'gender'      => $this->request->getPost('gender'),
        ];

        $model = new \App\Models\ChildrenModel();
        $model->insert($data);

        return redirect()->to('/waiver/children?id=' . $uuid)->with('message', 'The child has been successfully added.');
    }

    public function getChild($id)
    {
        $model = new \App\Models\ChildrenModel();
        $child = $model->find($id);

        if (!$child) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Child not found']);
        }

        return $this->response->setJSON($child);
    }

    public function updateChild($id)
    {
        $model = new \App\Models\ChildrenModel();
        $child = $model->find($id);

        if (!$child) {
            return redirect()->back()->with('error', 'The child\'s data could not be found.');
        }

        $rules = [
            'name'        => 'required',
            'birth_day'   => 'required|numeric',
            'birth_month' => 'required|numeric',
            'birth_year'  => 'required|numeric',
            'gender'      => 'required|in_list[male,female]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid.');
        }

        $birthdate = sprintf(
            '%04d-%02d-%02d',
            $this->request->getPost('birth_year'),
            $this->request->getPost('birth_month'),
            $this->request->getPost('birth_day')
        );

        $data = [
            'name'        => $this->request->getPost('name'),
            'birthdate'   => $birthdate,
            'gender'      => $this->request->getPost('gender'),
        ];

        $model->update($id, $data);

        return redirect()->back()->with('message', 'Data anak berhasil diperbarui.');
    }

    public function deleteChild($id)
    {
        $model = new \App\Models\ChildrenModel();
        $child = $model->find($id);

        if (!$child) {
            return redirect()->back()->with('error', 'The child\s data could not be found.');
        }

        $model->delete($id);

        return redirect()->back()->with('message', 'The child\'s data has been successfully removed.');
    }

    public function sign()
    {
        $uuid = $this->request->getGet('id');

        if (!$uuid) {
            return redirect()->to('/')->with('error', 'UUID tidak ditemukan.');
        }

        $member = (new \App\Models\MemberModel())->where('uuid', $uuid)->first();
        if (!$member) {
            return redirect()->to('/')->with('error', 'Member tidak ditemukan.');
        }

        $settingModel = new SettingModel();
        $content = $settingModel->get('waiver_content');
        // Cek apakah sudah pernah tanda tangan
        $signature = (new SignatureModel())
            ->where('member_uuid', $uuid)
            ->first();

        if ($signature) {
            return redirect()->to('/waiver/success?id=' . $uuid);
        }

        return view('member/waiver/sign', ['uuid' => $uuid, 'content' => $content, 'version' => env('app.version')]);
    }


    public function saveSignature()
    {
        $uuid = $this->request->getPost('uuid');
        $signatureData = $this->request->getPost('signature'); // base64 data

        if (!$uuid || !$signatureData) {
            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Both UUID and signature must be provided.'
            ]);
        }

        // Validasi UUID apakah ada di tabel members
        $memberModel = new MemberModel();
        $member = (new MemberModel())->where('uuid', $uuid)->first();

        if (!$member) {
            return $this->response->setStatusCode(404)->setJSON([
                'status' => 'error',
                'message' => 'Member tidak ditemukan.'
            ]);
        }

        // Simpan ke database
        $signatureModel = new SignatureModel();
        $signatureModel->insert([
            'member_uuid' => $uuid,
            'signature' => $signatureData
        ]);
        $memberModel->update($member['id'], ['is_active' => 1]);

        try {
            $email = \Config\Services::email();
            $config = new \Config\Email();

            $email->setFrom($config->fromEmail, $config->fromName ?? 'Hellomonster');
            $email->setTo($member['email']);
            $email->setSubject(lang('Membership.waiver_completed'));

            $message = view('emails/waiver_success', ['member' => $member]);
            $email->setMessage($message);
            $email->send();
        } catch (\Throwable $e) {
            log_message('error', 'Gagal mengirim email success waiver: ' . $e->getMessage());
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Tanda tangan berhasil disimpan.'
        ]);
    }

    public function success()
    {
        $uuid = $this->request->getGet('id');

        if (!$uuid) {
            return redirect()->to('/')->with('error', 'UUID tidak ditemukan.');
        }

        return view('member/waiver/success', [
            'title' => 'Success',
            'version' => env('app.version'),
        ]);
    }

    public function decline()
    {
        $uuid = $this->request->getGet('id');

        if (!$uuid) {
            return redirect()->to('/')->with('error', 'UUID tidak ditemukan.');
        }

        $memberModel = new MemberModel();
        $member = $memberModel->where('uuid', $uuid)->first();
        $childrenModel = new ChildrenModel();
        $signatureModel = new SignatureModel();


        if ($member) {
            $memberModel->where('uuid', $uuid)->delete();
            $childrenModel->where('member_uuid', $uuid)->delete();
            $signatureModel->where('member_uuid', $uuid)->delete();
        }

        session()->remove('waiver_step_1');
        session()->remove('waiver_step_2');
        session()->remove('waiver_member_id');

        return redirect()->to('/')->with('message', 'Pendaftaran dibatalkan.');
    }
}
