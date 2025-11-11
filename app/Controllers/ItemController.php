<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\TransactionModel;
use App\Models\BidModel;

class ItemController extends BaseController
{
    public function create()
    {
        return view('items/create');
    }

    public function store()
    {
        $itemModel = new ItemModel();

        // Validasi input dari form
        if ($this->validate([
            'name' => 'required',
            'description' => 'required',
            'starting_price' => 'required|numeric',
            'end_time' => 'required|valid_date'
        ])) {
            $itemModel->save([
                'name' => $this->request->getPost('name'),
                'description' => $this->request->getPost('description'),
                'starting_price' => $this->request->getPost('starting_price'),
                'end_time' => $this->request->getPost('end_time'),
                'status' => 'active'
            ]);
            return redirect()->to('/items');
        } else {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
    }

    public function index()
    {
        $this->processExpiredItems();
        $itemModel = new ItemModel();
        $items = $itemModel->findAll();
        return view('items/index', ['items' => $items]);
    }

    private function processExpiredItems()
    {
        $itemModel = new ItemModel();
        $bidModel = new BidModel();
        $transactionModel = new TransactionModel();
        log_message('debug', 'processExpiredItems() dipanggil.');

        // Ambil item yang sudah expired
        //$itemModel = new ItemModel();
        $expiredItems = $itemModel->where('end_time <', date('Y-m-d H:i:s'))
                                ->where('status', 'active')
                                ->findAll();

        if (empty($expiredItems)) {
            log_message('info', 'Tidak ada item yang expired.');
        } else {
            log_message('info', 'Item expired ditemukan: ' . json_encode($expiredItems));
        }

        foreach ($expiredItems as $item) {
            // Ambil bid tertinggi untuk item
            $highestBid = $bidModel->where('item_id', $item['id'])
                                   ->orderBy('bid_amount', 'DESC')
                                   ->first();

            // Jika ada bid tertinggi, masukkan ke tabel transaksi
            if ($highestBid) {
                $transactionData = [
                    'user_id'     => $highestBid['user_id'],
                    'item_id'     => $item['id'],
                    'final_price' => $highestBid['bid_amount'],
                    'status'      => 'pending',
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                ];
                $transactionModel->insert($transactionData);
            }

            // Update status item menjadi selesai
            $itemModel->update($item['id'], ['status' => 'closed']);
        }
    }
}
