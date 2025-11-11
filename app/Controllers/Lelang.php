<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\BidModel;
use App\Models\TransactionModel;
use App\Models\PaymentModel;


class Lelang extends BaseController
{
    public function index()
    {
        $this->processExpiredItems();

        $itemModel = new ItemModel();
        $bidModel = new BidModel();
        
        // Ambil semua barang lelang
        $items = $itemModel->findAll();  

        // Ambil bid tertinggi untuk setiap item
        $itemsWithBids = [];  // Initialize the array to store items with bids
        foreach ($items as $item) {
            // Mendapatkan bid tertinggi untuk setiap item
            $highestBid = $bidModel->getHighestBid($item['id']);
            $currentPrice = $highestBid ? $highestBid['bid_amount'] : $item['starting_price'];
            $itemsWithBids[] = [
                'item' => $item,
                'highestBid' => $highestBid,
                'currentPrice' => $currentPrice,
            ];
        }

        // Send data to the view
        return view('lelang/index', ['itemsWithBids' => $itemsWithBids]);
    }


    public function view($id)
    {
        $userId = session()->get('user_id'); // Ambil user_id dari session
            if (!$userId) {
                // Redirect ke halaman login jika belum login
                return redirect()->to('/login');
            }
        $itemModel = new ItemModel();
        $transactionModel = new TransactionModel();
        $bidModel = new BidModel();

        // Perbarui status item sebelum mengambil data
        //$itemModel->updateStatus();

        // Mengambil data item
        $item = $itemModel->find($id);
        if (!$item) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Item tidak ditemukan');
        }

        // Mengambil bid tertinggi
        $highestBid = $bidModel->getHighestBid($id);

        // Menentukan harga saat ini
        $currentPrice = $highestBid ? $highestBid['bid_amount'] : $item['starting_price'];

        return view('lelang/view', [
            'item' => $item,
            'highestBid' => $highestBid,
            'currentPrice' => $currentPrice
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


    public function placeBid($id)
    {
        $bidAmount = $this->request->getPost('bid_amount');
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/auth/login');
        }

        $bidModel = new BidModel();
        $bidModel->save([
            'user_id' => $userId,
            'item_id' => $id,
            'bid_amount' => $bidAmount
        ]);

        return redirect()->to('/lelang/view/' . $id);
    }

    public function closeAuction($id)
    {
        $itemModel = new ItemModel();
        $item = $itemModel->find($id);

        // Pastikan hanya admin yang bisa menutup lelang
        if (session()->get('user_id') != 1) {
            return redirect()->to('/');
        }

        $bidModel = new BidModel();
        $highestBid = $bidModel->where('item_id', $id)->orderBy('bid_amount', 'DESC')->first();

        // Transaksi: Tentukan pemenang
        $transactionModel = new TransactionModel();
        $transactionModel->save([
            'user_id' => $highestBid['user_id'],
            'item_id' => $id,
            'final_price' => $highestBid['bid_amount'],
            'status' => 'pending'
        ]);

        // Update status lelang
        $itemModel->update($id, ['status' => 'closed']);

        return redirect()->to('/lelang');
    }

    public function makePayment($transactionId)
    {
        $paymentModel = new PaymentModel();
        $paymentModel->save([
            'transaction_id' => $transactionId,
            'amount' => 100, // contoh nominal
            'payment_method' => 'credit_card',
            'status' => 'completed'
        ]);

        // Update transaksi status
        $transactionModel = new TransactionModel();
        $transactionModel->update($transactionId, ['status' => 'completed']);

        return redirect()->to('/lelang');
    }

    public function transactionHistory()
    {
        $transactionModel = new TransactionModel();
        $userId = session()->get('user_id');
        $transactions = $transactionModel->where('user_id', $userId)->findAll();
        
        return view('lelang/history', ['transactions' => $transactions]);
    }

    public function updateAuctionStatus($itemId)
    {
        // Atur timezone ke Jakarta
        date_default_timezone_set('Asia/Jakarta');

        $itemModel = new ItemModel();
        $transactionModel = new TransactionModel();
        $bidModel = new BidModel();
        
        $item = $itemModel->find($itemId);
        echo $item;
        exit();

        if (!$item) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Item tidak ditemukan.");
        }

        $currentTime = time();

        // Ambil bid tertinggi
        $highestBid = $bidModel->where('item_id', $itemId)
            ->orderBy('bid_amount', 'DESC')
            ->first();

        // Cek apakah waktu sudah habis
        if (strtotime($item['end_time']) <= $currentTime) {
            // Update status transaksi ke 'completed' jika ada bid tertinggi
            if ($highestBid) {
                $transactionData = [
                    'user_id' => $highestBid['user_id'],
                    'item_id' => $itemId,
                    'final_price' => $highestBid['bid_amount'],
                    'status' => 'pending',
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $transactionModel->save($transactionData);
            }

            // Disable bidding untuk item ini
            $itemModel->update($itemId, ['status' => 'inactive']);
        } else {
            // Update status transaksi ke 'on going' dan final_price ke bid tertinggi
            if ($highestBid) {
                $transactionData = [
                    'final_price' => $highestBid['bid_amount'],
                    'status' => 'on going',
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $transactionModel->where('item_id', $itemId)->set($transactionData)->update();
            } else {
                // Tidak ada data untuk diperbarui
                log_message('error', "Tidak ada bid tertinggi untuk item ID $itemId.");
            }
        }

        return redirect()->to("/lelang/view/$itemId");
    }

    private function processExpiredItems()
    {
        $itemModel = new ItemModel();
        $bidModel = new BidModel();
        $transactionModel = new TransactionModel();

        // Ambil semua item aktif yang sudah lewat waktu end_time
        $expiredItems = $itemModel->where('end_time <', date('Y-m-d H:i:s'))
                                ->where('status', 'active')
                                ->findAll();

        foreach ($expiredItems as $item) {
            // Ambil bid tertinggi untuk item tersebut
            $highestBid = $bidModel->where('item_id', $item['id'])
                                ->orderBy('bid_amount', 'DESC')
                                ->first();

            if ($highestBid) {
                // Jika ada bid tertinggi, buat transaksi baru
                $transactionData = [
                    'user_id' => $highestBid['user_id'],
                    'item_id' => $item['id'],
                    'final_price' => $highestBid['bid_amount'],
                    'status' => 'pending',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                $transactionModel->save($transactionData);
            }

            // Perbarui status item menjadi 'closed'
            $itemModel->update($item['id'], ['status' => 'closed']);
        }
    }



}
