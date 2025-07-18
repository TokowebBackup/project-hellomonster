<?php

namespace App\Controllers;

use App\Models\{AdminModel, MemberModel, SignatureModel, ChildrenModel};

class Admin extends BaseController
{
    public function login()
    {
        return view('admin/login');
    }

    public function doLogin()
    {
        $adminModel = new AdminModel();
        $admin = $adminModel->where('email', $this->request->getPost('email'))->first();

        if ($admin && password_verify($this->request->getPost('password'), $admin['password'])) {
            session()->set('admin_logged_in', true);
            session()->set('admin_name', $admin['name']);
            return redirect()->to('/admin/dashboard');
        }

        return redirect()->back()->with('error', 'Invalid email or password');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin/login');
    }
    private function getCountryCodeFromName($countryName)
    {
        $url = 'https://restcountries.com/v3.1/name/' . urlencode($countryName);
        $response = @file_get_contents($url);
        if ($response === false) {
            return null; // gagal fetch
        }

        $data = json_decode($response, true);
        if (isset($data[0]['cca2'])) {
            return strtolower($data[0]['cca2']); // misal 'ID'
        }

        return null;
    }
    public function dashboard()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $memberModel = new \App\Models\MemberModel();

        $totalMembers = $memberModel->countAllResults();

        $builder = $memberModel->builder();
        $builder->select('MONTH(created_at) as month, COUNT(*) as count');
        $builder->where('YEAR(created_at)', date('Y'));
        $builder->groupBy('MONTH(created_at)');
        $result = $builder->get()->getResult();

        $monthlyCounts = array_fill(1, 12, 0);
        foreach ($result as $row) {
            $monthlyCounts[(int)$row->month] = (int)$row->count;
        }

