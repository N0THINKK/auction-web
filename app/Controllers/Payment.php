<?php

namespace App\Controllers;

use App\Models\PaymentModel;
use App\Models\TransactionModel;

class Payment extends BaseController
{
    public function choose($itemId)
    {
        $paymentModel = new \App\Models\PaymentModel();
        $userModel = new \App\Models\UserModel(); // Pastikan model ini tersedia
        $userId = session()->get('user_id');

        // Ambil data user
        $user = $userModel->find($userId);

        // Cari payment untuk transaksi terkait
        $payment = $paymentModel->where('transaction_id', $itemId)->first();

        return view('payment/choose', [
            'itemId' => $itemId,
            'payment' => $payment,
            'creditCard' => $user['credit_card'], // Ambil nilai credit_card langsung
        ]);
    }


    public function process($itemId)
    {
        $paymentModel = new PaymentModel();
        $transactionModel = new TransactionModel();

        // Ambil metode pembayaran dari form
        $paymentMethod = $this->request->getPost('payment_method');
        $amount = 100000; // Contoh nominal
        $uniqueCode = rand(100, 999); // Kode unik untuk transfer bank

        // Mapping metode pembayaran sesuai enum
        switch ($paymentMethod) {
            case 'transfer':
                $method = 'bank_transfer';
                break;
            case 'cod':
                $method = 'cash';
                break;
            case 'debit':
                $method = 'credit_card';
                break;
            default:
                return redirect()->back()->with('error', 'Metode pembayaran tidak valid.');
        }

        // Ambil data transaksi terkait
        $transaction = $transactionModel->where('item_id', $itemId)->first();

        if (!$transaction) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }

        // Tentukan status dan jumlah pembayaran
        $status = 'pending';
        $amount = $transaction['final_price'];

        if ($paymentMethod === 'transfer') {
            // Tambahkan kode unik (3 digit terakhir) untuk transfer bank
            $amount += $uniqueCode;
        } elseif ($paymentMethod === 'cod' || $paymentMethod === 'debit') {
            // Jika COD atau Debit, pembayaran dianggap selesai
            $status = 'completed';
        }

        // Cek apakah pembayaran sudah ada untuk transaksi ini
        $existingPayment = $paymentModel->where('transaction_id', $transaction['id'])->first();

        if ($existingPayment) {
            // Perbarui data pembayaran
            $paymentModel->update($existingPayment['id'], [
                'amount' => $amount,
                'payment_date' => date('Y-m-d H:i:s'),
                'payment_method' => $method,
                'status' => $status,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            // Buat entri baru
            $paymentData = [
                'transaction_id' => $transaction['id'],
                'amount' => $amount,
                'payment_date' => date('Y-m-d H:i:s'),
                'payment_method' => $method,
                'status' => $status,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            
            if (!$paymentModel->save($paymentData)) {
                log_message('error', 'Gagal menyimpan pembayaran: ' . print_r($paymentModel->errors(), true));
                return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pembayaran.');
            }
        }
        return redirect()->to('/payment/status/' . $transaction['id'])->with('success', 'Pembayaran berhasil diproses.');        
    }


    public function status($transactionId)
    {
        $paymentModel = new PaymentModel();
        $payment = $paymentModel->where('transaction_id', $transactionId)->first();

        if (!$payment) {
            return redirect()->back()->with('error', 'Pembayaran tidak ditemukan.');
        }

        return view('payment/status', ['payment' => $payment]);
    }

    public function detail($itemId)
    {
        $itemModel = new \App\Models\ItemModel();
        $paymentModel = new \App\Models\PaymentModel();

        // Ambil data item
        $item = $itemModel->find($itemId);
        if (!$item) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Item tidak ditemukan.");
        }

        // Ambil status payment (jika ada)
        $payment = $paymentModel->where('transaction_id', $item['id'])->first();
        $paymentStatus = $payment ? $payment['status'] : 'none'; // 'none' jika tidak ada payment

        // Tentukan harga akhir (final price)
        $finalPrice = $item['final_price'] ?? $item['starting_price'];

        return view('payment/detail', [
            'item' => $item,
            'paymentStatus' => $paymentStatus,
            'finalPrice' => $finalPrice,
        ]);
    }

    public function confirmTransfer($paymentId)
    {
        $paymentModel = new \App\Models\PaymentModel();

        // Cari data pembayaran berdasarkan ID
        $payment = $paymentModel->find($paymentId);

        if (!$payment) {
            return redirect()->back()->with('error', 'Pembayaran tidak valid.');
        }

        // Periksa apakah metode pembayaran adalah transfer bank
        if ($payment['payment_method'] !== 'bank_transfer') {
            return redirect()->back()->with('error', 'Metode pembayaran tidak valid.');
        }

        // Pastikan status diubah hanya ke waiting_verification
        $paymentModel->update($paymentId, ['status' => 'waiting_verification']);

        return redirect()->to('/payment/status/' . $payment['transaction_id'])
            ->with('success', 'Konfirmasi berhasil. Admin akan segera memverifikasi pembayaran Anda.');
    }

}
