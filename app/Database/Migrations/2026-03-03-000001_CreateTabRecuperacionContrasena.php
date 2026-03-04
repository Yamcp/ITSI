<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTabRecuperacionContrasena extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'ID_RECUPERACION' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ID_USUARIO' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'TOKEN' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
            ],
            'EXPIRA_EN' => [
                'type' => 'DATETIME',
            ],
            'USADO' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'CREADO_EN' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('ID_RECUPERACION', true);
        $this->forge->addKey('TOKEN');
        $this->forge->addKey('EXPIRA_EN');
        $this->forge->createTable('TAB_RECUPERACION_CONTRASENA');
    }

    public function down(): void
    {
        $this->forge->dropTable('TAB_RECUPERACION_CONTRASENA');
    }
}