        return view('admin/dashboard', [
            'totalMembers' => $totalMembers,
            'monthlyCounts' => $monthlyCounts,
        ]);
    }

    public function members()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $keyword = $this->request->getGet('keyword');
        $selectedCountry = $this->request->getGet('country');

        $memberModel = new MemberModel();

        if ($keyword) {
            $memberModel = $memberModel->groupStart()
                ->like('name', $keyword)
                ->orLike('email', $keyword)
                ->orLike('country', $keyword)
                ->groupEnd();
        }

        if ($selectedCountry) {
            $memberModel = $memberModel->where('country', $selectedCountry);
        }

        $perPage = 5;
        $pageParam = 'page_members';  // nama param page kustom

        // Ambil halaman dari query param page_members, default 1
        $page = (int) ($this->request->getGet($pageParam) ?? 1);

        // Panggil paginate dengan pageParam kustom
        $members = $memberModel->paginate($perPage, 'members', $page);
        $pager = $memberModel->pager;

        $countries = (new MemberModel())
            ->distinct()
            ->select('country')
            ->orderBy('country')
            ->findColumn('country');

        foreach ($members as &$m) {
            $m['country_code'] = $this->getCountryCodeFromName($m['country']);
        }
        unset($m);

        $query = $this->request->getGet();
        unset($query[$pageParam]); // hapus supaya gak dobel di URL pagination

        return view('admin/members', [
            'members' => $members,
            'pager' => $pager,
            'keyword' => $keyword,
            'selectedCountry' => $selectedCountry,
            'countries' => $countries,
            'query' => $query,
            'pageParam' => $pageParam,
        ]);
    }


    public function searchMembers()
    {
        $keyword = strtolower($this->request->getGet('q'));
        $selectedCountry = $this->request->getGet('country');

        $model = new \App\Models\MemberModel();
        $members = $model->findAll();

        if (!empty($keyword)) {
            $members = array_filter($members, function ($m) use ($keyword) {
                return str_contains(strtolower($m['name']), $keyword) ||
                    str_contains(strtolower($m['email']), $keyword) ||
                    str_contains(strtolower($m['country']), $keyword);
            });
        }

        if (!empty($selectedCountry)) {
            $members = array_filter($members, function ($m) use ($selectedCountry) {
                return $m['country'] === $selectedCountry;
            });
        }

        return $this->response->setJSON(array_values($members));
    }

    public function memberEdit($id)
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $memberModel = new \App\Models\MemberModel();
        $member = $memberModel->find($id);

        if (!$member) {
            return redirect()->to('/admin/members')->with('error', 'Member tidak ditemukan.');
        }

        return view('admin/member/edit', ['member' => $member]);
    }

    public function memberUpdate($id)
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $memberModel = new \App\Models\MemberModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'country' => $this->request->getPost('country'),
            'country_code' => $this->request->getPost('country_code'),
        ];

        $memberModel->update($id, $data);

        return redirect()->to('/admin/members')->with('message', 'Member berhasil diperbarui.');
    }

    public function memberDelete($id)
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $memberModel = new \App\Models\MemberModel();

        $memberModel->delete($id);

        return redirect()->to('/admin/members')->with('message', 'Member berhasil dihapus.');
    }

    private function calculateAge($birthdate)
    {
        $dob = new \DateTime($birthdate);
        $today = new \DateTime();
        return $dob->diff($today)->y; // hanya ambil tahun (umur)
    }

    public function children()
    {
        $keyword = $this->request->getGet('keyword');
        $selectedMember = $this->request->getGet('member');
        $pageParam = 'page_children'; // nama parameter page custom, bisa juga 'page'

        $model = new ChildrenModel();
        $memberModel = new MemberModel();

        // Mulai query builder
        if ($keyword) {
            $model = $model->groupStart()
                ->like('name', $keyword)
                ->groupEnd();
        }

        if ($selectedMember) {
            $model = $model->where('member_uuid', $selectedMember);
        }

        $perPage = 5;
        $page = (int) ($this->request->getGet($pageParam) ?? 1);

        // Ambil data dengan paginate
        $children = $model->paginate($perPage, 'children', $page);
        $pager = $model->pager;

        // Ambil semua member untuk dropdown
        $memberOptions = $memberModel->select('uuid, name')->orderBy('name')->findAll();

        // Tambahkan data member_name dan umur per child
        foreach ($children as &$child) {
            $member = $memberModel->where('uuid', $child['member_uuid'])->first();
            $child['member_name'] = $member ? $member['name'] : 'Unknown';
            $child['age'] = $this->calculateAge($child['birthdate']);
        }
        unset($child);

        // Kirim semua query kecuali page param ke view untuk dipertahankan di URL pagination
        $query = $this->request->getGet();
        unset($query[$pageParam]);

        return view('admin/childrens', [
            'children' => $children,
            'pager' => $pager,
            'keyword' => $keyword,
            'selectedMember' => $selectedMember,
            'memberOptions' => $memberOptions,
            'query' => $query,
            'pageParam' => $pageParam,
        ]);
    }


    public function searchChildren()
    {
        $keyword = strtolower($this->request->getGet('q'));
        $model = new ChildrenModel();
        $memberModel = new MemberModel();

        $children = $model->findAll();

        foreach ($children as &$child) {
            $member = $memberModel->where('uuid', $child['member_uuid'])->first();
            $child['member_name'] = $member ? $member['name'] : 'Unknown';
            $child['member_uuid'] = $member ? $member['uuid'] : null;
            $child['age'] = $this->calculateAge($child['birthdate']);
        }
        unset($child);

        // Filter keyword
        if (!empty($keyword)) {
            $children = array_filter($children, function ($c) use ($keyword) {
                return str_contains(strtolower($c['name']), $keyword) ||
                    str_contains(strtolower($c['member_name']), $keyword);
            });
        }

        // Kembalikan sebagai JSON
        return $this->response->setJSON(array_values($children)); // reset keys
    }



    public function createChild()
    {
        return view('admin/children/create');
    }

    public function storeChild()
    {
        $model = new ChildrenModel();
        $model->insert([
            'member_id' => $this->request->getPost('member_id'),
            'name' => $this->request->getPost('name'),
            'age' => $this->request->getPost('age')
        ]);

        return redirect()->to('/admin/waiver/children')->with('message', 'Child added successfully.');
    }

    public function editChild($id)
    {
        $childModel = new ChildrenModel();
        $memberModel = new MemberModel();

        $child = $childModel->find($id);
        if (!$child) {
            return redirect()->to('/admin/children')->with('error', 'Child not found.');
        }

        // Ambil list member untuk dropdown (id = uuid, value = name)
        $members = $memberModel->findAll();

        return view('admin/children/edit', compact('child', 'members'));
    }

    public function updateChild($id)
    {
        $childModel = new ChildrenModel();

        $childModel->update($id, [
            'member_uuid' => $this->request->getPost('member_uuid'),
            'name' => $this->request->getPost('name'),
            'birthdate' => $this->request->getPost('birthdate'),
            'gender' => $this->request->getPost('gender'),
        ]);

        return redirect()->to('/admin/children')->with('message', 'Child updated successfully.');
    }

    public function deleteChild($id)
    {
        $model = new ChildrenModel();
        $model->delete($id);
        return redirect()->to('/admin/children')->with('message', 'Child deleted.');
    }

    /**
     * SIGNATURE CRUD
     */
    public function signList()
    {
        $signatureModel = new SignatureModel();
        $memberModel = new MemberModel();

        $perPage = 5;
        $pageParam = 'page_signs'; // nama param page kustom

        // Ambil halaman dari query param 'page_signs' atau default 1
        $page = (int) ($this->request->getGet($pageParam) ?? 1);

        // Buat query builder dengan join member supaya member_name langsung bisa didapat (optional, lebih efisien)
        // Tapi kalau mau tetap manual seperti ini juga bisa.

        // Pagination tanda tangan
        $signs = $signatureModel->paginate($perPage, 'signs', $page);
        $pager = $signatureModel->pager;

        // Tambahkan member_name ke setiap tanda tangan
        foreach ($signs as &$sign) {
            $member = $memberModel->where('uuid', $sign['member_uuid'])->first();
            $sign['member_name'] = $member ? $member['name'] : 'Unknown';
        }
        unset($sign);

        // Kirim semua query param kecuali page supaya filter & pagination tetap
        $query = $this->request->getGet();
        unset($query[$pageParam]);

        return view('admin/sign', [
            'signs' => $signs,
            'pager' => $pager,
            'query' => $query,
            'pageParam' => $pageParam,
        ]);
    }

    public function searchSignatures()
    {
        $keyword = $this->request->getGet('q');

        $signatureModel = new SignatureModel();
        $memberModel = new MemberModel();

        $signs = $signatureModel->findAll();

        foreach ($signs as &$sign) {
            $member = $memberModel->where('uuid', $sign['member_uuid'])->first();
            $sign['member_name'] = $member ? $member['name'] : 'Unknown';
        }
        unset($sign);

        // Filter berdasarkan nama member
        if (!empty($keyword)) {
            $keyword = strtolower($keyword);
            $signs = array_filter($signs, function ($s) use ($keyword) {
                return str_contains(strtolower($s['member_name']), $keyword);
            });
        }

        return $this->response->setJSON(array_values($signs));
    }


    public function viewSign($id)
    {
        $signatureModel = new SignatureModel();
        $memberModel = new MemberModel();

        $sign = $signatureModel->find($id);
        if (!$sign) {
            return $this->response->setJSON(['error' => 'Signature not found'])->setStatusCode(404);
        }

        $member = $memberModel->where('uuid', $sign['member_uuid'])->first();
        $sign['member_name'] = $member ? $member['name'] : 'Unknown';

        return $this->response->setJSON($sign);
    }


    public function deleteSign($id)
    {
        $model = new SignatureModel();
        $model->delete($id);
        return redirect()->to('/admin/sign')->with('message', 'Signature deleted.');
    }
}
