<?php

namespace App\Controllers;

use App\Models\AdminModel;
use App\Models\MemberModel;

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

    public function dashboard()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        return view('admin/dashboard');
    }

    public function members()
    {
        if (!session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }

        $memberModel = new \App\Models\MemberModel();
        $members = $memberModel->findAll();

        return view('admin/members', ['members' => $members]);
    }
}
