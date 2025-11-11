<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'item_id', 'status', 'payment_status', 'final_price', 'created_at', 'updated_at'];

    // Menambahkan properti timestamps agar CodeIgniter dapat mengatur `created_at` dan `updated_at`
    protected $useTimestamps = true; // Menggunakan timestamp
    protected $createdField  = 'created_at'; // Nama kolom untuk created_at
    protected $updatedField  = 'updated_at'; // Nama kolom untuk updated_at

    // Fungsi untuk memperbarui status transaksi dan harga final
    public function updateTransactionStatus($transactionId, $status, $finalPrice)
    {
        return $this->update($transactionId, [
            'status' => $status,
            'final_price' => $finalPrice
        ]);
    }
}
