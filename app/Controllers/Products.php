<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\UserModel;

class Products extends BaseController
{
    // Halaman untuk menampilkan form create produk
    public function create()
    {
        $userId = session()->get('user_id'); // Ambil user_id dari session
        if (!$userId) {
            // Redirect ke halaman login jika belum login
            return redirect()->to('/login');
        }

        // Jika sudah login, tampilkan halaman create
        return view('items/create');
    }

    public function store()
    {
        $userId = session()->get('user_id'); // Ambil user_id dari session
        

        $itemModel = new ItemModel();
        //var_dump(session()->get());
        // Validasi input dari form
        if ($this->validate([
            'name' => 'required',
            'description' => 'required',
            'starting_price' => 'required|numeric',
            'end_time' => 'required|valid_date'
        ])) {
            // Simpan produk ke dalam database
            $itemModel->save([
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'starting_price' => $this->request->getPost('starting_price'),
                'end_time' => $this->request->getPost('end_time'),
                'created_by' => $userId, // Menggunakan user_id dari session
            ]);
            //var_dump('created_by');
            session()->setFlashdata('success', 'Product created successfully.');
            log_message('debug', 'User ID dari session: ' . $userId);
            return redirect()->to('/products');
        } else {
            // Jika validasi gagal, kirim kembali dengan pesan error
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
    }





    // Menampilkan daftar produk
    public function index()
    {
        $userId = session()->get('user_id'); // Ambil user_id dari session
        //var_dump(session()->get());
        if (!$userId) {
            // Redirect ke halaman login jika belum login
            return redirect()->to('/login');
        }
        //var_dump($userId);
        $itemModel = new ItemModel();
        $items = $itemModel->findAll();  // Ambil semua data produk
        return view('products/index', ['products' => $items]);
    }
}
