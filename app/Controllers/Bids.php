<?php

namespace App\Controllers;

use App\Models\BidModel;
use App\Models\ItemModel;


class Bids extends BaseController
{
    public function create($itemId)
    {
        // Ambil user_id yang sedang login
        $userId = session()->get('user_id'); 
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Anda harus login untuk melakukan bid.');
        }

        // Ambil model Bid dan Item
        $bidModel = new BidModel();
        $itemModel = new ItemModel();

        // Ambil data item berdasarkan ID
        $item = $itemModel->find($itemId);

        // Pastikan item ada
        if (!$item) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Item tidak ditemukan.');
        }

        // Periksa apakah user adalah pembuat item
        if ($item['created_by'] == $userId) {
            return redirect()->back()->with('error', 'Anda tidak dapat melakukan bid pada item yang Anda buat.');
        }

        // Ambil bid tertinggi untuk item tersebut
        $highestBid = $bidModel->where('item_id', $itemId)
                               ->orderBy('bid_amount', 'DESC')
                               ->first();

        // Tentukan harga saat ini (harga tertinggi atau harga awal jika tidak ada bid)
        $currentPrice = $highestBid ? $highestBid['bid_amount'] : $item['starting_price'];

        // Validasi bid amount
        $bidAmount = $this->request->getPost('bid_amount');
        if ($bidAmount <= $currentPrice) {
            return redirect()->to('/lelang/view/' . $itemId)
                             ->with('error', 'Bid Anda harus lebih tinggi dari harga saat ini.');
        }

        $existingBid = $bidModel->where('item_id', $itemId)
                                ->where('user_id', $userId)
                                ->first();

        if ($existingBid) {
            // Update bid yang sudah ada
            $existingBid['bid_amount'] = $bidAmount;

            if ($bidModel->update($existingBid['id'], $existingBid)) {
                return redirect()->to('/lelang/view/' . $itemId)
                                 ->with('success', 'Bid Anda telah berhasil diperbarui!');
            } else {
                return redirect()->to('/lelang/view/' . $itemId)
                                 ->with('error', 'Terjadi kesalahan saat memperbarui bid, coba lagi nanti.');
            }
        } else {
            // Simpan bid baru ke database
            $data = [
                'item_id'     => $itemId,
                'user_id'     => $userId,
                'bid_amount'  => $bidAmount,
            ];

            if ($bidModel->save($data)) {
                return redirect()->to('/lelang/view/' . $itemId)
                                 ->with('success', 'Bid Anda telah berhasil disimpan!');
            } else {
                return redirect()->to('/lelang/view/' . $itemId)
                                 ->with('error', 'Terjadi kesalahan, coba lagi nanti.');
            }
        }
    }
}
