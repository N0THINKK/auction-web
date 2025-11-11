<?php

namespace App\Controllers;

use App\Models\PaymentModel;

class Payments extends BaseController
{
    public function index()
    {
        $paymentModel = new PaymentModel();
        
        // Ambil semua pembayaran dari tabel payments
        $payments = $paymentModel->findAll();

        return view('payments/index', ['payments' => $payments]);
    }
}
