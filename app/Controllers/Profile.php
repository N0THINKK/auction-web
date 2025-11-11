<?php

namespace App\Controllers;

use App\Controllers\Items;
use App\Models\UserModel;
use App\Models\TransactionModel;
use App\Models\ItemModel;
use App\Models\PaymentModel;

class Profile extends BaseController
{
    public function index()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        return view('profile/index', [
            'user' => $user
        ]);
    }

    public function history()
    {
        //processExpiredItems();
        $userId = session()->get('user_id'); // Ambil user_id dari session
        if (!$userId) {
            return redirect()->to('/login');
        }

        $transactionModel = new TransactionModel();

        // Ambil history transaksi berdasarkan user_id
        $transactions = $transactionModel->where('user_id', $userId)
                                          ->findAll();

        return view('profile/history', ['transactions' => $transactions]);
    }

    public function itemDetail($itemId)
    {
        $userId = session()->get('user_id'); // Ambil user_id dari session
        if (!$userId) {
            return redirect()->to('/login');
        }

        $transactionModel = new TransactionModel();
        $itemModel = new ItemModel();
        $paymentModel = new PaymentModel();

        // Ambil transaksi berdasarkan item_id dan user_id
        $transaction = $transactionModel->where([
            'item_id' => $itemId,
            'user_id' => $userId
        ])->first();

        if (!$transaction) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaction not found');
        }

        // Ambil data item
        $item = $itemModel->find($itemId);
        if (!$item) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Item not found');
        }

        // Ambil status pembayaran
        $payment = $paymentModel->where('transaction_id', $transaction['id'])->first();
        $paymentStatus = $payment ? $payment['status'] : 'none'; // Jika tidak ada pembayaran, status "none"

        // Kirim data ke view
        return view('profile/item_detail', [
            'item' => $item,
            'finalPrice' => $transaction['final_price'], // Gunakan nama variabel konsisten dengan view
            'paymentStatus' => $paymentStatus,
        ]);
    }

    public function edit()
    {
        $userId = session()->get('user_id');
        $userModel = new UserModel();

        $user = $userModel->find($userId);

        if ($this->request->getMethod() === 'POST') {
            $data = [
                'name' => $this->request->getPost('name'),
                'email' => $this->request->getPost('email'),
                'credit_card' => $this->request->getPost('credit_card'),
            ];
        
            // Log data untuk debugging
            log_message('debug', 'Form data: ' . json_encode($data));
        
            $userModel->update($userId, $data);
        
            return redirect()->to('/profile')->with('success', 'Profile updated successfully.');
        }
        
        return view('profile/edit', ['user' => $user]);
    }
}
