<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLogoTabInstitucionesConvenios extends Migration
{
    public function up(): void
    {
        if (!$this->db->fieldExists('LOGO', 'TAB_INSTITUCIONES_CONVENIOS')) {
            $this->forge->addColumn('TAB_INSTITUCIONES_CONVENIOS', [
                'LOGO' => [
                    'type'       => 'VARCHAR',
                    'constraint' => 255,
                    'null'       => true,
                    'after'      => 'EMAIL_CONTACTO',
                ],
            ]);
        }
    }

    public function down(): void
    {
        if ($this->db->fieldExists('LOGO', 'TAB_INSTITUCIONES_CONVENIOS')) {
            $this->forge->dropColumn('TAB_INSTITUCIONES_CONVENIOS', 'LOGO');
        }
    }
}
