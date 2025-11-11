<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\BidModel;

class Lelang extends BaseController
{
    public function index()
    {
        $itemModel = new ItemModel();
        $bidModel = new BidModel();
        
        // Ambil semua barang lelang
        $items = $itemModel->findAll();  

        // Ambil bid tertinggi untuk setiap item
        $itemsWithBids = [];
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

        return view('lelang/index', ['itemsWithBids' => $itemsWithBids]);
    }

    // Method lainnya...
}
