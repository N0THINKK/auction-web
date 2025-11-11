<?php

namespace App\Controllers;

use App\Models\TransactionModel;

class TransactionController extends BaseController
{
    public function completeTransaction($transactionId)
    {
        // Inisialisasi model TransactionModel
        $transactionModel = new TransactionModel();

        // Dapatkan informasi transaksi untuk memastikan transaksi valid
        $transaction = $transactionModel->find($transactionId);
        
        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }

        // Perbarui status pembayaran menjadi 'paid' dan tentukan harga akhir
        $finalPrice = $this->getFinalPrice($transactionId); // Ambil harga final transaksi

        // Update transaksi di database dengan status 'paid' dan harga akhir
        $transactionModel->update($transactionId, [
            'payment_status' => 'paid',
            'final_price' => $finalPrice
        ]);

        // Redirect ke halaman riwayat transaksi dengan pesan sukses
        return redirect()->to('/transactions/history')->with('success', 'Transaksi berhasil diselesaikan');
    }

    // Fungsi untuk mendapatkan harga akhir transaksi (misalnya dari bid tertinggi)
    private function getFinalPrice($transactionId)
    {
        // Logika untuk mengambil harga akhir (misalnya harga tertinggi dari bid)
        // Anda bisa menyesuaikan dengan aplikasi Anda
        $bidModel = new \App\Models\BidModel();
        $highestBid = $bidModel->getHighestBid($transactionId);

        return $highestBid ? $highestBid['bid_amount'] : 0;
    }

    // Fungsi untuk menampilkan riwayat transaksi (contoh saja)
    public function history()
    {
        // Ambil data transaksi dari model dan kirim ke view
        $transactionModel = new TransactionModel();
        $transactions = $transactionModel->findAll();
        
        return view('transactions/history', [
            'transactions' => $transactions
        ]);
    }

    public function createBid($itemId)
    {
        $item = $this->lelangModel->find($itemId);
        $currentTime = time();

        if (strtotime($item['end_time']) <= $currentTime) {
            return redirect()->back()->with('error', 'Lelang telah berakhir.');
        }

        $bidAmount = $this->request->getPost('bid_amount');
        $userId = session()->get('user_id');

        // Simpan bid baru
        $bidData = [
            'user_id' => $userId,
            'item_id' => $itemId,
            'bid_amount' => $bidAmount,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->bidModel->insert($bidData);

        // Update transaksi dengan bid tertinggi
        $transactionData = [
            'user_id' => $userId,
            'final_price' => $bidAmount,
            'status' => 'on going',
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $this->transactionModel->where('item_id', $itemId)->set($transactionData)->update();

        return redirect()->to("/lelang/view/$itemId")->with('success', 'Bid berhasil ditambahkan.');
    }

}
