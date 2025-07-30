<?php

namespace App\Controllers;

use App\Models\{AdminModel, MemberModel, SignatureModel, ChildrenModel, SettingModel};

class Admin extends BaseController
{
    public function __construct()
    {
        helper('settings');
    }

    public function login()
    {
        $logo_html = get_setting('logo_website');
        preg_match('/src="([^"]+)"/', $logo_html, $matches);
        $logo_src = $matches[1] ?? '';
        $data = [
            'logo_src' => $logo_src,
        ];
        return view('admin/login', $data);
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

        $logo_html = get_setting('logo_website');
        preg_match('/src="([^"]+)"/', $logo_html, $matches);
        $logo_src = $matches[1] ?? '';

        return view('admin/dashboard', [
            'totalMembers' => $totalMembers,
            'monthlyCounts' => $monthlyCounts,
            'logo_src' => $logo_src,
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

        $perPage = 20;
        $pageParam = 'page_members';  // nama param page kustom

        // Ambil halaman dari query param page_members, default 1
        $page = (int) ($this->request->getGet($pageParam) ?? 1);

        // Panggil paginate dengan pageParam kustom
        $members = $memberModel->orderBy('id', 'DESC')->paginate($perPage, 'members', $page);
        $pager = $memberModel->pager;

        $countries = (new MemberModel())
            ->distinct()
            ->select('country')
            ->orderBy('country')
            ->findColumn('country');

        $countries = array_filter($countries, fn($c) => !empty(trim($c)));

        foreach ($members as &$m) {
            $m['country_code'] = $this->getCountryCodeFromName($m['country']);
        }
        unset($m);

        $query = $this->request->getGet();
        unset($query[$pageParam]); // hapus supaya gak dobel di URL pagination

        $logo_html = get_setting('logo_website');
        preg_match('/src="([^"]+)"/', $logo_html, $matches);
        $logo_src = $matches[1] ?? '';

        return view('admin/members', [
            'members' => $members,
            'pager' => $pager,
            'keyword' => $keyword,
            'selectedCountry' => $selectedCountry,
            'countries' => $countries,
            'query' => $query,
            'pageParam' => $pageParam,
            'logo_src' => $logo_src,
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

        $logo_html = get_setting('logo_website');
        preg_match('/src="([^"]+)"/', $logo_html, $matches);
        $logo_src = $matches[1] ?? '';

        return view('admin/member/edit', ['member' => $member, 'logo_src' => $logo_src]);
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

        $perPage = 20;
        $page = (int) ($this->request->getGet($pageParam) ?? 1);

        // Ambil data dengan paginate
        $children = $model->orderBy('id', 'DESC')->paginate($perPage, 'children', $page);
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

        $logo_html = get_setting('logo_website');
        preg_match('/src="([^"]+)"/', $logo_html, $matches);
        $logo_src = $matches[1] ?? '';

        return view('admin/childrens', [
            'children' => $children,
            'pager' => $pager,
            'keyword' => $keyword,
            'selectedMember' => $selectedMember,
            'memberOptions' => $memberOptions,
            'query' => $query,
            'pageParam' => $pageParam,
            'logo_src' => $logo_src,
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
        $logo_html = get_setting('logo_website');
        preg_match('/src="([^"]+)"/', $logo_html, $matches);
        $logo_src = $matches[1] ?? '';
        return view('admin/children/edit', compact('child', 'members', 'logo_src'));
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

        $perPage = 20;
        $pageParam = 'page_signs'; // nama param page kustom

        // Ambil halaman dari query param 'page_signs' atau default 1
        $page = (int) ($this->request->getGet($pageParam) ?? 1);

        // Buat query builder dengan join member supaya member_name langsung bisa didapat (optional, lebih efisien)
        // Tapi kalau mau tetap manual seperti ini juga bisa.

        // Pagination tanda tangan
        $signs = $signatureModel->orderBy('id', 'DESC')->paginate($perPage, 'signs', $page);
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

        $logo_html = get_setting('logo_website');
        preg_match('/src="([^"]+)"/', $logo_html, $matches);
        $logo_src = $matches[1] ?? '';

        return view('admin/sign', [
            'signs' => $signs,
            'pager' => $pager,
            'query' => $query,
            'pageParam' => $pageParam,
            'logo_src' => $logo_src,
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



    public function settings()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $model = new SettingModel();
        $logo_html = get_setting('logo_website');
        preg_match('/src="([^"]+)"/', $logo_html, $matches);
        $logo_src = $matches[1] ?? '';
        $data['logo_src'] = $logo_src;
        $data['settings'] = $model->orderBy('key_name', 'asc')->findAll();

        return view('admin/settings', $data);
    }

    public function addSetting()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $logo_html = get_setting('logo_website');
        preg_match('/src="([^"]+)"/', $logo_html, $matches);
        $logo_src = $matches[1] ?? '';
        return view('admin/settings/add', ['setting' => ['id' => '', 'key_name' => '', 'content' => ''], 'logo_src' => $logo_src]);
    }

    public function saveSetting()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $key = $this->request->getPost('key_name');
        $content = $this->request->getPost('content');

        // $imagePath = null;
        // $image = $this->request->getFile('image');
        // if ($image && $image->isValid() && !$image->hasMoved()) {
        //     $imageName = $image->getRandomName();
        //     $uploadPath = FCPATH . 'uploads/settings/';
        //     if (!is_dir($uploadPath)) {
        //         mkdir($uploadPath, 0755, true); // buat folder rekursif
        //     }
        //     $image->move('uploads/settings', $imageName);
        //     $imagePath = base_url('uploads/settings/' . $imageName);
        // }

        // // Gabungkan dengan konten
        // if ($imagePath) {
        //     $content .= '<br><img src="' . $imagePath . '" alt="Image" />';
        // }

        if (!$key || !$content) {
            return redirect()->back()->with('error', 'Key dan Content wajib diisi.');
        }

        $model = new SettingModel();

        // Cek duplikat key
        if ($model->where('key_name', $key)->first()) {
            return redirect()->back()->with('error', 'Key sudah digunakan.');
        }

        $model->insert([
            'key_name' => $key,
            'content' => $content
        ]);

        return redirect()->to('/admin/settings')->with('message', 'Setting berhasil ditambahkan.');
    }

    public function uploadImage()
    {
        if (!session()->get('admin_logged_in')) {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }

        $image = $this->request->getFile('image');

        if (!$image || !$image->isValid() || $image->hasMoved()) {
            return $this->response->setJSON(['error' => 'Gambar tidak valid.']);
        }

        $imageName = $image->getRandomName();
        $uploadPath = FCPATH . 'uploads/settings/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $image->move($uploadPath, $imageName);
        $imageUrl = base_url('uploads/settings/' . $imageName);

        return $this->response->setJSON(['location' => $imageUrl]);
    }


    public function editSetting($id)
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $model = new SettingModel();
        $setting = $model->find($id);

        if (!$setting) {
            return redirect()->to('/admin/settings')->with('error', 'Setting tidak ditemukan.');
        }

        $logo_html = get_setting('logo_website');
        preg_match('/src="([^"]+)"/', $logo_html, $matches);
        $logo_src = $matches[1] ?? '';

        return view('admin/settings/add', ['setting' => $setting, 'logo_src' => $logo_src]);
    }

    public function updateSetting($id)
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $model = new SettingModel();
        $setting = $model->find($id);

        if (!$setting) {
            return redirect()->to('/admin/settings')->with('error', 'Setting tidak ditemukan.');
        }

        $key = $this->request->getPost('key_name');
        $content = $this->request->getPost('content');

        $model->update($id, ['key_name' => $key, 'content' => $content]);

        return redirect()->to('/admin/settings')->with('message', 'Setting berhasil diperbarui.');
    }

    public function latestMember()
    {
        $memberModel = new \App\Models\MemberModel();
        $latest = $memberModel->orderBy('created_at', 'DESC')->first();

        return $this->response->setJSON([
            'id' => $latest['id'],
            'name' => $latest['name'],
            'email' => $latest['email'],
            'created_at' => $latest['created_at'],
        ]);
    }

    public function notifications()
    {
        $notifModel = new \App\Models\NotificationModel();
        // $notifs = $notifModel->orderBy('created_at', 'DESC')->findAll(10); // Ambil 10 terakhir
        $notifs = $notifModel->where('is_read', 0)->orderBy('created_at', 'DESC')->findAll(10);


        return $this->response->setJSON($notifs);
    }

    public function markNotificationRead()
    {
        $id = $this->request->getPost('id');
        $notifModel = new \App\Models\NotificationModel();

        $notifModel->update($id, ['is_read' => 1]);

        return $this->response->setJSON(['status' => 'ok']);
    }
}
