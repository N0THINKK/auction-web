<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';  // Nama tabel yang menyimpan data pengguna
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'email', 'password', 'credit_card'];  // Kolom yang dapat diubah
    protected $useTimestamps = true;  // Untuk penggunaan waktu

    // Validasi data pengguna
    protected $validationRules = [
        'email' => 'required|valid_email',
        'password' => 'required|min_length[8]',
    ];

    protected $validationMessages = [
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please provide a valid email address',
        ],
        'password' => [
            'required' => 'Password is required',
            'min_length' => 'Password should be at least 8 characters',
        ],
    ];
}
