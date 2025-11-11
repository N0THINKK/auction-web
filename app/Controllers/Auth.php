<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class Auth extends Controller
{
    public function register()
    {
        // Tampilkan form register
        return view('auth/register');
    }

    public function doRegister()
    {
        // Ambil inputan nama, email, dan password dari form
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $passwordConfirm = $this->request->getPost('password_confirm');

        // Validasi input (misal: password harus sama, dan email belum terdaftar)
        if ($password !== $passwordConfirm) {
            session()->setFlashdata('error', 'Passwords do not match');
            return redirect()->to('/auth/register');
        }

        // Cek apakah email sudah terdaftar
        $userModel = new UserModel();
        if ($userModel->where('email', $email)->first()) {
            session()->setFlashdata('error', 'Email already registered');
            return redirect()->to('/auth/register');
        }

        // Hash password untuk penyimpanan yang aman
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Simpan data pengguna baru
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
        ];

        if ($userModel->insert($data)) {
            session()->setFlashdata('success', 'Registration successful. Please log in.');
            return redirect()->to('/auth/login');
        } else {
            session()->setFlashdata('error', 'Failed to register user');
            return redirect()->to('/auth/register');
        }
    }

    public function login()
    {
        // Tampilkan form login
        return view('auth/login');
    }

    public function doLogin()
    {
        // Ambil inputan email dan password
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Cek apakah email dan password cocok dengan data di database
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            // Jika cocok, buat session dan redirect ke halaman utama
            session()->set('user_id', $user['id']);
            session()->set('user_name', $user['name']);
            return redirect()->to('/');  // Ganti dengan halaman dashboard atau halaman utama setelah login
        } else {
            // Jika tidak cocok, kembali ke halaman login dengan pesan error
            session()->setFlashdata('error', 'Invalid email or password');
            return redirect()->to('/auth/login');
        }
    }

    public function logout()
    {
        session()->remove('user_id');
        return redirect()->to('/');
    }
}
