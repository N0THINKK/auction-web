<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table      = 'items';  // Nama tabel di database
    protected $primaryKey = 'id';     // Primary key di tabel
    protected $allowedFields = ['name', 'description', 'starting_price', 'end_time', 'created_by', 'status', 'created_at', 'updated_at'];  // Kolom yang boleh diisi

    // Aktifkan timestamps untuk otomatis mengisi created_at dan updated_at
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

}
