<?php

namespace App\Controllers;

use App\Models\MemberModel;

class Waiver extends BaseController
{
    public function index()
    {
        if (!session()->get('waiver_step_1')) {
            return redirect()->to('/membership')->with('error', 'Silakan mulai dari awal.');
        }
        $id = $this->request->getGet('id');

        if (!$id || !is_numeric($id)) {
            return redirect()->to('/membership')->with('error', 'ID tidak valid.');
        }

        $member = (new MemberModel())->find($id);

        if (!$member) {
            return redirect()->to('/membership')->with('error', 'Member tidak ditemukan.');
        }

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

    public function save()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.');
        }

        $data = [
            'name'         => $this->request->getPost('name'),
            'phone'        => $this->request->getPost('phone'),
            'birthdate'    => $this->request->getPost('birthdate'),
            'country'      => $this->request->getPost('country'),
            'city'         => $this->request->getPost('city'),
            'address'      => $this->request->getPost('address'),
            'agree_terms'  => $this->request->getPost('agree_terms') ? 1 : 0,
        ];

        $model = new MemberModel();
        $model->update($id, $data);

        // Ambil email member
        $member = $model->find($id);
        if (!$member) {
            return redirect()->back()->with('error', 'Data member tidak ditemukan.');
        }

        // 🔹 Setup Midtrans
        try {
            new \App\Libraries\MidtransSnap(); // Panggil konfigurasi Midtrans

            $orderId = 'HM-WAIVER-' . time();

            $params = [
                'transaction_details' => [
                    'order_id'     => $orderId,
                    'gross_amount' => 25000 // atau nominal yang kamu tentukan
                ],
                'customer_details' => [
                    'email' => $member['email'],
                    'first_name' => $member['name'] ?? 'User'
                ],
                'callbacks' => [
                    'finish' => base_url('membership/payment-callback') // bisa reuse callback yang sama
                ],
                'metadata' => [
                    'email' => $member['email'],
                    'waiver' => true
                ]
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            session()->remove('waiver_step_1');
            session()->remove('waiver_step_2');
            session()->remove('waiver_member_id');
            return view('member/waiver/payment_waiver', [
                'snapToken' => $snapToken,
                'email'     => $member['email'],
                'orderId'   => $orderId
            ]);
        } catch (\Throwable $e) {
            log_message('error', 'MIDTRANS WAIVER ERROR: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }
}
