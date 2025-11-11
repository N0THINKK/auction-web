<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\BidModel;
use App\Models\TransactionModel;

class Cron extends BaseController
{
    public function finalizeBids()
    {
        $itemModel = new ItemModel();
        $bidModel = new BidModel();
        $transactionModel = new TransactionModel();

        // Ambil item yang sudah selesai lelang
        $expiredItems = $itemModel->where('end_time <', date('Y-m-d H:i:s'))
                                  ->where('status', 'active')
                                  ->findAll();

        foreach ($expiredItems as $item) {
            // Ambil bid tertinggi untuk item tersebut
            $highestBid = $bidModel->where('item_id', $item['id'])
                                   ->orderBy('bid_amount', 'DESC')
                                   ->first();

            // Jika ada bid tertinggi, masukkan ke tabel transaksi
            if ($highestBid) {
                $transactionData = [
                    'user_id'     => $highestBid['user_id'],
                    'item_id'     => $item['id'],
                    'final_price' => $highestBid['bid_amount'],
                    'status'      => 'unpaid',
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                ];
                $transactionModel->insert($transactionData);
            }

            // Update status item menjadi selesai
            $itemModel->update($item['id'], ['status' => 'inactive']);
        }

        return "Bids finalized successfully.";
    }
}
