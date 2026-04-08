<?php

namespace App\Controllers;

use App\Models\UserModel;

class User extends BaseController
{
    public function index()
    {
        return redirect()->to('/user/login');
    }

    public function login()
    {
        helper(['form']);

        if (session()->get('logged_in')) {
            return redirect()->to('/admin/artikel');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'email'    => 'required|valid_email',
                'password' => 'required|min_length[6]',
            ];

            $data = [
                'email'    => trim((string) $this->request->getPost('email')),
                'password' => (string) $this->request->getPost('password'),
            ];

            if (! $this->validateData($data, $rules)) {
                return view('user/login', [
                    'title'      => 'Login',
                    'validation' => $this->validator,
                ]);
            }

            $model = new UserModel();
            $login = $model->where('useremail', $data['email'])->first();

            if (! $login || ! password_verify($data['password'], $login['userpassword'])) {
                session()->setFlashdata('error', 'Email atau password yang Anda masukkan salah. Silakan coba lagi.');
                return redirect()->to('/user/login')->withInput();
            }

            session()->set([
                'user_id'    => $login['id'],
                'user_name'  => $login['username'],
                'user_email' => $login['useremail'],
                'logged_in'  => true,
            ]);

            session()->setFlashdata('success', 'Login berhasil. Selamat datang kembali, ' . $login['username'] . '.');
            return redirect()->to('/admin/artikel');
        }

        return view('user/login', ['title' => 'Login']);
    }

    public function register()
    {
        helper(['form']);

        if (session()->get('logged_in')) {
            return redirect()->to('/admin/artikel');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'username'         => 'required|min_length[3]|max_length[50]',
                'email'            => 'required|valid_email|is_unique[user.useremail]',
                'password'         => 'required|min_length[6]',
                'confirm_password' => 'required|matches[password]',
            ];

            $data = [
                'username'         => trim((string) $this->request->getPost('username')),
                'email'            => trim((string) $this->request->getPost('email')),
                'password'         => (string) $this->request->getPost('password'),
                'confirm_password' => (string) $this->request->getPost('confirm_password'),
            ];

            if (! $this->validateData($data, $rules)) {
                return view('user/register', [
                    'title'      => 'Buat Akun',
                    'validation' => $this->validator,
                ]);
            }

            $model = new UserModel();
            $model->insert([
                'username'     => $data['username'],
                'useremail'    => $data['email'],
                'userpassword' => password_hash($data['password'], PASSWORD_DEFAULT),
            ]);

            session()->setFlashdata('success', 'Akun berhasil dibuat. Silakan login dengan akun baru Anda.');
            return redirect()->to('/user/login');
        }

        return view('user/register', ['title' => 'Buat Akun']);
    }

    public function forgotPassword()
    {
        helper(['form']);

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'email'            => 'required|valid_email',
                'new_password'     => 'required|min_length[6]',
                'confirm_password' => 'required|matches[new_password]',
            ];

            $data = [
                'email'            => trim((string) $this->request->getPost('email')),
                'new_password'     => (string) $this->request->getPost('new_password'),
                'confirm_password' => (string) $this->request->getPost('confirm_password'),
            ];

            if (! $this->validateData($data, $rules)) {
                return view('user/forgot_password', [
                    'title'      => 'Lupa Password',
                    'validation' => $this->validator,
                ]);
            }

            $model = new UserModel();
            $user = $model->where('useremail', $data['email'])->first();

            if (! $user) {
                session()->setFlashdata('error', 'Email belum terdaftar. Silakan periksa lagi atau buat akun baru.');
                return redirect()->to('/user/forgot-password')->withInput();
            }

            $model->update($user['id'], [
                'userpassword' => password_hash($data['new_password'], PASSWORD_DEFAULT),
            ]);

            session()->setFlashdata('success', 'Password berhasil diperbarui. Silakan login dengan password baru Anda.');
            return redirect()->to('/user/login');
        }

        return view('user/forgot_password', ['title' => 'Lupa Password']);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/user/login');
    }
}
