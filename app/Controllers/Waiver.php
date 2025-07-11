<?php

namespace App\Controllers;

use App\Models\MemberModel;

class Waiver extends BaseController
{
    public function index()
    {
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

    public function save()
    {
        $id = $this->request->getPost('id');

        if (!$id) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.');
        }

        $data = [
            'name'      => $this->request->getPost('name'),
            'phone'     => $this->request->getPost('phone'),
            'birthdate' => $this->request->getPost('birthdate'),
            'country'   => $this->request->getPost('country'),
            'city'      => $this->request->getPost('city'),
            'address'   => $this->request->getPost('address'),
        ];
        // var_dump($id);
        // var_dump($data);
        // die;

        $model = new MemberModel();
        $affected = $model->update($id, $data);
        if (!$affected) {
            dd("Update gagal atau tidak ada perubahan");
        }

        return redirect()->to('/membership')->with('message', 'Data berhasil disimpan. Silakan lanjut aktivasi email.');
    }
}
