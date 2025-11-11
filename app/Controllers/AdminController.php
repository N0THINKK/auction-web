<?php

namespace App\Controllers;

use App\Models\PaymentModel;


class AdminController extends BaseController
{
    public function verifications()
    {
        $paymentModel = new PaymentModel();

        // Ambil pembayaran dengan status waiting_verification
        $payments = $paymentModel->where('status', 'waiting_verification')->findAll();

        return view('admin/verifications', ['payments' => $payments]);
    }

    public function verification($paymentId)
    {
        $paymentModel = new PaymentModel();

        $payment = $paymentModel->find($paymentId);

        if (!$payment) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Payment not found');
        }

        return view('admin/verification', ['payment' => $payment]);
    }

    public function verify($paymentId)
    {
        $paymentModel = new PaymentModel();

        $action = $this->request->getPost('action');

        if ($action === 'approve') {
            // Set pembayaran menjadi completed
            $paymentModel->update($paymentId, ['status' => 'completed']);
        } elseif ($action === 'reject') {
            // Set pembayaran kembali ke pending
            $paymentModel->update($paymentId, ['status' => 'pending']);
        }

        return redirect()->to('/admin/verifications')->with('success', 'Pembayaran berhasil diperbarui.');
    }

}
