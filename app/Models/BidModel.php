<?php

namespace App\Models;

use CodeIgniter\Model;

class BidModel extends Model
{
    protected $table      = 'bids';
    protected $primaryKey = 'id';

    protected $allowedFields = ['item_id', 'user_id', 'bid_amount', 'transaction_id'];

    protected $useTimestamps = true;

    // Fungsi untuk mengambil bid tertinggi
    public function getHighestBid($itemId)
    {
        return $this->where('item_id', $itemId)
                    ->orderBy('bid_amount', 'DESC')
                    ->first();
    }
}
