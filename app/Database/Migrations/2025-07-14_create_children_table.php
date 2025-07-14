<?php
// app/Database/Migrations/2025-07-14_create_children_table.php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateChildrenTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'auto_increment' => true],
            'member_uuid' => ['type' => 'CHAR', 'constraint' => 36],
            'name'       => ['type' => 'VARCHAR', 'constraint' => 100],
            'birthdate'  => ['type' => 'DATE'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('children');
    }

    public function down()
    {
        $this->forge->dropTable('children');
    }
}
