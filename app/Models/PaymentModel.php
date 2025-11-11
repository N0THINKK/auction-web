<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['transaction_id', 'amount', 'payment_date', 'payment_method', 'status', 'created_at', 'updated_at'];
}
