<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEndTimeToItems extends Migration
{
    public function up()
    {
        $this->forge->addColumn('items', [
            'end_time' => [
                'type'       => 'DATETIME',
                'null'       => false,
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('items', 'end_time');
    }
}
