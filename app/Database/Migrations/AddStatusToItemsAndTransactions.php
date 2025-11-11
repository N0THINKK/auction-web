<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToItemsAndTransactions extends Migration
{
    public function up()
    {
        // Menambahkan status pada tabel items
        $this->forge->addColumn('items', [
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'active',
            ],
        ]);

        // Menambahkan status pada tabel transactions
        $this->forge->addColumn('transactions', [
            'status' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'incomplete',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('items', 'status');
        $this->forge->dropColumn('transactions', 'status');
    }
}
